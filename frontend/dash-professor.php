<?php
require_once __DIR__ . '/../backend/config/auth.php';
exigirPerfil(['professor']);
$usuario=usuarioAtual();$pdo=getConnection();$idProfessor=(int)$usuario['id_usuario'];$nomeUsuario=htmlspecialchars($usuario['nome'],ENT_QUOTES,'UTF-8');$esc=static fn($v)=>htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');$calendarYear=(int)date('Y');$calendarMonth=(int)date('n');
$stmt=$pdo->prepare('SELECT COUNT(DISTINCT id_turma) FROM professor_turmas WHERE id_professor=:professor');$stmt->execute([':professor'=>$idProfessor]);$totalTurmas=(int)$stmt->fetchColumn();
$diaSemana=(int)date('w');$stmt=$pdo->prepare('SELECT COUNT(*) FROM professor_horarios WHERE id_professor=:professor AND dia_semana=:dia');$stmt->execute([':professor'=>$idProfessor,':dia'=>$diaSemana]);$aulasHoje=(int)$stmt->fetchColumn();
$stmt=$pdo->prepare('SELECT COUNT(*) FROM atividades WHERE id_professor=:professor AND data_entrega>=CURDATE()');$stmt->execute([':professor'=>$idProfessor]);$atividadesPendentes=(int)$stmt->fetchColumn();
$stmt=$pdo->prepare('SELECT titulo,descricao,data_lembrete FROM lembretes_professor WHERE id_professor=:professor AND data_lembrete>=NOW() ORDER BY data_lembrete LIMIT 3');$stmt->execute([':professor'=>$idProfessor]);$lembretes=$stmt->fetchAll();
$stmt=$pdo->prepare("SELECT h.hora_inicio,h.hora_fim,t.nome AS turma,COALESCE(NULLIF(h.disciplina_nome,''),d.nome) AS disciplina FROM professor_horarios h JOIN turmas t ON t.id_turma=h.id_turma LEFT JOIN disciplinas d ON d.id_disciplina=h.id_disciplina WHERE h.id_professor=:professor ORDER BY MOD(h.dia_semana-:dia+7,7),h.hora_inicio LIMIT 2");$stmt->execute([':professor'=>$idProfessor,':dia'=>$diaSemana]);$proximasAulas=$stmt->fetchAll();
$stmt=$pdo->prepare("SELECT recente.titulo,recente.tipo,recente.data_modificacao FROM (SELECT e.titulo,'Evento do calendário' AS tipo,e.criado_em AS data_modificacao FROM eventos e WHERE e.id_instituicao=:instituicao_eventos AND e.publico_alvo IN ('todos','professor') UNION ALL SELECT l.titulo,'Lembrete pessoal' AS tipo,l.criado_em AS data_modificacao FROM lembretes_professor l WHERE l.id_professor=:professor_lembretes UNION ALL SELECT CONCAT('Horário: ',t.nome,' — ',COALESCE(NULLIF(h.disciplina_nome,''),d.nome,'Disciplina não informada')) AS titulo,'Horário de aula' AS tipo,h.criado_em AS data_modificacao FROM professor_horarios h JOIN turmas t ON t.id_turma=h.id_turma LEFT JOIN disciplinas d ON d.id_disciplina=h.id_disciplina WHERE h.id_professor=:professor_horarios) AS recente ORDER BY recente.data_modificacao DESC LIMIT 5");$stmt->execute([':instituicao_eventos'=>(int)$usuario['id_instituicao'],':professor_lembretes'=>$idProfessor,':professor_horarios'=>$idProfessor]);$modificacoesRecentes=$stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Professor | Vanguard</title>
    <link rel="stylesheet" href="css/dash-professor.css">
    <link rel="stylesheet" href="css/professor-calendar.css">
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
                    <img src="imagem/notas-branco.png" alt="" aria-hidden="true">
                    <span>Notas</span>
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
                <h1 class="dash-titulo">Dashboard do <span>Professor</span></h1>

                <div class="dash-grid">
                    <div class="dash-principal">
                        <section class="stats" aria-label="Resumo">
                            <article class="stat-card">
                                <div class="stat-icone"><img src="imagem/grupo-verde.png" alt=""></div>
                                <div class="stat-info">
                                    <h3>Turma</h3>
                                    <strong><?= $totalTurmas ?></strong>
                                    <small>Turmas ativas</small>
                                </div>
                            </article>
                            <article class="stat-card">
                                <div class="stat-icone"><img src="imagem/livro_01.png" alt=""></div>
                                <div class="stat-info">
                                    <h3>Aulas de hoje</h3>
                                    <strong><?= $aulasHoje ?></strong>
                                    <small>Turmas ativas</small>
                                </div>
                            </article>
                            <article class="stat-card">
                                <div class="stat-icone"><img src="imagem/pendentes.png" alt=""></div>
                                <div class="stat-info">
                                    <h3>Atividades pendentes</h3>
                                    <strong><?= $atividadesPendentes ?></strong>
                                    <small>Turmas ativas</small>
                                </div>
                            </article>
                        </section>

                                                <section class="card cronograma">
                            <div class="teacher-calendar" data-professor-calendar data-endpoint="calendario-professor.php" data-year="<?= $calendarYear ?>" data-month="<?= $calendarMonth ?>" aria-label="Calendário mensal do professor">
                                <div class="teacher-calendar-toolbar">
                                    <div class="teacher-calendar-controls"><button type="button" id="prof-calendar-prev" aria-label="Mês anterior">‹</button><h2 id="prof-calendar-month"></h2><button type="button" id="prof-calendar-next" aria-label="Próximo mês">›</button></div>
                                    <div class="teacher-calendar-actions"><button type="button" id="prof-calendar-today">Hoje</button><button type="button" id="prof-calendar-add-reminder" class="teacher-primary-button">＋ Novo lembrete</button><a href="calendario-professor.php" data-professor-calendar-link class="teacher-calendar-link">Abrir calendário</a></div>
                                </div>
                                <div class="teacher-calendar-grid" id="prof-calendar-grid" aria-live="polite" aria-busy="true"></div>
                                <section class="teacher-month-agenda" aria-labelledby="prof-calendar-agenda-title"><div class="teacher-agenda-heading"><div><span class="teacher-eyebrow">AGENDA DO MÊS</span><h2 id="prof-calendar-agenda-title">Eventos, feriados e meus lembretes</h2></div><span id="prof-calendar-count" class="teacher-agenda-count">— itens</span></div><div id="prof-calendar-events"><p class="teacher-calendar-empty">Carregando calendário…</p></div></section>
                            </div>
                        </section>

                        <section class="card lembretes">
                            <h2><img src="imagem/lembretes.png" alt="">Lembretes pessoais</h2>
                            <ul><?php if($lembretes): foreach($lembretes as $lembrete): ?><li><?= $esc($lembrete['titulo']) ?> — <?= date('d/m H:i',strtotime($lembrete['data_lembrete'])) ?></li><?php endforeach; else: ?><li>Nenhum lembrete futuro.</li><?php endif; ?></ul>
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
                                <?php if($modificacoesRecentes): foreach($modificacoesRecentes as $modificacao): ?>
                                    <li><time><?= date('d/m H:i',strtotime($modificacao['data_modificacao'])) ?></time><p><strong><?= $esc($modificacao['tipo']) ?>:</strong> <?= $esc($modificacao['titulo']) ?></p></li>
                                <?php endforeach; else: ?><li><p>Nenhuma modificação recente.</p></li><?php endif; ?>
                            </ul>
                        </section>

                        <section class="card lateral-card">
                            <h2 class="centro"><img src="imagem/proxima-aula.png" alt="">Próximas aulas</h2>
                            <ul class="timeline"><?php if($proximasAulas): foreach($proximasAulas as $aula): ?><li><time><?= substr($aula['hora_inicio'],0,5) ?></time><p><?= $esc($aula['turma']) ?><?= $aula['disciplina']?' - '.$esc($aula['disciplina']):'' ?> · <?= substr($aula['hora_inicio'],0,5) ?>–<?= substr($aula['hora_fim'],0,5) ?></p></li><?php endforeach; else: ?><li><p>Nenhum horário cadastrado.</p></li><?php endif; ?></ul>
                        </section>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <dialog id="prof-reminder-modal" class="teacher-dialog" aria-labelledby="prof-reminder-title"><form method="post" action="calendario-professor.php" class="teacher-dialog-form"><div class="teacher-dialog-heading"><div><span class="teacher-eyebrow">SÓ VOCÊ PODE VER</span><h2 id="prof-reminder-title" data-modal-title>Novo lembrete pessoal</h2></div><button type="button" class="teacher-close-button" data-close-professor-modal aria-label="Fechar">×</button></div><input type="hidden" name="acao" value="salvar_lembrete"><input type="hidden" name="id_lembrete"><input type="hidden" name="ano" value="<?= $calendarYear ?>"><input type="hidden" name="mes" value="<?= $calendarMonth ?>"><label>Título<input name="titulo" maxlength="120" required placeholder="Ex.: Preparar material da aula"></label><label>Descrição<textarea name="descricao" rows="3" placeholder="Detalhes para você"></textarea></label><label>Data e horário<input name="data_lembrete" type="datetime-local" required></label><div class="teacher-dialog-actions"><button type="button" class="teacher-secondary-button" data-close-professor-modal>Cancelar</button><button type="submit" class="teacher-primary-button">Salvar lembrete</button></div></form></dialog>
    <script src="js/calendario-professor.js" defer></script>
</body>

</html>
