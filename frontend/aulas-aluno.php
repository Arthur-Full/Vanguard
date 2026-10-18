<?php require_once __DIR__ . '/../backend/config/auth.php'; exigirPerfil(['aluno']); $nomeUsuario = htmlspecialchars(usuarioAtual()['nome'], ENT_QUOTES, 'UTF-8'); ?>
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
                        <button class="tab">Segunda <span>SOP</span></button>
                        <button class="tab">Terça <span>ARI</span></button>
                        <button class="tab">Quarta <span>LOPAL</span></button>
                        <button class="tab">Quinta <span>BCD</span></button>
                        <button class="tab">Sexta <span>BKN</span></button>
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
