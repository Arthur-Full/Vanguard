<?php
require_once __DIR__ . '/../backend/config/auth.php';
exigirPerfil(['admin', 'coordenador', 'professor', 'aluno']);
$sessao = usuarioAtual();
$pdo = getConnection();
$idPerfilSolicitado = filter_var($_GET['aluno'] ?? null, FILTER_VALIDATE_INT);
$perfil = $sessao;
if ($idPerfilSolicitado) {
    if (!in_array($sessao['tipo_usuario'], ['admin','coordenador','professor'], true)) { http_response_code(403); exit('Acesso não autorizado.'); }
    $stmt = $pdo->prepare("SELECT u.id_usuario,u.id_instituicao,u.nome,u.email,u.telefone,u.tipo_usuario,u.foto_perfil,GROUP_CONCAT(DISTINCT t.nome ORDER BY t.nome SEPARATOR ', ') AS turmas,GROUP_CONCAT(DISTINCT t.curso ORDER BY t.curso SEPARATOR ', ') AS cursos FROM usuarios u LEFT JOIN matriculas m ON m.id_usuario=u.id_usuario AND m.status='ativa' LEFT JOIN turmas t ON t.id_turma=m.id_turma WHERE u.id_usuario=:id AND u.tipo_usuario='aluno' GROUP BY u.id_usuario");
    $stmt->execute([':id'=>$idPerfilSolicitado]);
    $perfil = $stmt->fetch();
    if (!$perfil) { http_response_code(404); exit('Aluno não encontrado.'); }
    if ($sessao['tipo_usuario']==='professor') {
        $permite=$pdo->prepare("SELECT 1 FROM matriculas m JOIN professor_turmas pt ON pt.id_turma=m.id_turma WHERE m.id_usuario=:aluno AND m.status='ativa' AND pt.id_professor=:professor LIMIT 1");
        $permite->execute([':aluno'=>$idPerfilSolicitado,':professor'=>(int)$sessao['id_usuario']]);
        if (!$permite->fetchColumn()) { http_response_code(403); exit('Aluno fora das suas turmas.'); }
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
                <p class="nav-seguidos"><span><b>Ponto Forte:</b></span> [Respectivo Curso]</p>
                <p class="nav-seguidos"><span><b>Telefone:</b></span> <?= $perfilTelefone ?></p>
                <p class="nav-seguidos"><span><b>E-mail:</b></span> <?= $perfilEmail ?></p>
                <p class="nav-item"><img class="nav-icon" src="imagem/localização.png" alt="Curso">[Respectiva Localização]</p>
                <p class="nav-item"><img class="nav-icon" src="imagem/github.png" alt="">[Respectivo GitHub]</p>
                <p class="nav-item"><img class="nav-icon" src="imagem/linkedin.png" alt="">[Respectivo LinkedIn]</p>

            </nav>
        </aside>

        <div class="main-column">
            <header class="topbar">
                <a href="sair.php" class="logout" aria-label="Sair">
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
                        <a href="#" class="pf-curriculo">
                            <img src="imagem/curriculo.png" alt="Currículo de Samuel">
                        </a>

                        <div class="pf-add">
                            <img src="imagem/add.png" alt="">
                            <h1>+</h1>
                        </div>
                    </div>

                </section>
            </main>
        </div>

    </div>
    </div>
</body>

</html>
