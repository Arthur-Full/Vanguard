<?php
require_once __DIR__ . '/../backend/config/auth.php';
exigirPerfil(['aluno']);
$usuario=usuarioAtual();$pdo=getConnection();$idAluno=(int)$usuario['id_usuario'];
$esc=static fn($value)=>htmlspecialchars((string)$value,ENT_QUOTES,'UTF-8');$nomeUsuario=$esc($usuario['nome']);
$stmt=$pdo->prepare("SELECT t.id_turma,t.nome AS turma,GROUP_CONCAT(DISTINCT d.nome ORDER BY d.nome SEPARATOR ', ') AS disciplina FROM matriculas m JOIN turmas t ON t.id_turma=m.id_turma LEFT JOIN professor_turmas pt ON pt.id_turma=t.id_turma LEFT JOIN disciplinas d ON d.id_disciplina=pt.id_disciplina WHERE m.id_usuario=:aluno AND m.status='ativa' AND t.status='ativa' AND t.id_instituicao=:instituicao GROUP BY t.id_turma,t.nome ORDER BY t.nome");
$stmt->execute([':aluno'=>$idAluno,':instituicao'=>(int)$usuario['id_instituicao']]);$turmas=$stmt->fetchAll();
$idTurma=filter_var($_GET['turma']??($turmas[0]['id_turma']??null),FILTER_VALIDATE_INT);$turmaAtual=null;foreach($turmas as $turma)if((int)$turma['id_turma']===(int)$idTurma){$turmaAtual=$turma;break;}if(!$turmaAtual&&$turmas){$turmaAtual=$turmas[0];$idTurma=(int)$turmaAtual['id_turma'];}
$notasAluno=[];if($turmaAtual){$stmt=$pdo->prepare('SELECT tipo,titulo,nota,data_avaliacao FROM avaliacoes_alunos WHERE id_aluno=:aluno AND id_turma=:turma ORDER BY data_avaliacao DESC,id_avaliacao DESC');$stmt->execute([':aluno'=>$idAluno,':turma'=>(int)$turmaAtual['id_turma']]);$notasAluno=$stmt->fetchAll();$stmt=$pdo->prepare("SELECT 'atividade' AS tipo,a.titulo,e.nota,DATE(e.avaliado_em) AS data_avaliacao FROM entregas_atividades e JOIN atividades a ON a.id_atividade=e.id_atividade WHERE e.id_aluno=:aluno AND a.id_turma=:turma AND e.nota IS NOT NULL ORDER BY e.avaliado_em DESC");$stmt->execute([':aluno'=>$idAluno,':turma'=>(int)$turmaAtual['id_turma']]);$notasAluno=array_merge($notasAluno,$stmt->fetchAll());usort($notasAluno,static fn($a,$b)=>strcmp((string)$b['data_avaliacao'],(string)$a['data_avaliacao']));}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aulas do aluno | Vanguard</title>
    <link rel="stylesheet" href="css/aulas-aluno.css">
</head>
<body>
    <div class="page-shell">
        <aside class="sidebar">
            <div class="brand-wrap">
                <img src="imagem/logo-maior.png" alt="Vanguard" class="brand-logo">
            </div>

            <div class="user-box">
                <div class="user-avatar">
                    <img src="imagem/perfil.png" alt="<?= $nomeUsuario ?>">
                </div>
                <div class="user-name-wrap">
                    <strong><?= $nomeUsuario ?></strong>
                    <span>Aluno</span>
                </div>
            </div>

            <nav class="side-nav" aria-label="Navegação principal">
                <a href="dash-aluno.php" class="nav-item">
                    <img src="imagem/casa-branca.png" alt="" aria-hidden="true">
                    <span>DashBoard</span>
                </a>
                <a href="calendario-aluno.php" class="nav-item">
                    <img src="imagem/calendario-01.png" alt="" aria-hidden="true">
                    <span>Calendário</span>
                </a>
                <a href="aulas-aluno.php" class="nav-item active" aria-current="page">
                    <img src="imagem/livro_01.png" alt="" aria-hidden="true">
                    <span>Aulas</span>
                </a>
                <a href="perfil.php" class="nav-item">
                    <img src="imagem/perfil.png" alt="" aria-hidden="true">
                    <span>Perfil</span>
                </a>
            </nav>
        </aside>

        <div class="main-column">
            <header class="topbar">
                <a href="sair.php" class="logout" aria-label="Sair">
                    <img src="imagem/exit.png" alt="" aria-hidden="true">
                </a>
            </header>

            <main class="content-area">
                <div class="content-panel">
                    <h2 class="section-heading">Salas</h2>
                    <div class="room-tabs" aria-label="Salas">
<?php foreach($turmas as $turma): ?><a class="tab" style="color:inherit;text-decoration:none" href="?turma=<?= (int)$turma['id_turma'] ?>" <?= (int)$turma['id_turma']===(int)$idTurma?'aria-current="page"':'' ?>><?= $esc($turma['turma']) ?> <span><?= $esc($turma['disciplina']??'') ?></span></a><?php endforeach; ?>
<?php if(!$turmas): ?><p>Você ainda não possui matrícula ativa em uma sala.</p><?php endif; ?>
</div>

                    <section class="notes-panel" aria-labelledby="anotacoes-title">
                        <h2 id="anotacoes-title"><span>Anotações</span><img src="imagem/anotacoes.png" alt="" aria-hidden="true"></h2>
                        <div class="notes-grid">
                            <article class="note-card add-card" aria-label="Adicionar anotação">
                                <span class="plus-sign">+</span>
                            </article>

                            <article class="note-card empty-card"></article>
                            <article class="note-card empty-card"></article>
                            <article class="note-card empty-card"></article>
                            <article class="note-card empty-card"></article>

                            <article class="note-card">
                                <p>Projeto de <br>Software</p>
                            </article>

                            <article class="note-card">
                                <p>Atividade de <br>Arquitetura de<br>Redes</p>
                            </article>

                            <article class="note-card">
                                <p>Trabalho de <br>Back End</p>
                            </article>

                            <article class="note-card">
                                <p>Trabalho de <br>Front End</p>
                            </article>

                            <article class="note-card">
                                <p>Atividade de <br>Sistemas<br>Operacionais</p>
                            </article>
                        </div>
                    </section>

<section class="notes-panel" aria-labelledby="notas-title">
    <h2 id="notas-title"><span>Notas<?= $turmaAtual?' · '.$esc($turmaAtual['turma']):'' ?></span><img src="imagem/notas-verde.png" alt="" aria-hidden="true"></h2>
    <div class="notes-grid">
        <?php if(!$notasAluno): ?><article class="note-card empty-card"><p>Ainda não há notas nesta sala.</p></article><?php else: foreach($notasAluno as $nota): ?><article class="note-card"><p><strong><?= $esc(ucfirst($nota['tipo'])) ?></strong><br><?= $esc($nota['titulo']) ?><br>Nota: <?= number_format((float)$nota['nota'],1,',','.') ?>/10<br><?= $nota['data_avaliacao']?$esc(date('d/m/Y',strtotime($nota['data_avaliacao']))):'' ?></p></article><?php endforeach; endif; ?>
    </div>
</section>
                    <section class="attachments-panel" aria-labelledby="anexos-title">
                        <h2 id="anexos-title"><span>Anexos</span><img src="imagem/anexo.png" alt="" aria-hidden="true"></h2>
                        <button class="add-attachment" type="button">Adicionar anexo</button>
                    </section>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
