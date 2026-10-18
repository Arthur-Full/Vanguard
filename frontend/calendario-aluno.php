<?php require_once __DIR__ . '/../backend/config/auth.php'; exigirPerfil(['aluno']); $nomeUsuario = htmlspecialchars(usuarioAtual()['nome'], ENT_QUOTES, 'UTF-8'); $feriados = feriadosDoAno((int)date('Y')); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário do aluno | Vanguard</title>
    <link rel="stylesheet" href="css/calendario-aluno.css">
    <link rel="stylesheet" href="css/feriados.css">
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
                <a href="calendario-aluno.php" class="nav-item active" aria-current="page">
                    <img src="imagem/calendario_01.png" alt="" aria-hidden="true">
                    <span>Calendário</span>
                </a>
                <a href="aulas-aluno.php" class="nav-item">
                    <img src="imagem/livro-branco.png" alt="" aria-hidden="true">
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
                <div class="caixa-top">Cronograma Anual</div>
                <div class="caixa-maior">
                    <div class="caixa-dentro"><img src="imagem/calendario-janeiro.png" alt="Janeiro"></div>
                    <div class="caixa-dentro"><img src="imagem/calendario-fevereiro.png" alt="Fevereiro"></div>
                    <div class="caixa-dentro"><img src="imagem/calendario-marco.png" alt="Março"></div>
                    <div class="caixa-dentro"><img src="imagem/calendario-abril.png" alt="Abril"></div>
                    <div class="caixa-dentro"><img src="imagem/calendario-maio.png" alt="Maio"></div>
                    <div class="caixa-dentro"><img src="imagem/calendario-junho.png" alt="Junho"></div>
                    <div class="caixa-dentro"><img src="imagem/calendario-julho.png" alt="Julho"></div>
                    <div class="caixa-dentro"><img src="imagem/calendario-agosto.png" alt="Agosto"></div>
                    <div class="caixa-dentro"><img src="imagem/calendario-setembro.png" alt="Setembro"></div>
                    <div class="caixa-dentro"><img src="imagem/calendario-outubro.png" alt="Outubro"></div>
                    <div class="caixa-dentro"><img src="imagem/calendario-novembro.png" alt="Novembro"></div>
                    <div class="caixa-dentro"><img src="imagem/calendario-dezembro.png" alt="Dezembro"></div>
                </div>
                <div class="caixa-baixo">
                    <div>
                        <h3>Feriados Nacionais</h3>
                        <p>Independência do Brasil - 7 de setembro</p>
                    </div>
                    <div>
                        <h3>Feriado municipal</h3>
                        <p>---</p>
                    </div>
                    <div>
                        <h3>Eventos escolares</h3>
                        <p>---</p>
                    </div>
                </div>
                <section class="feriados-calendario" aria-labelledby="titulo-feriados"><h2 id="titulo-feriados">Feriados nacionais e pontos facultativos de <?= (int)date('Y') ?></h2><ul><?php foreach ($feriados as $feriado): ?><li><time datetime="<?= htmlspecialchars($feriado['data'], ENT_QUOTES, 'UTF-8') ?>"><?= date('d/m', strtotime($feriado['data'])) ?></time><strong><?= htmlspecialchars($feriado['titulo'], ENT_QUOTES, 'UTF-8') ?></strong><span><?= htmlspecialchars($feriado['tipo'], ENT_QUOTES, 'UTF-8') ?><?= $feriado['observacao'] ? ' — ' . htmlspecialchars($feriado['observacao'], ENT_QUOTES, 'UTF-8') : '' ?></span></li><?php endforeach; ?></ul></section>
            </main>
        </div>
    </div>
</body>
</html>
