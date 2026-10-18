<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Professor | Vanguard</title>
    <link rel="stylesheet" href="css/dash-professor.css">
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
                <a href="dash-professor.php" class="nav-item active" aria-current="page">
                    <img src="imagem/casa-verde.png" alt="" aria-hidden="true">
                    <span>DashBoard</span>
                </a>
                <a href="salas-professor.php" class="nav-item">
                    <img src="imagem/salas-branco.png" alt="" aria-hidden="true">
                    <span>Salas</span>
                    <i class="seta" aria-hidden="true"></i>
                </a>
                <a href="calendario-professor.php" class="nav-item">
                    <img src="imagem/calendario-01.png" alt="" aria-hidden="true">
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
                <h1 class="dash-titulo">Dashboard do <span>Professor</span></h1>

                <div class="dash-grid">
                    <div class="dash-principal">
                        <section class="stats" aria-label="Resumo">
                            <article class="stat-card">
                                <div class="stat-icone"><img src="imagem/grupo-verde.png" alt=""></div>
                                <div class="stat-info">
                                    <h3>Turma</h3>
                                    <strong>5</strong>
                                    <small>Turmas ativas</small>
                                </div>
                            </article>
                            <article class="stat-card">
                                <div class="stat-icone"><img src="imagem/livro_01.png" alt=""></div>
                                <div class="stat-info">
                                    <h3>Aulas de hoje</h3>
                                    <strong>5</strong>
                                    <small>Turmas ativas</small>
                                </div>
                            </article>
                            <article class="stat-card">
                                <div class="stat-icone"><img src="imagem/pendentes.png" alt=""></div>
                                <div class="stat-info">
                                    <h3>Atividades pendentes</h3>
                                    <strong>5</strong>
                                    <small>Turmas ativas</small>
                                </div>
                            </article>
                        </section>

                        <section class="card cronograma">
                            <div class="card-cab">
                                <h2><img src="imagem/calendario_01.png" alt="">Cronograma Semanal</h2>
                                <a href="#" class="link-ano">cronograma do ano &#10140;</a>
                            </div>

                            <span class="mes-label">Setembro</span>
                            <table class="semana">
                                <thead>
                                    <tr>
                                        <th>DOM</th><th>Seg</th><th>TER</th><th>QUA</th><th>QUI</th><th>Sex</th><th>Sáb</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                    <tr><td></td><td class="vazio"></td><td class="vazio"></td><td class="vazio"></td><td class="vazio"></td><td class="vazio"></td><td class="vazio"></td></tr>
                                </tbody>
                            </table>

                            <div class="legenda">
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
                            </div>
                        </section>

                        <section class="card lembretes">
                            <h2><img src="imagem/lembretes.png" alt="">Lembretes pessoais</h2>
                            <ul>
                                <li>Foi adicionado um lembrete " Projeto integrador - data inicial " na data de 15/10</li>
                                <li>Foi adicionado um lembrete " Projeto integrador - data final " na data de 09/12</li>
                                <li></li>
                            </ul>
                        </section>
                    </div>

                    <div class="dash-lateral">
                        <section class="card campanha">
                            <h2><img src="imagem/comunicado.png" alt="">Campanha do mês</h2>
                            <img src="imagem/evento_01.jpg" alt="Setembro Amarelo" class="campanha-img">
                            <a href="#" class="link-ano">Ver próximos eventos &#10140;</a>
                        </section>

                        <section class="card lateral-card">
                            <h2 class="centro"><img src="imagem/modificacoes.png" alt="">Modificações recentes</h2>
                            <ul class="timeline">
                                <li>
                                    <time>08:06</time>
                                    <p>Ouve uma modificação no calendário do mês de Dezembro</p>
                                </li>
                                <li>
                                    <time>10:27</time>
                                    <p>Ouve alteração na aula de segunda.</p>
                                </li>
                            </ul>
                        </section>

                        <section class="card lateral-card">
                            <h2 class="centro"><img src="imagem/proxima-aula.png" alt="">Próximas aulas</h2>
                            <ul class="timeline">
                                <li>
                                    <time>07:45</time>
                                    <p>3MD - SOP - 4 Aulas</p>
                                </li>
                                <li>
                                    <time>13:00</time>
                                    <p>2MA - SOP - 4 Aulas</p>
                                </li>
                            </ul>
                        </section>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
