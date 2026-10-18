<?php
require_once __DIR__ . '/../backend/config/auth.php'; exigirPerfil(['admin', 'coordenador']);
if (isset($_GET['api'])) {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $pdo = getConnection();
        $payload = json_decode(file_get_contents('php://input'), true) ?? [];
        $api = (string)$_GET['api'];
        if ($api === 'eventos' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $eventos = $pdo->query('SELECT id_evento,id_instituicao,titulo,descricao,data_inicio,data_fim,local,publico_alvo,FALSE AS is_feriado FROM eventos ORDER BY data_inicio')->fetchAll();
            $stmt = $pdo->prepare("SELECT NULL AS id_evento,NULL AS id_instituicao,titulo,CONCAT(tipo,IF(observacao IS NULL,'',CONCAT(' — ',observacao))) AS descricao,CONCAT(data,' 00:00:00') AS data_inicio,NULL AS data_fim,NULL AS local,'Todos' AS publico_alvo,TRUE AS is_feriado FROM feriados WHERE ano=:ano ORDER BY data");
            $stmt->execute([':ano'=>(int)date('Y')]);
            echo json_encode(['sucesso'=>true,'dados'=>array_merge($eventos,$stmt->fetchAll())]);
        } elseif ($api === 'instituicoes' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            echo json_encode(['sucesso'=>true,'dados'=>$pdo->query('SELECT id_instituicao,nome FROM instituicoes ORDER BY nome')->fetchAll()]);
        } elseif (in_array($api,['criar_evento','editar_evento'],true) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo=trim($payload['titulo'] ?? ''); $dataInicio=trim($payload['data_inicio'] ?? '');
            $idInstituicao=filter_var($payload['id_instituicao'] ?? null,FILTER_VALIDATE_INT);
            $idEvento=filter_var($payload['id_evento'] ?? null,FILTER_VALIDATE_INT);
            $publico=$payload['publico_alvo'] ?? 'Todos';
            if ($titulo === '' || $dataInicio === '' || !$idInstituicao || !in_array($publico,['Todos','Só alunos','Só professores'],true) || ($api==='editar_evento' && !$idEvento)) throw new InvalidArgumentException('Confira o título, a data e o público do evento.');
            if ($api==='criar_evento') {
                $stmt=$pdo->prepare('INSERT INTO eventos (id_instituicao,titulo,descricao,data_inicio,data_fim,local,publico_alvo) VALUES (:instituicao,:titulo,:descricao,:inicio,NULL,NULL,:publico)');
                $stmt->execute([':instituicao'=>$idInstituicao,':titulo'=>$titulo,':descricao'=>trim($payload['descricao'] ?? '') ?: null,':inicio'=>$dataInicio,':publico'=>$publico]);
            } else {
                $stmt=$pdo->prepare('UPDATE eventos SET id_instituicao=:instituicao,titulo=:titulo,descricao=:descricao,data_inicio=:inicio,data_fim=NULL,local=NULL,publico_alvo=:publico WHERE id_evento=:id');
                $stmt->execute([':instituicao'=>$idInstituicao,':titulo'=>$titulo,':descricao'=>trim($payload['descricao'] ?? '') ?: null,':inicio'=>$dataInicio,':publico'=>$publico,':id'=>$idEvento]);
            }
            echo json_encode(['sucesso'=>true]);
        } elseif ($api==='deletar_evento' && $_SERVER['REQUEST_METHOD']==='POST') {
            $id=filter_var($payload['id_evento'] ?? null,FILTER_VALIDATE_INT); if (!$id) throw new InvalidArgumentException('Evento inválido.');
            $stmt=$pdo->prepare('DELETE FROM eventos WHERE id_evento=:id'); $stmt->execute([':id'=>$id]); echo json_encode(['sucesso'=>true]);
        } else { http_response_code(400); echo json_encode(['sucesso'=>false,'erro'=>'Operação inválida.']); }
    } catch (InvalidArgumentException $exception) {
        http_response_code(400); echo json_encode(['sucesso'=>false,'erro'=>$exception->getMessage()]);
    } catch (PDOException $exception) {
        http_response_code(500); echo json_encode(['sucesso'=>false,'erro'=>'Não foi possível acessar a agenda.']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#091e0a">
    <title>Calendário | Coordenação</title>
    <link rel="stylesheet" href="css/calendario-adm.css">
    <link rel="stylesheet" href="css/feriados.css">
    <link rel="stylesheet" href="css/aside-adm.css">
</head>
<body>
    <div class="page-shell">
        <aside class="sidebar">
            <a class="brand" href="dash-adm.php" aria-label="Vanguard, painel da coordenação"><img src="imagem/SEGUNDA_LOGO.png" alt="Vanguard — Portal Educacional"></a>
            <nav class="side-nav" aria-label="Navegação principal">
                <a class="nav-item" href="dash-adm.php"><img src="imagem/casa-branca.png" alt=""><span>Visão geral</span></a>
                <a class="nav-item" href="escolas-adm.php"><img src="imagem/escolas-icon.png" alt=""><span>Escolas</span></a>
                <a class="nav-item" href="salas-adm.php"><img src="imagem/classroom.png" alt=""><span>Salas</span></a>
                <a class="nav-item" href="professores-adm.php"><img src="imagem/professor.png" alt=""><span>Professores</span></a>
                <a class="nav-item active" href="calendario-adm.php" aria-current="page"><img src="imagem/calendario-verde.png" alt=""><span>Calendário</span></a>
                <a class="nav-item" href="alunos-adm.php"><img src="imagem/reading.png" alt=""><span>Alunos</span></a>
            </nav>
        </aside>
        <div class="main-column">
            <header class="topbar"></header>
            <main class="content-area">
                <div class="page-heading">
                    <div><p class="eyebrow">PAINEL DA COORDENAÇÃO</p><h1>Calendário</h1><p class="subtitle">Acompanhe os eventos e compromissos das instituições.</p></div>
                    <button class="button button-primary" id="novo-evento" type="button"><span aria-hidden="true">＋</span> Novo evento</button>
                </div>

                <section class="calendar-panel" aria-label="Calendário mensal">
                    <div class="calendar-toolbar">
                        <div class="month-controls"><button type="button" class="month-arrow" id="mes-anterior" aria-label="Mês anterior">‹</button><h2 id="mes-atual">Calendário</h2><button type="button" class="month-arrow" id="mes-proximo" aria-label="Próximo mês">›</button></div>
                        <button class="button button-today" type="button" id="hoje">Hoje</button>
                    </div>
                    <div class="calendar-grid" id="grade-calendario" aria-live="polite"></div>
                </section>

                <section class="events-panel" aria-label="Lista de eventos">
                    <div class="events-heading"><div><p class="eyebrow">AGENDA</p><h2>Próximos eventos</h2></div><span id="total-eventos" class="event-count">— eventos</span></div>
                    <div class="events-table-wrap"><table><thead><tr><th>Data</th><th>Evento</th><th>Público</th><th>Ações</th></tr></thead><tbody id="lista-eventos"><tr><td colspan="4" class="table-state">Carregando eventos…</td></tr></tbody></table></div>
                </section>
                <div class="notice" id="mensagem" role="status" aria-live="polite"></div>
            </main>
        </div>
    </div>

    <dialog class="event-dialog" id="modal-evento" aria-labelledby="titulo-modal">
        <form id="form-evento">
            <div class="dialog-heading"><div><p class="eyebrow">AGENDA ESCOLAR</p><h2 id="titulo-modal">Novo evento</h2></div><button type="button" class="icon-close" id="fechar-modal" aria-label="Fechar">×</button></div>
            <input type="hidden" name="id_evento" id="id-evento">
            <input type="hidden" name="id_instituicao" id="instituicao-evento">
            <label for="titulo-evento">Nome do evento <span>*</span></label><input id="titulo-evento" name="titulo" maxlength="100" placeholder="Ex.: Reunião de pais" required>
            <label for="inicio-evento">Data e horário do lembrete <span>*</span></label><input id="inicio-evento" name="data_inicio" type="datetime-local" required>
            <label for="publico-evento">Público</label><select id="publico-evento" name="publico_alvo"><option value="Todos">Todos</option><option value="Só alunos">Só alunos</option><option value="Só professores">Só professores</option></select>
            <label for="descricao-evento">Descrição</label><textarea id="descricao-evento" name="descricao" maxlength="255" rows="3" placeholder="Detalhes adicionais do evento"></textarea>
            <div class="dialog-actions"><button class="button button-muted" type="button" id="cancelar-modal">Cancelar</button><button class="button button-primary" id="salvar-evento" type="submit">Salvar evento</button></div>
        </form>
    </dialog>

    <dialog class="event-dialog confirm-dialog" id="modal-excluir" aria-labelledby="titulo-excluir">
        <form id="form-excluir"><div class="dialog-heading"><div><p class="eyebrow">ATENÇÃO</p><h2 id="titulo-excluir">Excluir evento?</h2></div><button type="button" class="icon-close" data-close="modal-excluir" aria-label="Fechar">×</button></div><p id="texto-excluir" class="confirm-copy">Confirma a exclusão deste evento?</p><div class="dialog-actions"><button class="button button-muted" type="button" data-close="modal-excluir">Cancelar</button><button class="button button-danger" id="confirmar-exclusao" type="submit">Excluir evento</button></div></form>
    </dialog>

    <script>
    (() => {
        const crud = 'calendario-adm.php?api=';
        const now = new Date();
        let shownMonth = new Date(now.getFullYear(), now.getMonth(), 1);
        let events = [], institutions = [], eventToDelete = null, toastTimer;
        const modal = document.getElementById('modal-evento');

        function escapeHtml(value) { return String(value ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c])); }
        function dateKey(value) { const date = new Date(String(value).replace(' ', 'T')); return Number.isNaN(date.getTime()) ? '' : `${date.getFullYear()}-${String(date.getMonth()+1).padStart(2,'0')}-${String(date.getDate()).padStart(2,'0')}`; }
        function notify(message, error = false) { const box = document.getElementById('mensagem'); box.textContent = message; box.className = `notice visible${error ? ' error' : ''}`; clearTimeout(toastTimer); toastTimer = setTimeout(() => box.className = 'notice', 4000); }
        async function request(url, options) { const response = await fetch(url, options); let data; try { data = await response.json(); } catch { throw new Error('O servidor retornou uma resposta inválida.'); } if (!response.ok || data.sucesso === false) throw new Error(data.erro || 'Não foi possível concluir a operação.'); return data; }
        function formatDate(value) { const date = new Date(String(value).replace(' ', 'T')); if (Number.isNaN(date.getTime())) return '—'; return new Intl.DateTimeFormat('pt-BR', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' }).format(date); }
        function toLocalInput(value) { const date = new Date(String(value || '').replace(' ', 'T')); if (Number.isNaN(date.getTime())) return ''; return `${date.getFullYear()}-${String(date.getMonth()+1).padStart(2,'0')}-${String(date.getDate()).padStart(2,'0')}T${String(date.getHours()).padStart(2,'0')}:${String(date.getMinutes()).padStart(2,'0')}`; }

        async function loadData() {
            const body = document.getElementById('lista-eventos');
            body.innerHTML = '<tr><td colspan="4" class="table-state">Carregando eventos…</td></tr>';
            try {
                const [eventData, institutionData] = await Promise.all([request(`${crud}eventos`), request(`${crud}instituicoes`)]);
                events = eventData.dados || [];
                institutions = institutionData.dados || [];
                renderCalendar(); renderEvents();
            } catch (error) {
                body.innerHTML = '<tr><td colspan="4" class="table-state error-state">Não foi possível carregar a agenda. Verifique se o servidor PHP está ativo.</td></tr>';
            }
        }
        function renderCalendar() {
            const year = shownMonth.getFullYear(), month = shownMonth.getMonth();
            document.getElementById('mes-atual').textContent = new Intl.DateTimeFormat('pt-BR', { month:'long', year:'numeric' }).format(shownMonth);
            const grid = document.getElementById('grade-calendario');
            const weekdays = ['Seg','Ter','Qua','Qui','Sex','Sáb','Dom'];
            const firstWeekday = (new Date(year, month, 1).getDay() + 6) % 7;
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const previousDays = new Date(year, month, 0).getDate();
            let cells = weekdays.map(day => `<div class="weekday">${day}</div>`).join('');
            for (let index = 0; index < 42; index++) {
                let day, date, outside = false;
                if (index < firstWeekday) { day = previousDays - firstWeekday + index + 1; date = new Date(year, month - 1, day); outside = true; }
                else if (index >= firstWeekday + daysInMonth) { day = index - firstWeekday - daysInMonth + 1; date = new Date(year, month + 1, day); outside = true; }
                else { day = index - firstWeekday + 1; date = new Date(year, month, day); }
                const key = `${date.getFullYear()}-${String(date.getMonth()+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
                const dayEvents = events.filter(item => dateKey(item.data_inicio) === key);
                const isToday = date.toDateString() === now.toDateString();
                cells += `<button type="button" class="calendar-day${outside ? ' outside' : ''}${isToday ? ' today' : ''}" data-date="${key}" aria-label="${day} ${new Intl.DateTimeFormat('pt-BR',{month:'long'}).format(date)}${dayEvents.length ? `, ${dayEvents.length} evento(s)` : ''}"><span class="day-number">${day}</span><span class="day-events">${dayEvents.slice(0,2).map(event => `<span class="day-event">${escapeHtml(event.titulo)}</span>`).join('')}${dayEvents.length > 2 ? `<span class="more-events">+${dayEvents.length-2} eventos</span>` : ''}</span></button>`;
            }
            grid.innerHTML = cells;
        }
        function renderEvents() {
            const upcoming = [...events].sort((a,b) => new Date(String(a.data_inicio).replace(' ','T')) - new Date(String(b.data_inicio).replace(' ','T')));
            document.getElementById('total-eventos').textContent = `${upcoming.length} ${upcoming.length === 1 ? 'evento' : 'eventos'}`;
            const body = document.getElementById('lista-eventos');
            if (!upcoming.length) { body.innerHTML = '<tr><td colspan="4" class="table-state">Nenhum evento cadastrado. Use “Novo evento” para adicionar à agenda.</td></tr>'; return; }
            body.innerHTML = upcoming.map(event => `<tr><td data-label="Data">${escapeHtml(formatDate(event.data_inicio))}</td><td data-label="Evento"><strong class="event-title">${escapeHtml(event.titulo)}</strong>${event.descricao ? `<small class="event-description">${escapeHtml(event.descricao)}</small>` : ''}</td><td data-label="Público">${escapeHtml(event.publico_alvo || 'Todos')}</td><td data-label="Ações">${event.is_feriado ? '<span>Calendário oficial</span>' : `<div class="row-actions"><button class="button button-edit" type="button" data-edit="${escapeHtml(event.id_evento)}">Editar</button><button class="button button-delete" type="button" data-delete="${escapeHtml(event.id_evento)}">Excluir</button></div>`}</td></tr>`).join('');
        }
        function openEventForm(event = null, day = '') {
            const form = document.getElementById('form-evento'); form.reset();
            document.getElementById('id-evento').value = event?.id_evento || '';
            document.getElementById('instituicao-evento').value = event?.id_instituicao || institutions[0]?.id_instituicao || '';
            document.getElementById('titulo-evento').value = event?.titulo || '';
            document.getElementById('inicio-evento').value = event ? toLocalInput(event.data_inicio) : (day ? `${day}T09:00` : '');
            document.getElementById('publico-evento').value = event?.publico_alvo || 'Todos';
            document.getElementById('descricao-evento').value = event?.descricao || '';
            document.getElementById('titulo-modal').textContent = event ? 'Editar evento' : 'Novo evento';
            document.getElementById('salvar-evento').textContent = event ? 'Salvar alterações' : 'Salvar evento';
            modal.showModal(); document.getElementById('titulo-evento').focus();
        }
        document.getElementById('mes-anterior').addEventListener('click', () => { shownMonth = new Date(shownMonth.getFullYear(), shownMonth.getMonth()-1, 1); renderCalendar(); });
        document.getElementById('mes-proximo').addEventListener('click', () => { shownMonth = new Date(shownMonth.getFullYear(), shownMonth.getMonth()+1, 1); renderCalendar(); });
        document.getElementById('hoje').addEventListener('click', () => { shownMonth = new Date(now.getFullYear(), now.getMonth(), 1); renderCalendar(); });
        document.getElementById('novo-evento').addEventListener('click', () => openEventForm());
        document.getElementById('fechar-modal').addEventListener('click', () => modal.close());
        document.getElementById('cancelar-modal').addEventListener('click', () => modal.close());
        modal.addEventListener('click', event => { if (event.target === modal) modal.close(); });
        document.getElementById('grade-calendario').addEventListener('click', event => { const day = event.target.closest('[data-date]'); if (day) openEventForm(null, day.dataset.date); });
        document.getElementById('form-evento').addEventListener('submit', async event => {
            event.preventDefault(); const form = event.currentTarget; if (!form.reportValidity()) return;
            const payload = Object.fromEntries(new FormData(form)); const id = payload.id_evento;
            payload.data_inicio = payload.data_inicio.replace('T', ' ') + ':00';
            payload.data_fim = null;
            payload.local = null;
            payload.publico_alvo = payload.publico_alvo || null;
            const button = document.getElementById('salvar-evento'); button.disabled = true;
            try { await request(`${crud}${id ? 'editar_evento' : 'criar_evento'}`, { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload) }); modal.close(); notify(id ? 'Evento atualizado.' : 'Evento adicionado à agenda.'); await loadData(); }
            catch (error) { notify(error.message, true); } finally { button.disabled = false; }
        });
        document.getElementById('lista-eventos').addEventListener('click', event => {
            const edit = event.target.closest('[data-edit]'), del = event.target.closest('[data-delete]');
            if (edit) { const item = events.find(entry => String(entry.id_evento) === edit.dataset.edit); if (item) openEventForm(item); }
            if (del) { eventToDelete = events.find(entry => String(entry.id_evento) === del.dataset.delete); if (!eventToDelete) return; document.getElementById('texto-excluir').textContent = `Tem certeza de que deseja excluir “${eventToDelete.titulo}”?`; document.getElementById('modal-excluir').showModal(); }
        });
        document.querySelectorAll('[data-close="modal-excluir"]').forEach(button => button.addEventListener('click', () => document.getElementById('modal-excluir').close()));
        document.getElementById('modal-excluir').addEventListener('click', event => { if (event.target === event.currentTarget) event.currentTarget.close(); });
        document.getElementById('form-excluir').addEventListener('submit', async event => {
            event.preventDefault(); if (!eventToDelete) return; const button = document.getElementById('confirmar-exclusao'); button.disabled = true;
            try { await request(`${crud}deletar_evento`, { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ id_evento:eventToDelete.id_evento }) }); document.getElementById('modal-excluir').close(); notify('Evento excluído.'); eventToDelete = null; await loadData(); }
            catch (error) { notify(error.message, true); } finally { button.disabled = false; }
        });
        loadData();
    })();
    </script>
</body>
</html>
