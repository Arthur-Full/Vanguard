<?php
require_once __DIR__ . '/../backend/config/auth.php';
exigirPerfil(['admin', 'coordenador']);

$usuario = usuarioAtual();
$nome = htmlspecialchars($usuario['nome'] ?? 'Coordenação', ENT_QUOTES, 'UTF-8');
$instituicaoId = (int)($usuario['id_instituicao'] ?? 0);
$administrador = ($usuario['tipo_usuario'] ?? '') === 'admin';
$metricas = ['escolas' => 0, 'salas' => 0, 'professores' => 0, 'alunos' => 0];
$recentes = [];
$eventos = [];
$erroBanco = false;
$escape = static fn($valor): string => htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');

try {
    $pdo = getConnection();
    if ($administrador) {
        $metricas['escolas'] = (int)$pdo->query('SELECT COUNT(*) FROM instituicoes')->fetchColumn();
    } else {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM instituicoes WHERE id_instituicao = :id');
        $stmt->execute([':id' => $instituicaoId]);
        $metricas['escolas'] = (int)$stmt->fetchColumn();
    }

    $filtroTurmas = $administrador ? '' : ' WHERE id_instituicao = :instituicao';
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM turmas' . $filtroTurmas);
    $stmt->execute($administrador ? [] : [':instituicao' => $instituicaoId]);
    $metricas['salas'] = (int)$stmt->fetchColumn();

    foreach (['professores' => 'professor', 'alunos' => 'aluno'] as $chave => $tipo) {
        $sql = 'SELECT COUNT(*) FROM usuarios WHERE tipo_usuario = :tipo' . ($administrador ? '' : ' AND id_instituicao = :instituicao');
        $stmt = $pdo->prepare($sql);
        $params = [':tipo' => $tipo];
        if (!$administrador) $params[':instituicao'] = $instituicaoId;
        $stmt->execute($params);
        $metricas[$chave] = (int)$stmt->fetchColumn();
    }

    $sqlRecentes = "SELECT titulo, detalhe, ocorrido_em FROM (
        SELECT nome AS titulo, CONCAT('Cadastro de ', tipo_usuario) AS detalhe, data_criacao AS ocorrido_em
        FROM usuarios" . ($administrador ? '' : ' WHERE id_instituicao = :instituicao_usuarios') . "
        UNION ALL
        SELECT titulo, 'Evento publicado' AS detalhe, criado_em AS ocorrido_em
        FROM eventos" . ($administrador ? '' : ' WHERE id_instituicao = :instituicao_eventos') . "
    ) AS movimentacoes ORDER BY ocorrido_em DESC LIMIT 6";
    $paramsRecentes = $administrador ? [] : [':instituicao_usuarios' => $instituicaoId, ':instituicao_eventos' => $instituicaoId];
    $stmt = $pdo->prepare($sqlRecentes);
    $stmt->execute($paramsRecentes);
    $recentes = $stmt->fetchAll();

    $sqlEventos = 'SELECT titulo, data_inicio, publico_alvo FROM eventos WHERE data_inicio >= NOW()' . ($administrador ? '' : ' AND id_instituicao = :instituicao') . ' ORDER BY data_inicio LIMIT 5';
    $stmt = $pdo->prepare($sqlEventos);
    $stmt->execute($administrador ? [] : [':instituicao' => $instituicaoId]);
    $eventos = $stmt->fetchAll();
} catch (Throwable $exception) {
    $erroBanco = true;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visão geral | Vanguard</title>
    <link rel="stylesheet" href="./css/dash-adm.css">
    <link rel="stylesheet" href="./css/aside-adm.css">
    <style>
        .painel-boas-vindas {
            margin: 0 0 18px;
            color: #d9ffe5
        }

        .box {
            color: inherit;
            text-decoration: none
        }

        .box .d h1 {
            margin: 0;
            font-size: 2.2rem
        }

        .box .d h3 {
            margin: 12px 0 0
        }

        .lista-atividade {
            width: 90%;
            margin: 0;
            padding: 8px 0;
            list-style: none;
            color: #fff
        }

        .lista-atividade li {
            padding: 12px 0;
            border-bottom: 1px solid #58888f
        }

        .lista-atividade li strong,
        .lista-atividade li span {
            display: block;
            color: #fff
        }

        .lista-atividade li span {
            margin-top: 5px;
            font-size: .9rem
        }

        .painel-vazio,
        .painel-erro {
            padding: 20px;
            color: #fff
        }

        .informacao p {
            margin: 12px 0
        }

        .informacao a {
            color: #d9ffe5
        }

        @media(max-width:900px) {

            .espaco_infor,
            .space {
                height: auto;
                flex-wrap: wrap;
                padding: 20px;
                gap: 16px
            }

            .box {
                max-width: calc(50% - 16px);
                flex: 1 1 240px
            }

            .quadrado {
                width: 100%;
                height: auto;
                min-height: 260px
            }

            .texto {
                height: auto;
                padding: 28px 20px
            }

            .titulo {
                margin-left: 24px
            }
        }
    </style>
</head>

<body>
    <main>
        <aside class="sidebar">
            <a class="brand" href="dash-adm.php" aria-label="Vanguard, painel da coordenação"><img src="./imagem/SEGUNDA_LOGO.png" alt="Vanguard — Portal Educacional"></a>
            <nav class="side-nav" aria-label="Navegação principal">
                <a class="nav-item active" href="dash-adm.php" aria-current="page"><img src="./imagem/casa-branca.png" alt=""><span>Visão geral</span></a>
                <a class="nav-item" href="escolas-adm.php"><img src="./imagem/escolas-icon.png" alt=""><span>Escolas</span></a>
                <a class="nav-item" href="salas-adm.php"><img src="./imagem/classroom.png" alt=""><span>Salas</span></a>
                <a class="nav-item" href="professores-adm.php"><img src="./imagem/professor.png" alt=""><span>Professores</span></a>
                <a class="nav-item" href="calendario-adm.php"><img src="./imagem/calendario-verde.png" alt=""><span>Calendário</span></a>
                <a class="nav-item" href="alunos-adm.php"><img src="./imagem/reading.png" alt=""><span>Alunos</span></a>
                <a class="nav-item" href="sair.php"><img src="./imagem/exit.png" alt=""><span>Sair</span></a>
            </nav>
        </aside>
    </main>

    <div class="espaco_dash">
        <header class="topbar"><a class="topbar-logout" href="sair.php" aria-label="Sair e voltar ao login" title="Sair"><img src="./imagem/exit.png" alt=""></a></header>
        <div class="texto">
            <div>
                <h1>Painel da <span>Coordenação</span></h1>
                <p class="painel-boas-vindas">Olá, <?= $nome ?>. <?= $administrador ? 'Visão geral de todas as instituições.' : 'Resumo da sua instituição.' ?></p>
            </div>
        </div>

        <?php if ($erroBanco): ?><p class="painel-erro" role="alert">Não foi possível carregar os dados. Verifique a conexão e se o banco está atualizado.</p><?php endif; ?>

        <div class="espaco_infor">
            <?php foreach (
                [
                    ['escolas', 'Escolas', './imagem/school (2).png', 'escolas-adm.php'],
                    ['salas', 'Salas', './imagem/classroom.png', 'salas-adm.php'],
                    ['professores', 'Professores', './imagem/professor.png', 'professores-adm.php'],
                    ['alunos', 'Alunos', './imagem/reading.png', 'alunos-adm.php'],
                ] as [$chave, $rotulo, $icone, $url]
            ): ?>
                <a class="box" href="<?= $escape($url) ?>">
                    <div class="bolinha"><img class="school_icone" src="<?= $escape($icone) ?>" alt=""></div>
                    <div class="dados">
                        <div class="d">
                            <h1><span><?= $metricas[$chave] ?></span></h1>
                            <h3><?= $escape($rotulo) ?></h3>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="space">
            <section class="quadrado" aria-labelledby="titulo-recentes">
                <div class="titulo">
                    <h1 id="titulo-recentes">Movimentações recentes</h1>
                </div>
                <?php if (!$erroBanco && !$recentes): ?>
                    <div class="painel-vazio">Nenhuma movimentação registrada ainda.</div>
                <?php elseif (!$erroBanco): ?>
                    <div class="texto_">
                        <ul class="lista-atividade">
                            <?php foreach ($recentes as $item): ?>
                                <li><strong><?= $escape($item['titulo']) ?></strong><span><?= $escape($item['detalhe']) ?> · <?= $escape(date('d/m/Y H:i', strtotime($item['ocorrido_em']))) ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </section>

            <section class="quadrado" aria-labelledby="titulo-eventos">
                <div class="titulo">
                    <h1 id="titulo-eventos">Próximos eventos</h1>
                </div>
                <div class="informacao">
                    <?php if (!$erroBanco && !$eventos): ?>
                        <p>Não há próximos eventos cadastrados.</p>
                        <?php elseif (!$erroBanco): foreach ($eventos as $evento): ?>
                            <p><time datetime="<?= $escape(date('c', strtotime($evento['data_inicio']))) ?>"><?= $escape(date('d/m/Y H:i', strtotime($evento['data_inicio']))) ?></time> — <?= $escape($evento['titulo']) ?></p>
                    <?php endforeach;
                    endif; ?>
                    <a href="calendario-adm.php">Abrir calendário</a>
                </div>
            </section>
        </div>
    </div>
</body>

</html>