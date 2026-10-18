<?php
require_once __DIR__ . '/../backend/config/auth.php'; exigirPerfil(['admin', 'coordenador']);
if (isset($_GET['api'])) {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $pdo = getConnection();
        $payload = json_decode(file_get_contents('php://input'), true) ?? [];
        $api = (string)$_GET['api'];
        if ($api === 'salas' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $sql = "SELECT t.id_turma,t.id_instituicao,t.nome,t.periodo,i.nome AS nome_instituicao,
                           COUNT(m.id_matricula) AS alunos_total,
                           COALESCE(SUM(CASE WHEN LOWER(COALESCE(m.status,'ativa'))='ativa' THEN 1 ELSE 0 END),0) AS alunos_ativos,
                           COALESCE(SUM(CASE WHEN LOWER(COALESCE(m.status,'ativa'))<>'ativa' THEN 1 ELSE 0 END),0) AS alunos_inativos
                    FROM turmas t INNER JOIN instituicoes i ON i.id_instituicao=t.id_instituicao
                    LEFT JOIN matriculas m ON m.id_turma=t.id_turma
                    GROUP BY t.id_turma,t.id_instituicao,t.nome,t.periodo,i.nome ORDER BY t.nome";
            echo json_encode(['sucesso'=>true,'dados'=>$pdo->query($sql)->fetchAll()]);
        } elseif ($api === 'instituicoes' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            echo json_encode(['sucesso'=>true,'dados'=>$pdo->query('SELECT id_instituicao,nome FROM instituicoes ORDER BY nome')->fetchAll()]);
        } elseif (in_array($api,['criar_sala','editar_sala'],true) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_var($payload['id_turma'] ?? null,FILTER_VALIDATE_INT);
            $instituicao = filter_var($payload['id_instituicao'] ?? null,FILTER_VALIDATE_INT);
            $nome = trim($payload['nome'] ?? ''); $periodo = trim($payload['periodo'] ?? '');
            if (!$instituicao || $nome === '' || $periodo === '' || ($api === 'editar_sala' && !$id)) throw new InvalidArgumentException('Preencha os dados da sala.');
            if ($api === 'criar_sala') {
                $stmt = $pdo->prepare('INSERT INTO turmas (id_instituicao,nome,periodo) VALUES (:instituicao,:nome,:periodo)');
                $stmt->execute([':instituicao'=>$instituicao,':nome'=>$nome,':periodo'=>$periodo]);
            } else {
                $stmt = $pdo->prepare('UPDATE turmas SET id_instituicao=:instituicao,nome=:nome,periodo=:periodo WHERE id_turma=:id');
                $stmt->execute([':instituicao'=>$instituicao,':nome'=>$nome,':periodo'=>$periodo,':id'=>$id]);
            }
            echo json_encode(['sucesso'=>true]);
        } elseif ($api === 'criar_aluno' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome=trim($payload['nome'] ?? ''); $email=trim($payload['email'] ?? ''); $senha=$payload['senha'] ?? ''; $telefone=trim($payload['telefone'] ?? '');
            $idTurma=filter_var($payload['id_turma'] ?? null,FILTER_VALIDATE_INT);
            if ($nome === '' || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($senha)<6 || $telefone === '' || !$idTurma) throw new InvalidArgumentException('Informe nome, e-mail, senha, telefone e sala.');
            $pdo->beginTransaction();
            $room=$pdo->prepare('SELECT id_instituicao FROM turmas WHERE id_turma=:id'); $room->execute([':id'=>$idTurma]); $idInstituicao=$room->fetchColumn();
            if (!$idInstituicao) throw new InvalidArgumentException('A sala selecionada não foi encontrada.');
            $user=$pdo->prepare("INSERT INTO usuarios (id_instituicao,nome,email,senha_hash,telefone,tipo_usuario,status) VALUES (:instituicao,:nome,:email,:senha,:telefone,'aluno','ativo')");
            $user->execute([':instituicao'=>$idInstituicao,':nome'=>$nome,':email'=>$email,':senha'=>password_hash($senha,PASSWORD_BCRYPT),':telefone'=>$telefone]);
            $pdo->prepare("INSERT INTO matriculas (id_usuario,id_turma,status) VALUES (:usuario,:turma,'ativa')")->execute([':usuario'=>$pdo->lastInsertId(),':turma'=>$idTurma]);
            $pdo->commit(); echo json_encode(['sucesso'=>true]);
        } elseif ($api === 'deletar_sala' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id=filter_var($payload['id_turma'] ?? null,FILTER_VALIDATE_INT); if (!$id) throw new InvalidArgumentException('Sala inválida.');
            $stmt=$pdo->prepare('DELETE FROM turmas WHERE id_turma=:id'); $stmt->execute([':id'=>$id]); echo json_encode(['sucesso'=>true]);
        } else { http_response_code(400); echo json_encode(['sucesso'=>false,'erro'=>'Operação inválida.']); }
    } catch (InvalidArgumentException $exception) {
        if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack(); http_response_code(400); echo json_encode(['sucesso'=>false,'erro'=>$exception->getMessage()]);
    } catch (PDOException $exception) {
        if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack(); http_response_code($exception->getCode()==='23000'?409:500); echo json_encode(['sucesso'=>false,'erro'=>$exception->getCode()==='23000'?'O e-mail já está cadastrado ou a turma possui vínculos.':'Não foi possível concluir a operação.']);
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
    <title>Salas | Coordenação</title>
    <link rel="stylesheet" href="css/salas-adm.css">
    <link rel="stylesheet" href="css/aside-adm.css">
</head>
<body>
    <div class="page-shell">
        <aside class="sidebar">
            <a class="brand" href="dash-adm.php" aria-label="Vanguard, painel da coordenação"><img src="imagem/SEGUNDA_LOGO.png" alt="Vanguard — Portal Educacional"></a>
            <nav class="side-nav" aria-label="Navegação principal">
                <a class="nav-item" href="dash-adm.php"><img src="imagem/casa-branca.png" alt=""><span>Visão geral</span></a>
                <a class="nav-item" href="escolas-adm.php"><img src="imagem/escolas-icon.png" alt=""><span>Escolas</span></a>
                <a class="nav-item active" href="salas-adm.php" aria-current="page"><img src="imagem/classroom.png" alt=""><span>Salas</span></a>
                <a class="nav-item" href="professores-adm.php"><img src="imagem/professor.png" alt=""><span>Professores</span></a>
                <a class="nav-item" href="calendario-adm.php"><img src="imagem/calendario-verde.png" alt=""><span>Calendário</span></a>
                <a class="nav-item" href="alunos-adm.php"><img src="imagem/reading.png" alt=""><span>Alunos</span></a>
            </nav>
            <div class="sidebar-decoration" aria-hidden="true">✧</div>
        </aside>

        <div class="main-column">
            <header class="topbar"></header>
            <main class="content-area">
                <div class="page-heading">
                    <div><p class="eyebrow">PAINEL DA COORDENAÇÃO</p><h1>Salas</h1><p class="subtitle">Gerencie turmas e acompanhe os alunos cadastrados.</p></div>
                    <div class="room-total"><strong id="total-turmas">—</strong><span>turmas</span></div>
                </div>
                <section class="toolbar" aria-label="Ferramentas de salas">
                    <label class="search-box" for="busca-salas"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"></circle><path d="m15.5 15.5 5 5"></path></svg><input id="busca-salas" type="search" placeholder="Buscar sala, escola ou período..." autocomplete="off"></label>
                    <button class="button button-primary" id="nova-sala" type="button"><span aria-hidden="true">＋</span> Nova sala</button>
                </section>
                <div id="mensagem" class="notice" role="status" aria-live="polite"></div>
                <section id="lista-salas" class="room-list" aria-label="Turmas cadastradas"><p class="list-state">Carregando salas…</p></section>
            </main>
        </div>
    </div>

    <dialog class="dialog" id="modal-sala" aria-labelledby="titulo-sala">
        <form id="form-sala">
            <div class="dialog-heading"><div><p class="eyebrow">TURMA</p><h2 id="titulo-sala">Nova sala</h2></div><button class="icon-close" type="button" data-close="modal-sala" aria-label="Fechar">×</button></div>
            <input type="hidden" name="id_turma" id="id-turma">
            <label for="instituicao-sala">Instituição <span>*</span></label><select id="instituicao-sala" name="id_instituicao" required><option value="">Selecione uma instituição</option></select>
            <label for="nome-sala">Nome da turma <span>*</span></label><input id="nome-sala" name="nome" maxlength="50" placeholder="Ex.: 3MD" required>
            <label for="periodo-sala">Período <span>*</span></label><select id="periodo-sala" name="periodo" required><option value="">Selecione o período</option><option value="Manhã">Manhã</option><option value="Tarde">Tarde</option><option value="Noite">Noite</option><option value="Integral">Integral</option></select>
            <div class="dialog-actions"><button class="button button-muted" type="button" data-close="modal-sala">Cancelar</button><button class="button button-primary" id="salvar-sala" type="submit">Salvar sala</button></div>
        </form>
    </dialog>

    <dialog class="dialog" id="modal-aluno" aria-labelledby="titulo-aluno">
        <form id="form-aluno">
            <div class="dialog-heading"><div><p class="eyebrow">MATRÍCULA</p><h2 id="titulo-aluno">Cadastrar aluno</h2></div><button class="icon-close" type="button" data-close="modal-aluno" aria-label="Fechar">×</button></div>
            <input type="hidden" name="id_instituicao" id="instituicao-aluno"><input type="hidden" name="tipo_usuario" value="aluno">
            <label for="sala-aluno">Sala <span>*</span></label><select id="sala-aluno" name="id_turma" required><option value="">Selecione a sala</option></select>
            <label for="nome-aluno">Nome <span>*</span></label><input id="nome-aluno" name="nome" maxlength="100" autocomplete="name" required>
            <label for="email-aluno">E-mail <span>*</span></label><input id="email-aluno" name="email" type="email" maxlength="150" autocomplete="email" required>
            <label for="senha-aluno">Senha <span>*</span></label><input id="senha-aluno" name="senha" type="password" minlength="6" autocomplete="new-password" required>
            <label for="telefone-aluno">Telefone <span>*</span></label><input id="telefone-aluno" name="telefone" type="tel" inputmode="tel" maxlength="20" placeholder="(11) 99999-9999" autocomplete="tel" required>
            <div class="dialog-actions"><button class="button button-muted" type="button" data-close="modal-aluno">Cancelar</button><button class="button button-primary" id="salvar-aluno" type="submit">Cadastrar aluno</button></div>
        </form>
    </dialog>

    <dialog class="dialog confirm-dialog" id="modal-excluir" aria-labelledby="titulo-excluir">
        <form id="form-excluir"><div class="dialog-heading"><div><p class="eyebrow">ATENÇÃO</p><h2 id="titulo-excluir">Excluir sala?</h2></div><button class="icon-close" type="button" data-close="modal-excluir" aria-label="Fechar">×</button></div>
            <p class="confirm-copy" id="texto-excluir">Confirma a exclusão desta sala?</p><p class="confirm-hint">As matrículas desta turma também serão removidas.</p>
            <div class="dialog-actions"><button class="button button-muted" type="button" data-close="modal-excluir">Cancelar</button><button class="button button-danger" id="confirmar-exclusao" type="submit">Excluir sala</button></div>
        </form>
    </dialog>

    <script>
    (() => {
        const api = 'salas-adm.php?api=';
        const roomList = document.getElementById('lista-salas');
        const roomsById = new Map();
        let rooms = [], institutions = [], roomToDelete = null, toastTimer;
        const dialogs = ['modal-sala', 'modal-aluno', 'modal-excluir'].map(id => document.getElementById(id));

        function notify(message, error = false) {
            const box = document.getElementById('mensagem');
            box.textContent = message;
            box.className = `notice visible${error ? ' error' : ''}`;
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => { box.className = 'notice'; }, 4200);
        }
        async function request(url, options) {
            const response = await fetch(url, options);
            let data;
            try { data = await response.json(); } catch { throw new Error('O servidor retornou uma resposta inválida.'); }
            if (!response.ok || data.sucesso === false) throw new Error(data.erro || 'Não foi possível concluir a operação.');
            return data;
        }
        function escapeHtml(value) { return String(value ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c])); }
        function closeDialogs() { dialogs.forEach(dialog => dialog.close()); }

        async function loadPageData() {
            roomList.innerHTML = '<p class="list-state">Carregando salas…</p>';
            try {
                const [roomData, institutionData] = await Promise.all([
                    request(`${api}salas`), request(`${api}instituicoes`)
                ]);
                rooms = roomData.dados || [];
                institutions = institutionData.dados || [];
                rooms.forEach(room => roomsById.set(String(room.id_turma), room));
                document.getElementById('total-turmas').textContent = rooms.length;
                populateInstitutionOptions();
                populateRoomOptions();
                renderRooms();
            } catch (error) {
                roomList.innerHTML = '<p class="list-state error-state">Não foi possível carregar as salas. Verifique se o servidor PHP está ativo.</p>';
                document.getElementById('total-turmas').textContent = '—';
            }
        }
        function populateInstitutionOptions(selected = '') {
            const select = document.getElementById('instituicao-sala');
            select.innerHTML = '<option value="">Selecione uma instituição</option>' + institutions.map(item => `<option value="${escapeHtml(item.id_instituicao)}" ${String(item.id_instituicao) === String(selected) ? 'selected' : ''}>${escapeHtml(item.nome)}</option>`).join('');
        }
        function populateRoomOptions(selected = '') {
            const select = document.getElementById('sala-aluno');
            select.innerHTML = '<option value="">Selecione a sala</option>' + rooms.map(room => `<option value="${escapeHtml(room.id_turma)}" data-institution="${escapeHtml(room.id_instituicao)}" ${String(room.id_turma) === String(selected) ? 'selected' : ''}>${escapeHtml(room.nome)} — ${escapeHtml(room.nome_instituicao)} (${escapeHtml(room.periodo || 'período não informado')})</option>`).join('');
        }
        function renderRooms() {
            const query = document.getElementById('busca-salas').value.trim().toLocaleLowerCase('pt-BR');
            const visible = rooms.filter(room => `${room.nome} ${room.nome_instituicao} ${room.periodo || ''}`.toLocaleLowerCase('pt-BR').includes(query));
            if (!visible.length) {
                roomList.innerHTML = `<p class="list-state">${rooms.length ? 'Nenhuma sala corresponde à busca.' : 'Ainda não há salas cadastradas. Use “Nova sala” para adicionar a primeira.'}</p>`;
                return;
            }
            roomList.innerHTML = visible.map(room => {
                const active = Number(room.alunos_ativos || 0), inactive = Number(room.alunos_inativos || 0), total = Number(room.alunos_total || 0);
                return `<article class="room-card">
                    <div class="room-main"><div class="room-title-line"><span class="room-icon" aria-hidden="true">▦</span><h2>${escapeHtml(room.nome)}</h2><span class="room-period">${escapeHtml(room.periodo || 'Período não informado')}</span></div>
                    <p class="institution-name">${escapeHtml(room.nome_instituicao)}</p>
                    <div class="student-stats"><span><i class="dot active"></i><strong>${active}</strong> ativos</span><span><i class="dot inactive"></i><strong>${inactive}</strong> desativados</span><span class="total-students"><strong>${total}</strong> cadastrados</span></div></div>
                    <div class="room-actions"><button class="button button-student" type="button" data-student="${escapeHtml(room.id_turma)}">＋ Aluno</button><button class="button button-edit" type="button" data-edit="${escapeHtml(room.id_turma)}">Editar sala</button><button class="button button-delete" type="button" data-delete="${escapeHtml(room.id_turma)}">Excluir</button></div>
                </article>`;
            }).join('');
        }
        function openRoomDialog(room = null) {
            const form = document.getElementById('form-sala');
            form.reset();
            document.getElementById('id-turma').value = room?.id_turma || '';
            populateInstitutionOptions(room?.id_instituicao || '');
            document.getElementById('nome-sala').value = room?.nome || '';
            document.getElementById('periodo-sala').value = room?.periodo || '';
            document.getElementById('titulo-sala').textContent = room ? 'Editar sala' : 'Nova sala';
            document.getElementById('salvar-sala').textContent = room ? 'Salvar alterações' : 'Salvar sala';
            document.getElementById('modal-sala').showModal();
            document.getElementById('nome-sala').focus();
        }
        function openStudentDialog(roomId = '') {
            const form = document.getElementById('form-aluno');
            form.reset();
            populateRoomOptions(roomId);
            const selectedRoom = roomsById.get(String(roomId));
            document.getElementById('instituicao-aluno').value = selectedRoom?.id_instituicao || '';
            document.getElementById('modal-aluno').showModal();
            document.getElementById('nome-aluno').focus();
        }

        document.getElementById('nova-sala').addEventListener('click', () => openRoomDialog());
        document.getElementById('busca-salas').addEventListener('input', renderRooms);
        document.querySelectorAll('[data-close]').forEach(button => button.addEventListener('click', () => document.getElementById(button.dataset.close).close()));
        dialogs.forEach(dialog => dialog.addEventListener('click', event => { if (event.target === dialog) dialog.close(); }));
        document.getElementById('sala-aluno').addEventListener('change', event => {
            const option = event.target.selectedOptions[0];
            document.getElementById('instituicao-aluno').value = option?.dataset.institution || '';
        });
        document.getElementById('telefone-aluno').addEventListener('input', event => {
            let digits = event.target.value.replace(/\D/g, '').slice(0, 11);
            if (digits.length > 10) digits = digits.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, '($1) $2-$3');
            else if (digits.length > 6) digits = digits.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
            else if (digits.length > 2) digits = digits.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
            event.target.value = digits;
        });

        document.getElementById('form-sala').addEventListener('submit', async event => {
            event.preventDefault();
            if (!event.currentTarget.reportValidity()) return;
            const payload = Object.fromEntries(new FormData(event.currentTarget));
            const id = payload.id_turma;
            const button = document.getElementById('salvar-sala');
            button.disabled = true;
            try {
                await request(`${api}${id ? 'editar_sala' : 'criar_sala'}`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
                closeDialogs();
                notify(id ? 'Sala atualizada com sucesso.' : 'Sala cadastrada com sucesso.');
                await loadPageData();
            } catch (error) { notify(error.message, true); }
            finally { button.disabled = false; }
        });
        document.getElementById('form-aluno').addEventListener('submit', async event => {
            event.preventDefault();
            if (!event.currentTarget.reportValidity()) return;
            const payload = Object.fromEntries(new FormData(event.currentTarget));
            const button = document.getElementById('salvar-aluno');
            button.disabled = true;
            try {
                await request(`${api}criar_aluno`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
                closeDialogs();
                notify('Aluno cadastrado e matriculado na sala.');
                await loadPageData();
            } catch (error) { notify(error.message, true); }
            finally { button.disabled = false; }
        });
        roomList.addEventListener('click', event => {
            const studentButton = event.target.closest('[data-student]');
            const editButton = event.target.closest('[data-edit]');
            const deleteButton = event.target.closest('[data-delete]');
            if (studentButton) openStudentDialog(studentButton.dataset.student);
            if (editButton) openRoomDialog(roomsById.get(String(editButton.dataset.edit)));
            if (deleteButton) {
                roomToDelete = roomsById.get(String(deleteButton.dataset.delete));
                if (!roomToDelete) return;
                document.getElementById('texto-excluir').textContent = `Tem certeza de que deseja excluir a sala “${roomToDelete.nome}” da instituição ${roomToDelete.nome_instituicao}?`;
                document.getElementById('modal-excluir').showModal();
            }
        });
        document.getElementById('form-excluir').addEventListener('submit', async event => {
            event.preventDefault();
            if (!roomToDelete) return;
            const button = document.getElementById('confirmar-exclusao');
            button.disabled = true;
            try {
                await request(`${api}deletar_sala`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id_turma: roomToDelete.id_turma }) });
                closeDialogs();
                notify('Sala excluída com sucesso.');
                roomToDelete = null;
                await loadPageData();
            } catch (error) { notify(error.message, true); }
            finally { button.disabled = false; }
        });

        loadPageData();
    })();
    </script>
</body>
</html>
