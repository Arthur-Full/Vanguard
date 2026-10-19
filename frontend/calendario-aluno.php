<?php
require_once __DIR__ . '/../backend/config/auth.php';
exigirPerfil(['aluno']);
$usuario=usuarioAtual();$nomeUsuario=htmlspecialchars($usuario['nome'],ENT_QUOTES,'UTF-8');
if(($_GET['api']??'')==='eventos'){
    header('Content-Type: application/json; charset=utf-8');
    try{
        $ano=filter_var($_GET['ano']??date('Y'),FILTER_VALIDATE_INT);
        if(!$ano||$ano<2000||$ano>2100)throw new InvalidArgumentException('Ano invalido.');
        $pdo=getConnection();$inicio=sprintf('%04d-01-01 00:00:00',$ano);$fim=sprintf('%04d-01-01 00:00:00',$ano+1);
        $stmt=$pdo->prepare("SELECT id_evento,titulo,descricao,data_inicio,data_fim,local,publico_alvo,FALSE AS is_feriado FROM eventos WHERE id_instituicao=:instituicao AND publico_alvo IN ('todos','aluno') AND data_inicio>=:inicio AND data_inicio<:fim ORDER BY data_inicio");
        $stmt->execute([':instituicao'=>(int)$usuario['id_instituicao'],':inicio'=>$inicio,':fim'=>$fim]);$eventos=$stmt->fetchAll();
        $stmt=$pdo->prepare("SELECT NULL AS id_evento,titulo,CONCAT(tipo,IF(observacao IS NULL,'',CONCAT(' - ',observacao))) AS descricao,CONCAT(data,' 00:00:00') AS data_inicio,NULL AS data_fim,NULL AS local,'todos' AS publico_alvo,TRUE AS is_feriado FROM feriados WHERE ano=:ano ORDER BY data");
        $stmt->execute([':ano'=>$ano]);
        echo json_encode(['sucesso'=>true,'dados'=>array_merge($eventos,$stmt->fetchAll())],JSON_UNESCAPED_UNICODE);
    }catch(InvalidArgumentException $e){http_response_code(400);echo json_encode(['sucesso'=>false,'erro'=>$e->getMessage()],JSON_UNESCAPED_UNICODE);}
    catch(PDOException $e){http_response_code(500);echo json_encode(['sucesso'=>false,'erro'=>'Nao foi possivel carregar o calendario.'],JSON_UNESCAPED_UNICODE);}
    exit;
}
$anoCalendario=filter_var($_GET['ano']??date('Y'),FILTER_VALIDATE_INT);if(!$anoCalendario||$anoCalendario<2000||$anoCalendario>2100)$anoCalendario=(int)date('Y');
$mesSelecionado=filter_var($_GET['mes']??null,FILTER_VALIDATE_INT);if(!$mesSelecionado||$mesSelecionado<1||$mesSelecionado>12)$mesSelecionado=null;
$mesesCalendario=['Janeiro','Fevereiro',html_entity_decode('Mar&ccedil;o',ENT_QUOTES,'UTF-8'),'Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário do aluno | Vanguard</title>
    <link rel="stylesheet" href="css/calendario-aluno.css?v=20261018-events">
    <link rel="stylesheet" href="css/feriados.css">
    <script src="js/calendario-aluno.js?v=20261018" defer></script>
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

            <main class="content-area" id="area-calendario-aluno" data-ano="<?= $anoCalendario ?>" data-mes="<?= $mesSelecionado??0 ?>">
                <?php if($mesSelecionado): $dataMes=new DateTimeImmutable(sprintf('%04d-%02d-01',$anoCalendario,$mesSelecionado));$mesAnterior=$dataMes->modify('-1 month');$mesProximo=$dataMes->modify('+1 month'); ?>
                    <div class="caixa-top month-detail-heading">Calend&aacute;rio de <?= htmlspecialchars($mesesCalendario[$mesSelecionado-1],ENT_QUOTES,'UTF-8') ?> de <?= $anoCalendario ?></div>
                    <div class="month-detail-toolbar"><a href="?ano=<?= $mesAnterior->format('Y') ?>&amp;mes=<?= $mesAnterior->format('n') ?>" aria-label="M&ecirc;s anterior">&lsaquo;</a><strong><?= htmlspecialchars($mesesCalendario[$mesSelecionado-1],ENT_QUOTES,'UTF-8') ?> <?= $anoCalendario ?></strong><a href="?ano=<?= $mesProximo->format('Y') ?>&amp;mes=<?= $mesProximo->format('n') ?>" aria-label="Pr&oacute;ximo m&ecirc;s">&rsaquo;</a></div>
                    <a class="calendar-back" href="?ano=<?= $anoCalendario ?>">&larr; Ver todos os meses</a>
                    <div class="month-detail-layout">
                        <section class="month-detail-calendar" aria-label="Calend&aacute;rio mensal"><table class="student-month-grid"><thead><tr><th>Dom</th><th>Seg</th><th>Ter</th><th>Qua</th><th>Qui</th><th>Sex</th><th>Sab</th></tr></thead><tbody id="student-month-days"></tbody></table></section>
                        <section class="month-events-panel"><h2>Eventos e compromissos</h2><ul id="eventos-do-mes" aria-live="polite"><li class="calendar-loading">Carregando eventos...</li></ul></section>
                    </div>
                <?php else: ?>
                    <div class="caixa-top">Cronograma Anual &middot; <?= $anoCalendario ?></div>
                    <div class="caixa-maior">
                        <?php $imagensMes=['janeiro','fevereiro','marco','abril','maio','junho','julho','agosto','setembro','outubro','novembro','dezembro']; foreach($mesesCalendario as $i=>$mesNome): $numeroMes=$i+1; ?>
                            <a class="caixa-dentro month-card" data-mes="<?= $numeroMes ?>" href="?ano=<?= $anoCalendario ?>&amp;mes=<?= $numeroMes ?>" aria-label="Abrir calend&aacute;rio de <?= htmlspecialchars($mesNome,ENT_QUOTES,'UTF-8') ?> de <?= $anoCalendario ?>"><img src="imagem/calendario-<?= $imagensMes[$i] ?>.png" alt="Calend&aacute;rio de <?= htmlspecialchars($mesNome,ENT_QUOTES,'UTF-8') ?>"><span class="month-event-count"></span></a>
                        <?php endforeach; ?>
                    </div>
                    <section class="annual-agenda"><h2>Pr&oacute;ximos eventos da escola</h2><ul id="eventos-proximos-anual" aria-live="polite"><li class="calendar-loading">Carregando eventos...</li></ul></section>
                <?php endif; ?>
                <p class="calendar-error" id="calendar-error" role="status" hidden></p>
            </main>
        </div>
    </div>
</body>
</html>
