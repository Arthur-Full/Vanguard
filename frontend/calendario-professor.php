<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário | Vanguard</title>
    <link rel="stylesheet" href="css/calendario-professor.css">
</head>

<body>
    <div class="page-shell">
        <aside class="sidebar">
            <div class="brand-wrap">
                <img src="imagem/logo-maior.png" alt="Vanguard" class="brand-logo">
            </div>

            <div class="user-box">
                <div class="user-avatar">
                    <img src="imagem/perfil.png" alt="Alce Barbosa">
                </div>
                <div class="user-name-wrap">
                    <strong>Alce Barbosa</strong>
                    <span>Professor</span>
                </div>
            </div>

            <nav class="side-nav" aria-label="Navegação principal">
                <a href="dash-professor.php" class="nav-item">
                    <img src="imagem/casa-branca.png" alt="" aria-hidden="true">
                    <span>DashBoard</span>
                </a>
                <a href="salas-professor.php" class="nav-item">
                    <img src="imagem/salas-branco.png" alt="" aria-hidden="true">
                    <span>Salas</span>
                    <i class="seta" aria-hidden="true"></i>
                </a>
                <a href="calendario-professor.php" class="nav-item active" aria-current="page">
                    <img src="imagem/calendario_01.png" alt="" aria-hidden="true">
                    <span>Calendário / aulas do dia</span>
                </a>
                <a href="notas-professor.php" class="nav-item">
                    <img src="imagem/atividades-branco.png" alt="" aria-hidden="true">
                    <span>Atividades</span>
                </a>
                <a href="notas-professor.php" class="nav-item">
                    <img src="imagem/notas-branco.png" alt="" aria-hidden="true">
                    <span>Notas</span>
                </a>
            </nav>
        </aside>

        <div class="main-column">
            <header class="topbar">
                <a href="#" class="logout" aria-label="Sair">
                    <img src="imagem/exit.png" alt="" aria-hidden="true">
                </a>
            </header>

<main class="content-area">
    <h1 class="cal-titulo"><span>Calendário Anual</span> e <span>aulas</span> do dia</h1>

    <section class="cal-painel">
        <span class="cal-aba">Cronograma Anual</span>

        <div class="cal-meses">
            <a href="#" class="cal-mes"><img src="imagem/calendario-janeiro.png" alt="Janeiro"></a>
            <a href="#" class="cal-mes"><img src="imagem/calendario-fevereiro.png" alt="Fevereiro"></a>
            <a href="#" class="cal-mes"><img src="imagem/calendario-marco.png" alt="Março"></a>
            <a href="#" class="cal-mes"><img src="imagem/calendario-abril.png" alt="Abril"></a>
            <a href="#" class="cal-mes"><img src="imagem/calendario-maio.png" alt="Maio"></a>
            <a href="#" class="cal-mes"><img src="imagem/calendario-junho.png" alt="Junho"></a>
            <a href="#" class="cal-mes"><img src="imagem/calendario-julho.png" alt="Julho"></a>
            <a href="#" class="cal-mes"><img src="imagem/calendario-agosto.png" alt="Agosto"></a>
            <a href="#" class="cal-mes"><img src="imagem/calendario-setembro.png" alt="Setembro"></a>
            <a href="#" class="cal-mes"><img src="imagem/calendario-outubro.png" alt="Outubro"></a>
            <a href="#" class="cal-mes"><img src="imagem/calendario-novembro.png" alt="Novembro"></a>
            <a href="#" class="cal-mes"><img src="imagem/calendario-dezembro.png" alt="Dezembro"></a>
        </div>

        <div class="cal-legenda">
            <div>
                <h4>Feriados Nacionais</h4>
                <p>Independência do Brasil - 7 de setembro</p>
            </div>
            <div>
                <h4>Feriado municipal</h4>
                <p>---</p>
            </div>
            <div>
                <h4>Eventos escolares</h4>
                <p>---</p>
            </div>
            <div>
                <h4>Lembretes do mês</h4>
                <p>---</p>
            </div>
        </div>
    </section>

    <section class="cal-aulas">
        <h2><img src="imagem/calendario_01.png" alt="">Aulas e salas da semana</h2>

        <div class="cal-dia">
            <h3>Segunda</h3>
            <span class="cal-pill">3 Aulas</span>
        </div>

        <ul class="cal-lista">
            <li>
                <img src="imagem/relogio.png" alt="">
                <strong>7:45 - 11:00</strong>
                <span class="cal-pill">D0-3</span>
            </li>
            <li>
                <img src="imagem/relogio.png" alt="">
                <strong>13:40 - 17:20</strong>
                <span class="cal-pill">D2-3</span>
            </li>
            <li>
                <img src="imagem/relogio.png" alt="">
                <strong>18:00 - 22:00</strong>
                <span class="cal-pill">D0-6</span>
            </li>
        </ul>
    </section>
</main>
        </div>
    </div>
</body>

</html>
