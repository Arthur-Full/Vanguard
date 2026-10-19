<?php
require_once __DIR__ . '/../backend/config/auth.php';
exigirPerfil(['professor']);

$usuario = usuarioAtual();
$pdo = getConnection();
$idProfessor = (int)$usuario['id_usuario'];
$esc = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
$ano = filter_var($_POST['ano'] ?? $_GET['ano'] ?? date('Y'), FILTER_VALIDATE_INT);
$ano = $ano && $ano >= 2000 && $ano <= 2100 ? $ano : (int)date('Y');
$mes = filter_var($_POST['mes'] ?? $_GET['mes'] ?? date('n'), FILTER_VALIDATE_INT);
$mes = $mes && $mes >= 1 && $mes <= 12 ? $mes : (int)date('n');
$dia = filter_var($_GET['dia'] ?? null, FILTER_VALIDATE_INT);
$erro = '';

if (($_GET['api'] ?? '') === 'calendario') {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $stmt = $pdo->prepare("SELECT id_evento,titulo,descricao,data_inicio,publico_alvo,'evento' AS categoria,NULL AS id_lembrete FROM eventos WHERE id_instituicao=:instituicao AND YEAR(data_inicio)=:ano AND publico_alvo IN ('todos','professor') ORDER BY data_inicio");
        $stmt->execute([':instituicao' => (int)$usuario['id_instituicao'], ':ano' => $ano]);
        $itens = $stmt->fetchAll();
        $stmt = $pdo->prepare("SELECT NULL AS id_evento,titulo,CONCAT(tipo,IF(observacao IS NULL,'',CONCAT(' — ',observacao))) AS descricao,CONCAT(data,' 00:00:00') AS data_inicio,'todos' AS publico_alvo,'feriado' AS categoria,NULL AS id_lembrete FROM feriados WHERE ano=:ano ORDER BY data");
        $stmt->execute([':ano' => $ano]);
        $itens = array_merge($itens, $stmt->fetchAll());
        $stmt = $pdo->prepare("SELECT NULL AS id_evento,titulo,descricao,data_lembrete AS data_inicio,'professor' AS publico_alvo,'lembrete' AS categoria,id_lembrete FROM lembretes_professor WHERE id_professor=:professor AND YEAR(data_lembrete)=:ano ORDER BY data_lembrete");
        $stmt->execute([':professor' => $idProfessor, ':ano' => $ano]);
        $itens = array_merge($itens, $stmt->fetchAll());
        echo json_encode(['sucesso' => true, 'dados' => $itens], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    } catch (PDOException $exception) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Não foi possível carregar o calendário.'], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

$salasStmt = $pdo->prepare("SELECT id_turma,nome AS turma FROM turmas WHERE id_instituicao=:instituicao AND status='ativa' ORDER BY nome");
$salasStmt->execute([':instituicao' => (int)$usuario['id_instituicao']]);
$salas = $salasStmt->fetchAll();
$dias = [0 => 'Domingo', 1 => 'Segunda', 2 => 'Terça', 3 => 'Quarta', 4 => 'Quinta', 5 => 'Sexta', 6 => 'Sábado'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    try {
        if ($acao === 'salvar_lembrete') {
            $id = filter_var($_POST['id_lembrete'] ?? null, FILTER_VALIDATE_INT);
            $titulo = trim($_POST['titulo'] ?? '');
            $descricao = trim($_POST['descricao'] ?? '');
            $data = trim($_POST['data_lembrete'] ?? '');
            $dataValida = DateTime::createFromFormat('Y-m-d\\TH:i', $data);
            if ($titulo === '' || mb_strlen($titulo) > 120 || !$dataValida) throw new InvalidArgumentException('Informe um título e uma data válidos para o lembrete.');
            $dataSql = str_replace('T', ' ', $data) . ':00';
            if ($id) {
                $stmt = $pdo->prepare('UPDATE lembretes_professor SET titulo=:titulo,descricao=:descricao,data_lembrete=:data WHERE id_lembrete=:id AND id_professor=:professor');
                $stmt->execute([':titulo' => $titulo, ':descricao' => $descricao ?: null, ':data' => $dataSql, ':id' => $id, ':professor' => $idProfessor]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO lembretes_professor (id_professor,titulo,descricao,data_lembrete) VALUES (:professor,:titulo,:descricao,:data)');
                $stmt->execute([':professor' => $idProfessor, ':titulo' => $titulo, ':descricao' => $descricao ?: null, ':data' => $dataSql]);
            }
            header('Location: calendario-professor.php?ano=' . $ano . '&mes=' . $mes . '&salvo=lembrete');
            exit;
        }
        if ($acao === 'excluir_lembrete') {
            $id = filter_var($_POST['id_lembrete'] ?? null, FILTER_VALIDATE_INT);
            if (!$id) throw new InvalidArgumentException('Lembrete inválido.');
            $stmt = $pdo->prepare('DELETE FROM lembretes_professor WHERE id_lembrete=:id AND id_professor=:professor');
            $stmt->execute([':id' => $id, ':professor' => $idProfessor]);
            header('Location: calendario-professor.php?ano=' . $ano . '&mes=' . $mes);
            exit;
        }
        if ($acao === 'salvar_horario') {
            $id = filter_var($_POST['id_horario'] ?? null, FILTER_VALIDATE_INT);
            $idTurma = filter_var($_POST['id_turma'] ?? null, FILTER_VALIDATE_INT);
            $disciplinaNome = trim($_POST['disciplina_nome'] ?? '');
            $diaSemana = filter_var($_POST['dia_semana'] ?? null, FILTER_VALIDATE_INT);
            $inicio = $_POST['hora_inicio'] ?? '';
            $fim = $_POST['hora_fim'] ?? '';
            if (!$idTurma || $disciplinaNome === '' || mb_strlen($disciplinaNome) > 80 || $diaSemana === false || $diaSemana < 0 || $diaSemana > 6 || !preg_match('/^\d{2}:\d{2}$/', $inicio) || !preg_match('/^\d{2}:\d{2}$/', $fim) || $fim <= $inicio) throw new InvalidArgumentException('Confira sala, disciplina, dia e horários.');
            $stmt = $pdo->prepare("SELECT 1 FROM turmas WHERE id_turma=:turma AND id_instituicao=:instituicao AND status='ativa'");
            $stmt->execute([':turma' => $idTurma, ':instituicao' => (int)$usuario['id_instituicao']]);
            if (!$stmt->fetchColumn()) throw new InvalidArgumentException('Selecione uma sala ativa da sua instituição.');
            if ($id) {
                $stmt = $pdo->prepare('UPDATE professor_horarios SET id_turma=:turma,id_disciplina=NULL,disciplina_nome=:disciplina,dia_semana=:dia,hora_inicio=:inicio,hora_fim=:fim WHERE id_horario=:id AND id_professor=:professor');
                $stmt->execute([':turma' => $idTurma, ':disciplina' => $disciplinaNome, ':dia' => $diaSemana, ':inicio' => $inicio, ':fim' => $fim, ':id' => $id, ':professor' => $idProfessor]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO professor_horarios (id_professor,id_turma,id_disciplina,disciplina_nome,dia_semana,hora_inicio,hora_fim) VALUES (:professor,:turma,NULL,:disciplina,:dia,:inicio,:fim)');
                $stmt->execute([':professor' => $idProfessor, ':turma' => $idTurma, ':disciplina' => $disciplinaNome, ':dia' => $diaSemana, ':inicio' => $inicio, ':fim' => $fim]);
            }
            header('Location: calendario-professor.php?ano=' . $ano . '&mes=' . $mes . '&salvo=horario');
            exit;
        }
        if ($acao === 'excluir_horario') {
            $id = filter_var($_POST['id_horario'] ?? null, FILTER_VALIDATE_INT);
            if (!$id) throw new InvalidArgumentException('Horário inválido.');
            $stmt = $pdo->prepare('DELETE FROM professor_horarios WHERE id_horario=:id AND id_professor=:professor');
            $stmt->execute([':id' => $id, ':professor' => $idProfessor]);
            header('Location: calendario-professor.php?ano=' . $ano . '&mes=' . $mes);
            exit;
        }
    } catch (InvalidArgumentException $exception) {
        $erro = $exception->getMessage();
    } catch (PDOException $exception) {
        $erro = 'Não foi possível salvar. Confira se as migrações de horários e lembretes foram aplicadas.';
    }
}

$stmt = $pdo->prepare("SELECT h.id_horario,h.dia_semana,h.hora_inicio,h.hora_fim,h.id_turma,h.id_disciplina,t.nome AS turma,COALESCE(NULLIF(h.disciplina_nome,''),d.nome) AS disciplina FROM professor_horarios h JOIN turmas t ON t.id_turma=h.id_turma LEFT JOIN disciplinas d ON d.id_disciplina=h.id_disciplina WHERE h.id_professor=:professor ORDER BY h.dia_semana,h.hora_inicio");
$stmt->execute([':professor' => $idProfessor]);
$horarios = $stmt->fetchAll();
$porDia = array_fill(0, 7, []);
foreach ($horarios as $horario) $porDia[(int)$horario['dia_semana']][] = $horario;
$nomeUsuario = $esc($usuario['nome']);
$meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário | Vanguard</title>
    <link rel="stylesheet" href="css/calendario-professor.css">
    <link rel="stylesheet" href="css/professor-calendar.css">
</head>
<body>
<div class="page-shell">
    <aside class="sidebar">
        <div class="brand-wrap"><img src="imagem/logo-maior.png" alt="Vanguard" class="brand-logo"></div>
        <div class="user-box"><div class="user-avatar"><img src="imagem/perfil.png" alt=""></div><div class="user-name-wrap"><strong><?= $nomeUsuario ?></strong><span>Professor</span></div></div>
        <nav class="side-nav" aria-label="Navegação principal">
            <a href="dash-professor.php" class="nav-item"><img src="imagem/casa-branca.png" alt=""><span>Dashboard</span></a>
            <a href="salas-professor.php" class="nav-item"><img src="imagem/salas-branco.png" alt=""><span>Salas</span><i class="seta" aria-hidden="true"></i></a>
            <a href="calendario-professor.php" class="nav-item active" aria-current="page"><img src="imagem/calendario_01.png" alt=""><span>Calendário / aulas</span></a>
            <a href="notas-professor.php" class="nav-item"><img src="imagem/notas-branco.png" alt=""><span>Notas</span></a>
        </nav>
    </aside>
    <div class="main-column">
        <header class="topbar"><a href="sair.php" class="logout" aria-label="Sair"><img src="imagem/exit.png" alt=""></a></header>
        <main class="content-area">
            <h1 class="cal-titulo"><span>Calendário</span> e aulas do professor</h1>
            <section class="teacher-calendar" data-professor-calendar data-endpoint="calendario-professor.php" data-year="<?= $ano ?>" data-month="<?= $mes ?>" aria-label="Calendário mensal do professor">
                <div class="teacher-calendar-toolbar">
                    <div class="teacher-calendar-controls"><button type="button" id="prof-calendar-prev" aria-label="Mês anterior">‹</button><h2 id="prof-calendar-month"></h2><button type="button" id="prof-calendar-next" aria-label="Próximo mês">›</button></div>
                    <div class="teacher-calendar-actions"><button type="button" id="prof-calendar-today">Hoje</button><button type="button" id="prof-calendar-add-reminder" class="teacher-primary-button">＋ Novo lembrete</button></div>
                </div>
                <div class="teacher-calendar-grid" id="prof-calendar-grid" aria-live="polite" aria-busy="true"></div>
                <section class="teacher-month-agenda" aria-labelledby="prof-calendar-agenda-title"><div class="teacher-agenda-heading"><div><span class="teacher-eyebrow">AGENDA DO MÊS</span><h2 id="prof-calendar-agenda-title">Eventos, feriados e meus lembretes</h2></div><span id="prof-calendar-count" class="teacher-agenda-count">— itens</span></div><div id="prof-calendar-events"><p class="teacher-calendar-empty">Carregando calendário…</p></div></section>
            </section>
            <?php if ($erro): ?><p class="teacher-feedback is-error" role="alert"><?= $esc($erro) ?></p><?php elseif (isset($_GET['salvo'])): ?><p class="teacher-feedback" role="status">Alteração salva com sucesso.</p><?php endif; ?>
            <section class="cal-aulas"><h2><img src="imagem/calendario_01.png" alt="">Aulas e salas da semana <button type="button" id="abrir-horario" class="teacher-secondary-button">Editar meus horários</button></h2>
                <?php foreach ($dias as $numeroDia => $nomeDia): ?><div class="cal-dia"><h3><?= $nomeDia ?></h3><span class="cal-pill"><?= count($porDia[$numeroDia]) ?> aulas</span></div>
                    <?php if ($porDia[$numeroDia]): ?><ul class="cal-lista"><?php foreach ($porDia[$numeroDia] as $horario): ?><li><img src="imagem/relogio.png" alt=""><strong><?= substr($horario['hora_inicio'], 0, 5) ?> – <?= substr($horario['hora_fim'], 0, 5) ?></strong><span class="cal-pill"><?= $esc($horario['turma']) ?><?= $horario['disciplina'] ? ' · ' . $esc($horario['disciplina']) : '' ?></span><button type="button" class="editar-horario teacher-small-button" data-id="<?= (int)$horario['id_horario'] ?>" data-turma="<?= (int)$horario['id_turma'] ?>" data-disciplina="<?= $esc($horario['disciplina'] ?? '') ?>" data-dia="<?= (int)$horario['dia_semana'] ?>" data-inicio="<?= $esc(substr($horario['hora_inicio'], 0, 5)) ?>" data-fim="<?= $esc(substr($horario['hora_fim'], 0, 5)) ?>">Editar</button><form method="post" class="inline-form"><input type="hidden" name="acao" value="excluir_horario"><input type="hidden" name="id_horario" value="<?= (int)$horario['id_horario'] ?>"><input type="hidden" name="ano" value="<?= $ano ?>"><input type="hidden" name="mes" value="<?= $mes ?>"><button type="submit" class="teacher-small-button is-danger">Excluir</button></form></li><?php endforeach; ?></ul><?php endif; ?>
                <?php endforeach; ?>
            </section>
        </main>
    </div>
</div>

<dialog id="prof-reminder-modal" class="teacher-dialog" aria-labelledby="prof-reminder-title"><form method="post" action="calendario-professor.php" class="teacher-dialog-form"><div class="teacher-dialog-heading"><div><span class="teacher-eyebrow">SÓ VOCÊ PODE VER</span><h2 id="prof-reminder-title" data-modal-title>Novo lembrete pessoal</h2></div><button type="button" class="teacher-close-button" data-close-professor-modal aria-label="Fechar">×</button></div><input type="hidden" name="acao" value="salvar_lembrete"><input type="hidden" name="id_lembrete"><input type="hidden" name="ano" value="<?= $ano ?>"><input type="hidden" name="mes" value="<?= $mes ?>"><label>Título<input name="titulo" maxlength="120" required placeholder="Ex.: Preparar material da aula"></label><label>Descrição<textarea name="descricao" rows="3" placeholder="Detalhes para você"></textarea></label><label>Data e horário<input name="data_lembrete" type="datetime-local" required></label><div class="teacher-dialog-actions"><button type="button" class="teacher-secondary-button" data-close-professor-modal>Cancelar</button><button type="submit" class="teacher-primary-button">Salvar lembrete</button></div></form></dialog>

<dialog id="modal-horario" class="teacher-dialog" aria-labelledby="horario-title"><form method="post" action="calendario-professor.php" class="teacher-dialog-form"><div class="teacher-dialog-heading"><div><span class="teacher-eyebrow">SUA ROTINA</span><h2 id="horario-title">Meu horário</h2></div><button type="button" class="teacher-close-button" data-close-professor-modal aria-label="Fechar">×</button></div><input type="hidden" name="acao" value="salvar_horario"><input type="hidden" name="id_horario" id="id-horario"><input type="hidden" name="ano" value="<?= $ano ?>"><input type="hidden" name="mes" value="<?= $mes ?>"><label>Sala<select name="id_turma" id="turma-horario" required><?php if (!$salas): ?><option value="">Nenhuma sala ativa cadastrada</option><?php else: foreach ($salas as $sala): ?><option value="<?= (int)$sala['id_turma'] ?>"><?= $esc($sala['turma']) ?></option><?php endforeach; endif; ?></select></label><label>Disciplina<input type="text" name="disciplina_nome" id="disciplina-horario" maxlength="80" required placeholder="Digite o nome da disciplina"></label><label>Dia da semana<select name="dia_semana" id="dia-horario"><?php foreach ($dias as $numero => $nomeDia): ?><option value="<?= $numero ?>"><?= $nomeDia ?></option><?php endforeach; ?></select></label><div class="teacher-time-row"><label>Início<input id="inicio-horario" name="hora_inicio" type="time" required></label><label>Fim<input id="fim-horario" name="hora_fim" type="time" required></label></div><div class="teacher-dialog-actions"><button type="button" class="teacher-secondary-button" data-close-professor-modal>Cancelar</button><button type="submit" class="teacher-primary-button">Salvar horário</button></div></form></dialog>

<script src="js/calendario-professor.js" defer></script>
<script>
const modalHorario = document.getElementById('modal-horario');
const turmaHorario = document.getElementById('turma-horario');
const disciplinaHorario = document.getElementById('disciplina-horario');
document.getElementById('abrir-horario').addEventListener('click', () => {
    document.getElementById('id-horario').value = '';
    if (turmaHorario.options.length) turmaHorario.selectedIndex = 0;
    disciplinaHorario.value = '';
    document.getElementById('dia-horario').value = '1';
    document.getElementById('inicio-horario').value = '08:00';
    document.getElementById('fim-horario').value = '09:00';
    modalHorario.showModal();
});
document.querySelectorAll('.editar-horario').forEach(button => button.addEventListener('click', () => {
    document.getElementById('id-horario').value = button.dataset.id;
    turmaHorario.value = button.dataset.turma;
    disciplinaHorario.value = button.dataset.disciplina;
    document.getElementById('dia-horario').value = button.dataset.dia;
    document.getElementById('inicio-horario').value = button.dataset.inicio;
    document.getElementById('fim-horario').value = button.dataset.fim;
    modalHorario.showModal();
}));
</script>
</body>
</html>
