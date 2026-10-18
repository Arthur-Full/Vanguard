<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas | Vanguard</title>
    <link rel="stylesheet" href="css/notas-professor.css">
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

                <div class="nav-dropdown">
                    <a class="nav-item nav-toggle" tabindex="0" role="button" aria-controls="submenu-salas">
                        <img src="imagem/salas-branco.png" alt="" aria-hidden="true">
                        <span>Salas</span>
                        <i class="seta" aria-hidden="true"></i>
                    </a>
                    <div class="submenu" id="submenu-salas">
                        <ul>
                            <li><a href="salas-professor.php?sala=1MD">1MD</a></li>
                            <li><a href="salas-professor.php?sala=2MD">2MD</a></li>
                            <li><a href="salas-professor.php?sala=3MD">3MD</a></li>
                        </ul>
                    </div>
                </div>

                <a href="calendario-professor.php" class="nav-item">
                    <img src="imagem/calendario-01.png" alt="" aria-hidden="true">
                    <span>Calendário / aulas do dia</span>
                </a>
                <a href="notas-professor.php" class="nav-item">
                    <img src="imagem/atividades-branco.png" alt="" aria-hidden="true">
                    <span>Atividades</span>
                </a>
                <a href="#" class="nav-item active">
                    <img src="imagem/notas-verde.png" alt="" aria-hidden="true">
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
                <input type="radio" name="sala" id="sala-3md" class="nt-radio" checked aria-label="Sala 3MD">
                <input type="radio" name="sala" id="sala-2md" class="nt-radio" aria-label="Sala 2MD">
                <input type="radio" name="sala" id="sala-2pm" class="nt-radio" aria-label="Sala 2PM">
                <input type="radio" name="sala" id="sala-3pm" class="nt-radio" aria-label="Sala 3PM">

                <!-- Cards das salas: clicar em uma sala troca a lista de alunos (só CSS) -->
                <section class="nt-salas" aria-label="Salas">
                    <label class="nt-sala" for="sala-3md">
                        <div class="nt-sala-topo">
                            <div><h3>3MD</h3><small>SOP - 26 alunos</small></div>
                            <img src="imagem/grupo-verde.png" alt="">
                        </div>
                        <div class="nt-sala-media"><strong>6,5</strong><span>Média</span></div>
                        <span class="nt-badge">3 em recuperação</span>
                    </label>
                    <label class="nt-sala" for="sala-2md">
                        <div class="nt-sala-topo">
                            <div><h3>2MD</h3><small>PMEC - 20 alunos</small></div>
                            <img src="imagem/grupo-verde.png" alt="">
                        </div>
                        <div class="nt-sala-media"><strong>8,2</strong><span>Média</span></div>
                        <span class="nt-badge">2 em recuperação</span>
                    </label>
                    <label class="nt-sala" for="sala-2pm">
                        <div class="nt-sala-topo">
                            <div><h3>2PM</h3><small>BIC - 19 alunos</small></div>
                            <img src="imagem/grupo-verde.png" alt="">
                        </div>
                        <div class="nt-sala-media"><strong>6,5</strong><span>Média</span></div>
                        <span class="nt-badge">3 em recuperação</span>
                    </label>
                    <label class="nt-sala" for="sala-3pm">
                        <div class="nt-sala-topo">
                            <div><h3>3PM</h3><small>SIP - 12 alunos</small></div>
                            <img src="imagem/grupo-verde.png" alt="">
                        </div>
                        <div class="nt-sala-media"><strong>8,0</strong><span>Média</span></div>
                        <span class="nt-badge">5 em recuperação</span>
                    </label>
                </section>

                <div class="nt-topo">
                    <h2 class="nt-titulo"><span class="nt-selo" aria-hidden="true">A+</span>Notas dos alunos</h2>
                    <label class="nt-busca" for="nt-busca-aluno">
                        <svg class="nt-busca-icone" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <circle cx="10.5" cy="10.5" r="6.25" fill="none" stroke="currentColor" stroke-width="2"></circle>
                            <path d="M15.2 15.2L20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                        </svg>
                        <input type="search" id="nt-busca-aluno" name="busca" placeholder="Pesquisar aluno..." autocomplete="off" spellcheck="false">
                    </label>
                    <input type="checkbox" id="filtro-recup" class="nt-check-recup">
                    <label for="filtro-recup" class="nt-btn-recup">
                        Em recuperação
                        <span class="nt-conta" aria-hidden="true"></span>
                    </label>
                </div>
                <p class="nt-contagem" aria-live="polite"></p>

                <div class="nt-listas">
            <section class="nt-lista" id="lista-3md" aria-label="Alunos da 3MD">
            <p class="nt-vazio">Nenhum aluno encontrado.</p>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Juliana dos Santos</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>8,3</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">9,5</span><span class="nt-celula baixo">2,0</span><span class="nt-celula ok">9,5</span><span class="nt-celula ok">9,5</span><span class="nt-celula baixo">5,4</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">9,5</span><span class="nt-celula ok">9,5</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Arthur Felipe</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,2</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">9,5</span><span class="nt-celula baixo">3,5</span><span class="nt-celula ok">9,5</span><span class="nt-celula ok">9,5</span><span class="nt-celula baixo">5,5</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">9,5</span><span class="nt-celula ok">9,5</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Algum aleatório</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>4,5</strong><small>Média</small></span>
                        <span class="nt-pill recuperacao">recuperação</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">9,5</span><span class="nt-celula baixo">1,8</span><span class="nt-celula baixo">2,0</span><span class="nt-celula baixo">4,0</span><span class="nt-celula baixo">6,0</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">9,5</span><span class="nt-celula ok">9,5</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Aline Martins</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">8,6</span><span class="nt-celula ok">9,2</span><span class="nt-celula ok">8,8</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">3,3</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">8,8</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>André dos Barbosa</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">7,6</span><span class="nt-celula baixo">5,7</span><span class="nt-celula ok">10,0</span><span class="nt-celula ok">9,5</span><span class="nt-celula baixo">5,3</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">5,7</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">7,8</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Bruno dos Oliveira</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>1,6</strong><small>Média</small></span>
                        <span class="nt-pill recuperacao">recuperação</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">1,7</span><span class="nt-celula baixo">6,8</span><span class="nt-celula baixo">4,0</span><span class="nt-celula baixo">0,5</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">0,0</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Carolina de Carvalho</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,1</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">7,4</span><span class="nt-celula baixo">3,4</span><span class="nt-celula baixo">5,4</span><span class="nt-celula ok">8,7</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">5,6</span><span class="nt-celula baixo">6,1</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">9,3</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Davi do Azevedo</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">4,7</span><span class="nt-celula ok">7,8</span><span class="nt-celula ok">8,4</span><span class="nt-celula ok">8,8</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">7,8</span><span class="nt-celula baixo">5,5</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Elisa de Cunha</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">5,8</span><span class="nt-celula baixo">2,0</span><span class="nt-celula baixo">5,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">7,1</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">9,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Elisa dos Azevedo</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">8,1</span><span class="nt-celula ok">10,0</span><span class="nt-celula baixo">6,9</span><span class="nt-celula baixo">6,8</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">4,8</span><span class="nt-celula ok">7,3</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Felipe do Cardoso</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">8,8</span><span class="nt-celula ok">8,8</span><span class="nt-celula ok">8,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">3,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">8,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Fábio Pereira</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">6,8</span><span class="nt-celula baixo">6,6</span><span class="nt-celula baixo">6,9</span><span class="nt-celula ok">7,6</span><span class="nt-celula ok">7,1</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">6,7</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">7,3</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Gabriel de Souza</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">6,2</span><span class="nt-celula baixo">4,5</span><span class="nt-celula baixo">2,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">9,2</span><span class="nt-celula baixo">6,9</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">8,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Gustavo do Almeida</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">5,6</span><span class="nt-celula baixo">2,3</span><span class="nt-celula ok">7,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">6,8</span><span class="nt-celula baixo">6,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">9,3</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Henrique dos Costa</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">10,0</span><span class="nt-celula baixo">5,1</span><span class="nt-celula baixo">6,9</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">6,4</span><span class="nt-celula baixo">6,9</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Igor Mendes</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>1,3</strong><small>Média</small></span>
                        <span class="nt-pill recuperacao">recuperação</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">6,5</span><span class="nt-celula baixo">0,0</span><span class="nt-celula baixo">1,6</span><span class="nt-celula baixo">0,8</span><span class="nt-celula baixo">0,9</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">0,8</span><span class="nt-celula baixo">0,3</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>João de Monteiro</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">8,6</span><span class="nt-celula ok">7,1</span><span class="nt-celula ok">7,5</span><span class="nt-celula ok">8,6</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">8,0</span><span class="nt-celula baixo">4,1</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>João de Nunes</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">8,8</span><span class="nt-celula baixo">6,9</span><span class="nt-celula ok">9,0</span><span class="nt-celula baixo">6,9</span><span class="nt-celula ok">8,8</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">6,4</span><span class="nt-celula baixo">5,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Júlia do Ramos</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">9,3</span><span class="nt-celula baixo">6,9</span><span class="nt-celula baixo">6,0</span><span class="nt-celula ok">9,0</span><span class="nt-celula ok">8,3</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">4,1</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">9,1</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Marina dos Barros</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">7,3</span><span class="nt-celula ok">10,0</span><span class="nt-celula ok">7,7</span><span class="nt-celula baixo">5,3</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">3,9</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">9,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Rodrigo dos Cardoso</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">5,7</span><span class="nt-celula baixo">6,9</span><span class="nt-celula ok">9,2</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">6,8</span><span class="nt-celula baixo">6,5</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Samuel Moreira</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">8,9</span><span class="nt-celula baixo">5,4</span><span class="nt-celula ok">10,0</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">3,0</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">9,8</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Talita do Azevedo</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">6,7</span><span class="nt-celula baixo">4,2</span><span class="nt-celula ok">9,4</span><span class="nt-celula ok">9,8</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">6,7</span><span class="nt-celula baixo">6,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Thiago do Mendes</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">5,7</span><span class="nt-celula ok">9,2</span><span class="nt-celula baixo">5,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">7,1</span><span class="nt-celula ok">7,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Wesley dos Machado</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">5,5</span><span class="nt-celula ok">9,7</span><span class="nt-celula baixo">5,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">6,4</span><span class="nt-celula ok">7,8</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Yasmin da Pinto</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">5,9</span><span class="nt-celula ok">9,2</span><span class="nt-celula ok">8,6</span><span class="nt-celula ok">7,0</span><span class="nt-celula baixo">6,1</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">7,0</span><span class="nt-celula baixo">6,1</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            </section>
            <section class="nt-lista" id="lista-2md" aria-label="Alunos da 2MD">
            <p class="nt-vazio">Nenhum aluno encontrado.</p>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Alice do Batista</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>8,7</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">9,6</span><span class="nt-celula baixo">6,9</span><span class="nt-celula baixo">5,6</span><span class="nt-celula ok">8,9</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">9,3</span><span class="nt-celula ok">9,1</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">9,0</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Ana Araújo</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,8</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">6,5</span><span class="nt-celula ok">7,2</span><span class="nt-celula baixo">6,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">9,0</span><span class="nt-celula ok">8,5</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Bianca da Lima</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>8,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">9,5</span><span class="nt-celula ok">8,1</span><span class="nt-celula ok">9,3</span><span class="nt-celula ok">8,5</span><span class="nt-celula baixo">6,4</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">7,7</span><span class="nt-celula ok">7,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Clara de Barbosa</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>8,1</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">5,0</span><span class="nt-celula ok">8,6</span><span class="nt-celula ok">8,6</span><span class="nt-celula ok">10,0</span><span class="nt-celula baixo">4,9</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">8,8</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Daniela Santos</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>9,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">9,3</span><span class="nt-celula ok">9,8</span><span class="nt-celula ok">9,3</span><span class="nt-celula baixo">5,6</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">9,5</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Daniela do Dias</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>8,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">9,3</span><span class="nt-celula baixo">6,4</span><span class="nt-celula ok">8,6</span><span class="nt-celula ok">9,1</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">8,3</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">7,2</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Davi dos Moreira</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>9,1</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">7,5</span><span class="nt-celula ok">9,6</span><span class="nt-celula ok">8,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">9,5</span><span class="nt-celula ok">9,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Diego do Souza</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>6,4</strong><small>Média</small></span>
                        <span class="nt-pill recuperacao">recuperação</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">1,9</span><span class="nt-celula baixo">3,9</span><span class="nt-celula baixo">6,6</span><span class="nt-celula ok">7,2</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">7,0</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">7,2</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Felipe da Ramos</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>9,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">8,1</span><span class="nt-celula ok">7,7</span><span class="nt-celula ok">8,9</span><span class="nt-celula ok">9,8</span><span class="nt-celula ok">8,6</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">9,3</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Giovana do Machado</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,9</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">5,7</span><span class="nt-celula baixo">4,7</span><span class="nt-celula ok">8,5</span><span class="nt-celula ok">7,8</span><span class="nt-celula ok">8,8</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">8,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Helena da Costa</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,7</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">9,1</span><span class="nt-celula baixo">6,4</span><span class="nt-celula ok">8,5</span><span class="nt-celula baixo">5,6</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">7,7</span><span class="nt-celula ok">8,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Igor da Correia</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>8,2</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">9,4</span><span class="nt-celula baixo">6,6</span><span class="nt-celula ok">10,0</span><span class="nt-celula ok">9,2</span><span class="nt-celula ok">9,3</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">7,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Laura da Campos</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>8,7</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">9,6</span><span class="nt-celula ok">8,8</span><span class="nt-celula ok">9,2</span><span class="nt-celula ok">8,9</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">8,0</span><span class="nt-celula ok">9,1</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">8,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Leonardo Mendes</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>8,4</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">9,3</span><span class="nt-celula baixo">5,6</span><span class="nt-celula ok">8,9</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">8,1</span><span class="nt-celula ok">9,5</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Letícia do Pereira</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>8,1</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">8,6</span><span class="nt-celula ok">9,0</span><span class="nt-celula baixo">5,9</span><span class="nt-celula ok">10,0</span><span class="nt-celula baixo">4,8</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">7,5</span><span class="nt-celula ok">9,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Matheus do Barros</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>8,8</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">7,4</span><span class="nt-celula ok">9,6</span><span class="nt-celula ok">7,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">8,6</span><span class="nt-celula ok">10,0</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Otávio dos Nascimento</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,6</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">9,2</span><span class="nt-celula baixo">5,2</span><span class="nt-celula baixo">5,9</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">9,5</span><span class="nt-celula ok">7,3</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Rafael da Cunha</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>9,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">8,9</span><span class="nt-celula ok">9,1</span><span class="nt-celula ok">10,0</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">8,7</span><span class="nt-celula ok">8,5</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Sofia Souza</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>6,7</strong><small>Média</small></span>
                        <span class="nt-pill recuperacao">recuperação</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">3,7</span><span class="nt-celula baixo">5,7</span><span class="nt-celula baixo">2,9</span><span class="nt-celula baixo">5,6</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">8,9</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Valentina do Teixeira</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>8,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">8,5</span><span class="nt-celula ok">9,7</span><span class="nt-celula baixo">4,7</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">9,9</span><span class="nt-celula ok">8,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">7,0</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            </section>
            <section class="nt-lista" id="lista-2pm" aria-label="Alunos da 2PM">
            <p class="nt-vazio">Nenhum aluno encontrado.</p>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Ana Nunes</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">6,8</span><span class="nt-celula baixo">6,5</span><span class="nt-celula baixo">6,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">8,2</span><span class="nt-celula baixo">6,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Camila Nunes</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,6</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">8,2</span><span class="nt-celula ok">8,0</span><span class="nt-celula baixo">4,4</span><span class="nt-celula ok">8,1</span><span class="nt-celula ok">8,6</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">5,9</span><span class="nt-celula baixo">6,8</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">9,1</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Carolina dos Alves</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>3,4</strong><small>Média</small></span>
                        <span class="nt-pill recuperacao">recuperação</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">5,1</span><span class="nt-celula ok">7,1</span><span class="nt-celula baixo">1,3</span><span class="nt-celula baixo">0,0</span><span class="nt-celula baixo">6,5</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">2,3</span><span class="nt-celula baixo">3,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Davi do Martins</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">8,3</span><span class="nt-celula ok">8,5</span><span class="nt-celula baixo">5,8</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">6,4</span><span class="nt-celula baixo">6,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Fernanda da Nascimento</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">7,7</span><span class="nt-celula ok">9,2</span><span class="nt-celula ok">7,2</span><span class="nt-celula ok">10,0</span><span class="nt-celula baixo">5,6</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">7,1</span><span class="nt-celula baixo">4,9</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Gustavo dos Pereira</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">6,9</span><span class="nt-celula baixo">2,1</span><span class="nt-celula ok">8,2</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">6,0</span><span class="nt-celula ok">7,8</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">8,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Helena dos Costa</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>3,9</strong><small>Média</small></span>
                        <span class="nt-pill recuperacao">recuperação</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">7,7</span><span class="nt-celula baixo">4,4</span><span class="nt-celula baixo">4,8</span><span class="nt-celula baixo">3,8</span><span class="nt-celula baixo">3,1</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">1,6</span><span class="nt-celula baixo">4,6</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Henrique Lima</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">9,0</span><span class="nt-celula baixo">4,1</span><span class="nt-celula ok">10,0</span><span class="nt-celula ok">7,6</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">6,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Henrique dos Dias</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">6,2</span><span class="nt-celula ok">9,4</span><span class="nt-celula ok">9,8</span><span class="nt-celula ok">7,0</span><span class="nt-celula ok">8,9</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">5,2</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">7,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Isabela dos Monteiro</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>8,2</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">7,9</span><span class="nt-celula ok">8,8</span><span class="nt-celula ok">7,4</span><span class="nt-celula ok">7,7</span><span class="nt-celula ok">7,6</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">7,2</span><span class="nt-celula ok">7,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">9,5</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Laura da Pinto</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">6,5</span><span class="nt-celula baixo">4,8</span><span class="nt-celula baixo">4,5</span><span class="nt-celula ok">9,2</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">8,0</span><span class="nt-celula baixo">6,5</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">7,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Marina de Monteiro</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">7,8</span><span class="nt-celula ok">9,5</span><span class="nt-celula baixo">6,6</span><span class="nt-celula ok">7,1</span><span class="nt-celula ok">9,2</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">4,1</span><span class="nt-celula ok">7,8</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Melissa Araújo</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">4,7</span><span class="nt-celula ok">7,8</span><span class="nt-celula ok">7,4</span><span class="nt-celula ok">9,4</span><span class="nt-celula baixo">6,4</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">6,9</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Nicolas do Araújo</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">7,9</span><span class="nt-celula ok">9,6</span><span class="nt-celula ok">9,2</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">5,0</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Paulo dos Nunes</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">6,5</span><span class="nt-celula baixo">6,4</span><span class="nt-celula baixo">6,7</span><span class="nt-celula ok">7,4</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">5,9</span><span class="nt-celula ok">8,5</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">7,1</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Paulo dos Ramos</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">7,9</span><span class="nt-celula ok">8,8</span><span class="nt-celula ok">9,4</span><span class="nt-celula baixo">6,3</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">3,7</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">9,3</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Renan do Ribeiro</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">8,6</span><span class="nt-celula ok">8,4</span><span class="nt-celula ok">7,2</span><span class="nt-celula baixo">5,1</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">5,5</span><span class="nt-celula ok">7,8</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Sofia dos Costa</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">5,9</span><span class="nt-celula ok">8,4</span><span class="nt-celula ok">10,0</span><span class="nt-celula baixo">6,0</span><span class="nt-celula baixo">5,8</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">6,7</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Vitor Pereira</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>3,1</strong><small>Média</small></span>
                        <span class="nt-pill recuperacao">recuperação</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">2,6</span><span class="nt-celula baixo">5,3</span><span class="nt-celula baixo">6,9</span><span class="nt-celula ok">7,7</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">0,0</span><span class="nt-celula baixo">1,3</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            </section>
            <section class="nt-lista" id="lista-3pm" aria-label="Alunos da 3PM">
            <p class="nt-vazio">Nenhum aluno encontrado.</p>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Alice da Correia</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>6,8</strong><small>Média</small></span>
                        <span class="nt-pill recuperacao">recuperação</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">2,8</span><span class="nt-celula baixo">4,4</span><span class="nt-celula baixo">6,6</span><span class="nt-celula baixo">2,0</span><span class="nt-celula ok">7,0</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">5,6</span><span class="nt-celula ok">9,3</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">8,4</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Bianca dos Mendes</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>7,9</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">7,0</span><span class="nt-celula ok">7,1</span><span class="nt-celula baixo">5,5</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">9,2</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Bruno Almeida</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>8,7</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">6,2</span><span class="nt-celula baixo">6,7</span><span class="nt-celula ok">8,3</span><span class="nt-celula ok">9,6</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">9,2</span><span class="nt-celula ok">10,0</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Daniela do Mendes</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>8,9</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">8,3</span><span class="nt-celula baixo">6,9</span><span class="nt-celula ok">9,8</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">8,7</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">9,7</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Enzo Moreira</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>9,2</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">7,1</span><span class="nt-celula ok">10,0</span><span class="nt-celula ok">8,8</span><span class="nt-celula ok">7,8</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">9,9</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Felipe do Dias</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>6,5</strong><small>Média</small></span>
                        <span class="nt-pill recuperacao">recuperação</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">2,0</span><span class="nt-celula baixo">2,2</span><span class="nt-celula baixo">6,6</span><span class="nt-celula baixo">5,2</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">7,1</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">8,3</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Felipe dos Pinto</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>6,7</strong><small>Média</small></span>
                        <span class="nt-pill recuperacao">recuperação</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">5,0</span><span class="nt-celula baixo">5,9</span><span class="nt-celula baixo">4,8</span><span class="nt-celula ok">7,1</span><span class="nt-celula baixo">5,2</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">7,7</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Gabriel do Cardoso</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>8,3</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">8,9</span><span class="nt-celula ok">8,0</span><span class="nt-celula ok">9,7</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">8,2</span><span class="nt-celula ok">8,2</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula ok">7,7</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Gustavo da Martins</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>6,9</strong><small>Média</small></span>
                        <span class="nt-pill recuperacao">recuperação</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">7,8</span><span class="nt-celula baixo">3,5</span><span class="nt-celula ok">7,5</span><span class="nt-celula ok">8,8</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula baixo">6,8</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Júlia de Monteiro</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>9,2</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">6,8</span><span class="nt-celula ok">10,0</span><span class="nt-celula ok">10,0</span><span class="nt-celula ok">8,7</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">9,3</span><span class="nt-celula ok">9,9</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Júlia dos Siqueira</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>6,5</strong><small>Média</small></span>
                        <span class="nt-pill recuperacao">recuperação</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula baixo">2,6</span><span class="nt-celula baixo">3,7</span><span class="nt-celula baixo">6,4</span><span class="nt-celula ok">8,4</span><span class="nt-celula baixo">4,8</span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">7,9</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            <article class="nt-aluno">
                <header class="nt-aluno-cab">
                    <h3>Vinícius dos Pinto</h3>
                    <div class="nt-aluno-resumo">
                        <span class="nt-aluno-media"><strong>10,0</strong><small>Média</small></span>
                        <span class="nt-pill aprovado">Aprovado</span>
                        <button type="button" class="nt-pill nt-mais">Nota +</button>
                    </div>
                </header>
                <div class="nt-grade">
                    <div class="nt-linha"><span class="nt-rotulo">Atividades</span><div class="nt-celulas"><span class="nt-celula ok">10,0</span><span class="nt-celula ok">10,0</span><span class="nt-celula ok">9,6</span><span class="nt-celula ok">10,0</span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Provas</span><div class="nt-celulas"><span class="nt-celula ok">10,0</span><span class="nt-celula ok">10,0</span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                    <div class="nt-linha"><span class="nt-rotulo">Trabalho</span><div class="nt-celulas"><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span><span class="nt-celula vazia"></span></div></div>
                </div>
                <footer class="nt-legenda">
                    <span class="nt-celula ok">7,0</span><small>Igual ou maior</small>
                    <span class="nt-celula baixo">7,0</span><small>Abaixo</small>
                    <span class="nt-celula vazia"></span><small>Sem atividade cadastrada</small>
                </footer>
            </article>
            </section>
                </div>
            </main>
        </div>
    </div>
    <script>
        document.getElementById("nt-busca-aluno").addEventListener("input", function () {
            var q = this.value.trim().toLowerCase();
            document.querySelectorAll(".nt-aluno").forEach(function (aluno) {
                var nome = aluno.querySelector("h3").textContent.toLowerCase();
                aluno.classList.toggle("nt-oculto", q !== "" && nome.indexOf(q) < 0);
            });
        });
    </script>
</body>

</html>
