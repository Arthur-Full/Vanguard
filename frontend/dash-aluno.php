<?php require_once __DIR__ . '/../backend/config/auth.php'; exigirPerfil(['aluno']); $usuario=usuarioAtual(); $nomeUsuario=htmlspecialchars($usuario['nome'],ENT_QUOTES,'UTF-8'); ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do aluno | Vanguard</title>
    <link rel="stylesheet" href="css/dash-aluno.css">
    <link rel="stylesheet" href="css/calendario-aluno.css?v=20261018-events">
    <script src="js/calendario-aluno.js?v=20261018" defer></script>
</head>

<body>
    <aside class="sidebar">
        <div class="brand-wrap">
            <img src="imagem/logo-maior.png" alt="Vanguard" class="brand-logo">
        </div>
        <div class="user-box">
            <div class="user-avatar"><img src="imagem/perfil.png" alt="<?= $nomeUsuario ?>"></div>
            <div class="user-name-wrap"><strong><?= $nomeUsuario ?></strong><span>Aluno</span></div>
        </div>
        <nav class="side-nav" aria-label="Navegação principal">
            <a href="dash-aluno.php" class="nav-item active" aria-current="page"><img src="imagem/casa-verde.png" alt="" aria-hidden="true"><span>DashBoard</span></a>
            <a href="calendario-aluno.php" class="nav-item"><img src="imagem/calendario_01.png" alt="" aria-hidden="true"><span>Calendário</span></a>
            <a href="aulas-aluno.php" class="nav-item"><img src="imagem/livro-branco.png" alt="" aria-hidden="true"><span>Aulas</span></a>
            <a href="perfil.php" class="nav-item"><img src="imagem/perfil.png" alt="" aria-hidden="true"><span>Perfil</span></a>
        </nav>
    </aside>

    <main class="page main-column" id="inicio">
        <header class="topbar">
            <a class="logout" href="sair.php" aria-label="Sair" title="Sair"><img src="imagem/exit.png" alt=""></a>
        </header>

        <div class="dashboard">
            <section class="welcome" aria-label="Boas-vindas">
                <h1>Olá, <span><?= $nomeUsuario ?>!</span></h1>
                <p>Confira seus próximos eventos e não perca nenhuma atividade</p>
            </section>

            <div class="dashboard-grid">
                <div class="primary-column">
                    <section class="summary" aria-label="Resumo">
                        <article class="summary-card">
                            <div class="summary-icon"><img src="imagem/calendario_01.png" alt=""></div>
                            <div>
                                <h2>Próximo evento</h2>
                                <p class="summary-number" id="prox-evento-dias">—</p>
                                <p class="summary-caption" id="prox-evento-titulo">Aguardando agenda</p>
                            </div>
                        </article>
                        <article class="summary-card">
                            <div class="summary-icon"><img src="imagem/livro_01.png" alt=""></div>
                            <div>
                                <h2>Aulas esta semana</h2>
                                <p class="summary-number">4</p>
                                <p class="summary-caption">em andamento</p>
                            </div>
                        </article>
                    </section>

                    <section class="calendar-panel" id="calendario" aria-labelledby="calendar-title">
                        <div class="panel-heading"><h2 id="calendar-title"><img src="imagem/calendario-verde.png" alt="">Cronograma mensal</h2><a href="calendario-aluno.php">Calendario anual <span aria-hidden="true">&#10132;</span></a></div>
                        <p class="month-label" id="dashboard-month-label"></p>
                        <table class="calendar-grid" aria-label="Calendario mensal"><thead><tr><th scope="col">Dom</th><th scope="col">Seg</th><th scope="col">Ter</th><th scope="col">Qua</th><th scope="col">Qui</th><th scope="col">Sex</th><th scope="col">Sab</th></tr></thead><tbody id="dashboard-calendar-days"></tbody></table>
                        <section class="dashboard-month-events"><h3>Eventos deste mes</h3><ul id="dashboard-month-events" aria-live="polite"><li>Carregando eventos...</li></ul></section>
                    </section>
                </div>

                <aside class="right-column" aria-label="Avisos e atualizações">
                    <section class="campaign-panel">
                        <h2><img src="imagem/comunicado.png" alt="">Campanha do mês</h2>
                        <img class="campaign-image" src="imagem/evento_01.jpg" alt="Campanha Setembro Amarelo">
                        <a class="campaign-link" href="#eventos">Ver próximos eventos <span aria-hidden="true">➜</span></a>
                    </section>

                    <section class="updates-panel" id="aulas">
                        <h2><img class="updates-icon" src="imagem/modificacoes.png" alt="">Modificações recentes</h2>
                        <ol class="updates-list">
                            <li><time datetime="08:06">08:06</time><p>Houve uma modificação no calendário do mês de Dezembro</p></li>
                            <li><time datetime="10:27">10:27</time><p>Houve alteração na aula de segunda.</p></li>
                        </ol>
                    </section>
                </aside>
            </div>
        </div>
    </main>
</body>
</html>
