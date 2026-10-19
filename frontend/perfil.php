<?php
require_once __DIR__ . '/../backend/config/auth.php';
exigirPerfil(['admin', 'coordenador', 'professor', 'aluno']);
$sessao = usuarioAtual();
$pdo = getConnection();
$idPerfilSolicitado = filter_var($_GET['aluno'] ?? null, FILTER_VALIDATE_INT);
$perfil = $sessao;
$podeEditarPerfil = ($sessao['tipo_usuario'] ?? '') === 'aluno' && !$idPerfilSolicitado;
$mensagemPerfil = '';
$erroPerfil = '';
if (empty($_SESSION['csrf_perfil'])) { $_SESSION['csrf_perfil'] = bin2hex(random_bytes(32)); }
if ($idPerfilSolicitado) {
    if (!in_array($sessao['tipo_usuario'], ['admin','coordenador','professor'], true)) { http_response_code(403); exit('Acesso não autorizado.'); }
    $stmt = $pdo->prepare("SELECT u.id_usuario,u.id_instituicao,u.nome,u.email,u.telefone,u.ponto_forte,u.localizacao,u.github,u.linkedin,u.tipo_usuario,u.foto_perfil,GROUP_CONCAT(DISTINCT t.nome ORDER BY t.nome SEPARATOR ', ') AS turmas,GROUP_CONCAT(DISTINCT t.curso ORDER BY t.curso SEPARATOR ', ') AS cursos FROM usuarios u LEFT JOIN matriculas m ON m.id_aluno=u.id_usuario AND m.status='ativa' LEFT JOIN turmas t ON t.id_turma=m.id_turma WHERE u.id_usuario=:id AND u.tipo_usuario='aluno' GROUP BY u.id_usuario");
    $stmt->execute([':id'=>$idPerfilSolicitado]);
    $perfil = $stmt->fetch();
    if (!$perfil) { http_response_code(404); exit('Aluno não encontrado.'); }
    if ($sessao['tipo_usuario']==='professor') {
        $permite=$pdo->prepare("SELECT 1 FROM matriculas m JOIN professor_turmas pt ON pt.id_turma=m.id_turma WHERE m.id_aluno=:aluno AND m.status='ativa' AND pt.id_professor=:professor LIMIT 1");
        $permite->execute([':aluno'=>$idPerfilSolicitado,':professor'=>(int)$sessao['id_usuario']]);
        if (!$permite->fetchColumn()) { http_response_code(403); exit('Aluno fora das suas turmas.'); }
    }
}
$idAlunoAtual = (int)($sessao['id_usuario'] ?? 0);
if (!$idPerfilSolicitado && ($sessao['tipo_usuario'] ?? '') === 'aluno') {
    $stmt = $pdo->prepare("SELECT u.id_usuario,u.id_instituicao,u.nome,u.email,u.telefone,u.ponto_forte,u.localizacao,u.github,u.linkedin,u.tipo_usuario,u.foto_perfil,GROUP_CONCAT(DISTINCT t.nome ORDER BY t.nome SEPARATOR ', ') AS turmas,GROUP_CONCAT(DISTINCT t.curso ORDER BY t.curso SEPARATOR ', ') AS cursos FROM usuarios u LEFT JOIN matriculas m ON m.id_aluno=u.id_usuario AND m.status='ativa' LEFT JOIN turmas t ON t.id_turma=m.id_turma WHERE u.id_usuario=:id GROUP BY u.id_usuario");
    $stmt->execute([':id'=>$idAlunoAtual]);
    $perfil = $stmt->fetch() ?: $sessao;
}
if ($podeEditarPerfil && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'salvar_informacoes') {
    if (!hash_equals($_SESSION['csrf_perfil'], (string)($_POST['csrf_perfil'] ?? ''))) {
        $erroPerfil = 'Sua sessão expirou. Atualize a página e tente novamente.';
    } else {
        $dados = ['email'=>trim((string)($_POST['email']??'')), 'telefone'=>trim((string)($_POST['telefone']??'')), 'ponto_forte'=>trim((string)($_POST['ponto_forte']??'')), 'localizacao'=>trim((string)($_POST['localizacao']??'')), 'github'=>trim((string)($_POST['github']??'')), 'linkedin'=>trim((string)($_POST['linkedin']??''))];
        if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL) || strlen($dados['email']) > 150) {
            $erroPerfil = 'Informe um e-mail válido (até 150 caracteres).';
        } elseif (strlen($dados['telefone']) > 20 || strlen($dados['ponto_forte']) > 120 || strlen($dados['localizacao']) > 120 || strlen($dados['github']) > 255 || strlen($dados['linkedin']) > 255) {
            $erroPerfil = 'Confira os limites dos campos e tente novamente.';
        } else {
            try {
                $salvarPerfil = $pdo->prepare('UPDATE usuarios SET email=:email, telefone=:telefone, ponto_forte=:ponto_forte, localizacao=:localizacao, github=:github, linkedin=:linkedin WHERE id_usuario=:id AND tipo_usuario=\'aluno\'');
                $salvarPerfil->execute([':email'=>$dados['email'], ':telefone'=>$dados['telefone'] ?: null, ':ponto_forte'=>$dados['ponto_forte'] ?: null, ':localizacao'=>$dados['localizacao'] ?: null, ':github'=>$dados['github'] ?: null, ':linkedin'=>$dados['linkedin'] ?: null, ':id'=>$idAlunoAtual]);
                $perfil = array_merge($perfil, $dados);
                $_SESSION['usuario']['email'] = $dados['email'];
                $_SESSION['usuario']['telefone'] = $dados['telefone'];
                $mensagemPerfil = 'Informações atualizadas com sucesso.';
            } catch (PDOException $e) {
                $erroPerfil = (string)$e->getCode() === '23000' ? 'Este e-mail já está sendo usado por outra conta.' : 'Não foi possível salvar suas informações.';
            }
        }
    }
}
$mensagemCurriculo='';$erroCurriculo='';$podeEditarCurriculo=($sessao['tipo_usuario']??'')==='aluno' && !$idPerfilSolicitado;
$stmtCurriculo=$pdo->prepare('SELECT curriculo_path FROM usuarios WHERE id_usuario=:id AND tipo_usuario=\'aluno\'');
$stmtCurriculo->execute([':id'=>(int)($perfil['id_usuario']??$sessao['id_usuario'])]);
$curriculoPath=$stmtCurriculo->fetchColumn() ?: null;
if($podeEditarCurriculo && $_SERVER['REQUEST_METHOD']==='POST' && ($_POST['acao']??'')==='enviar_curriculo'){
    if(!isset($_FILES['curriculo']) || $_FILES['curriculo']['error']!==UPLOAD_ERR_OK){$erroCurriculo='Selecione um PDF de até 5 MB.';}
    elseif($_FILES['curriculo']['size']>5*1024*1024){$erroCurriculo='O arquivo excede o limite de 5 MB.';}
    else{
        $finfo=new finfo(FILEINFO_MIME_TYPE);$mime=$finfo->file($_FILES['curriculo']['tmp_name']);
        if($mime!=='application/pdf' || strtolower(pathinfo($_FILES['curriculo']['name'],PATHINFO_EXTENSION))!=='pdf'){$erroCurriculo='Envie um arquivo PDF válido.';}
        else{
            $diretorio=__DIR__.'/uploads/curriculos';
            if(!is_dir($diretorio) && !mkdir($diretorio,0755,true) && !is_dir($diretorio)){$erroCurriculo='Não foi possível preparar o armazenamento do currículo.';}
            else{
                $nomeArquivo=(int)$sessao['id_usuario'].'_'.bin2hex(random_bytes(16)).'.pdf';
                if(move_uploaded_file($_FILES['curriculo']['tmp_name'],$diretorio.'/'.$nomeArquivo)){
                    $novoCaminho='uploads/curriculos/'.$nomeArquivo;
                    $salvar=$pdo->prepare('UPDATE usuarios SET curriculo_path=:caminho WHERE id_usuario=:id AND tipo_usuario=\'aluno\'');
                    $salvar->execute([':caminho'=>$novoCaminho,':id'=>(int)$sessao['id_usuario']]);
                    if($curriculoPath && str_starts_with($curriculoPath,'uploads/curriculos/')){@unlink(__DIR__.'/'.$curriculoPath);}
                    $curriculoPath=$novoCaminho;$mensagemCurriculo='Currículo enviado com sucesso.';
                }else{$erroCurriculo='Não foi possível salvar o arquivo. Tente novamente.';}
            }
        }
    }
}
$perfilNome=htmlspecialchars($perfil['nome']??'',ENT_QUOTES,'UTF-8');
$perfilEmail=htmlspecialchars($perfil['email']??'',ENT_QUOTES,'UTF-8');
$perfilTelefone=htmlspecialchars($perfil['telefone']??'Não informado',ENT_QUOTES,'UTF-8');
$perfilSala=htmlspecialchars($perfil['turmas']??'Não informado',ENT_QUOTES,'UTF-8');
$perfilCurso=htmlspecialchars($perfil['cursos']??'Não informado',ENT_QUOTES,'UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil | Vanguard</title>
    <link rel="stylesheet" href="css/perfil.css">
</head>

<body>
    <div class="page-shell">
        <aside class="sidebar">
            <div class="brand-wrap">
                <img src="imagem/logo-maior.png" alt="Vanguard" class="brand-logo">
            </div>

            <div class="user-box">
                <div class="user-avatar">
                    <img src="<?= !empty($perfil['foto_perfil']) ? htmlspecialchars($perfil['foto_perfil'], ENT_QUOTES, 'UTF-8') : 'imagem/perfil.png' ?>" alt="<?= $perfilNome ?>">
                </div>
                <div class="user-name-wrap">
                    <strong><?= $perfilNome ?></strong>
                    <span><?= htmlspecialchars(ucfirst($perfil['tipo_usuario']??''), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>

            <nav class="side-nav" aria-label="Navegação principal">

                <p class="nav-seguidos"><span><b>Cursos:</b></span> <?= $perfilCurso ?></p>
                <p class="nav-seguidos"><span><b>Sala:</b></span> <?= $perfilSala ?></p>
                <?php if ($podeEditarPerfil): ?>
                <form class="perfil-aside-form" method="post">
                    <input type="hidden" name="acao" value="salvar_informacoes">
                    <input type="hidden" name="csrf_perfil" value="<?= htmlspecialchars($_SESSION['csrf_perfil'], ENT_QUOTES, 'UTF-8') ?>">
                    <?php if ($mensagemPerfil): ?><p class="perfil-feedback sucesso" role="status"><?= htmlspecialchars($mensagemPerfil, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
                    <?php if ($erroPerfil): ?><p class="perfil-feedback erro" role="alert"><?= htmlspecialchars($erroPerfil, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
                    <label>Ponto forte<input name="ponto_forte" maxlength="120" value="<?= htmlspecialchars($perfil['ponto_forte'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Ex.: programação"></label>
                    <label>Telefone<input name="telefone" type="tel" maxlength="20" value="<?= htmlspecialchars($perfil['telefone'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Seu telefone"></label>
                    <label>E-mail<input name="email" type="email" maxlength="150" required value="<?= htmlspecialchars($perfil['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></label>
                    <label>Localização<input name="localizacao" maxlength="120" value="<?= htmlspecialchars($perfil['localizacao'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Cidade, estado"></label>
                    <label>GitHub<input name="github" type="url" maxlength="255" value="<?= htmlspecialchars($perfil['github'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="https://github.com/usuario"></label>
                    <label>LinkedIn<input name="linkedin" type="url" maxlength="255" value="<?= htmlspecialchars($perfil['linkedin'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="https://linkedin.com/in/usuario"></label>
                    <button type="submit">Salvar informações</button>
                </form>
                <?php else: ?>
                <p class="nav-seguidos"><span><b>Ponto forte:</b></span> <?= htmlspecialchars($perfil['ponto_forte'] ?? 'Não informado', ENT_QUOTES, 'UTF-8') ?></p>
                <p class="nav-seguidos"><span><b>Telefone:</b></span> <?= $perfilTelefone ?></p>
                <p class="nav-seguidos"><span><b>E-mail:</b></span> <?= $perfilEmail ?></p>
                <p class="nav-seguidos"><span><b>Localização:</b></span> <?= htmlspecialchars($perfil['localizacao'] ?? 'Não informado', ENT_QUOTES, 'UTF-8') ?></p>
                <p class="nav-seguidos"><span><b>GitHub:</b></span> <?= htmlspecialchars($perfil['github'] ?? 'Não informado', ENT_QUOTES, 'UTF-8') ?></p>
                <p class="nav-seguidos"><span><b>LinkedIn:</b></span> <?= htmlspecialchars($perfil['linkedin'] ?? 'Não informado', ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </nav>
        </aside>

        <div class="main-column">
            <header class="topbar">
                <a href="dash-aluno.php" class="logout" aria-label="Sair">
                    <img src="imagem/exit.png" alt="" aria-hidden="true">
                </a>
            </header>

            <main class="content-area">
                <h2 class="pf-titulo">Perfil</h2>

                <section class="pf-secao">
                    <a href="#" class="pf-barra">
                        <h3>Projetos Escolares</h3>
                        <span class="pf-seta">→</span>
                    </a>

                    <article class="pf-projeto">
                        <img src="imagem/projeto-aluno.png" alt="Prévia do projeto">
                        <div class="pf-projeto-info">
                            <h4>Site Gerenciamento de Estoque</h4>
                            <span class="pf-membros"><img src="imagem/grupo.png" alt="Integrantes"> 2</span>
                        </div>
                    </article>

                    <article class="pf-projeto">
                        <img src="imagem/projeto-aluno.png" alt="Prévia do projeto">
                        <div class="pf-projeto-info">
                            <h4>Site Web-comerce</h4>
                            <span class="pf-membros"><img src="imagem/grupo.png" alt="Integrantes"> 4</span>
                        </div>
                    </article>

                    <article class="pf-projeto">
                        <img src="imagem/projeto-aluno.png" alt="Prévia do projeto">
                        <div class="pf-projeto-info">
                            <h4>Site Web-comerce</h4>
                            <span class="pf-membros"><img src="imagem/grupo.png" alt="Integrantes"> 3</span>
                        </div>
                    </article>
                </section>

                <section class="pf-secao pf-secao-menor">
                    <a href="#" class="pf-barra">
                        <h3>Feedback dos Professores</h3>
                        <span class="pf-seta">→</span>
                    </a>

                    <article class="pf-feedback">
                        <div class="pf-feedback-autor">
                            <img src="imagem/prof.png" alt="Professor Alice Barbosa">
                            <span>Professor Alice Barbosa</span>
                        </div>
                        <p>Muito bom aluno, entrega todas as atividades, mas é muito quieto.</p>
                    </article>
                </section>

                <section class="pf-secao pf-secao-menor">
                    <div class="pf-barra pf-barra-curta">
                        <h3>Anexo Currículo</h3>
                    </div>

                    <div class="pf-curriculo-wrap">
                        <?php if($curriculoPath): ?><a class="pf-curriculo-link" href="<?= htmlspecialchars($curriculoPath,ENT_QUOTES,'UTF-8') ?>" target="_blank" rel="noopener">Ver currículo (PDF)</a><?php else: ?><p class="pf-curriculo-vazio">Nenhum currículo enviado.</p><?php endif; ?>
                        <?php if($mensagemCurriculo): ?><p class="curriculo-feedback sucesso" role="status"><?= htmlspecialchars($mensagemCurriculo,ENT_QUOTES,'UTF-8') ?></p><?php endif; ?>
                        <?php if($erroCurriculo): ?><p class="curriculo-feedback erro" role="alert"><?= htmlspecialchars($erroCurriculo,ENT_QUOTES,'UTF-8') ?></p><?php endif; ?>
                        <?php if($podeEditarCurriculo): ?><form class="curriculo-form" method="post" enctype="multipart/form-data"><input type="hidden" name="acao" value="enviar_curriculo"><label for="curriculo">Adicionar ou substituir currículo em PDF (máximo 5 MB)</label><input id="curriculo" name="curriculo" type="file" accept="application/pdf,.pdf" required><button type="submit">Enviar currículo</button></form><?php endif; ?>
                    </div>

                </section>
            </main>
        </div>

    </div>
    </div>
</body>

</html>
