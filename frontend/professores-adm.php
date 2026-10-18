<?php
require_once __DIR__ . '/../backend/config/auth.php'; exigirPerfil(['admin', 'coordenador']);
if (isset($_GET['api'])) {
    header('Content-Type: application/json; charset=utf-8');
    $api=(string)$_GET['api'];$dados=json_decode(file_get_contents('php://input'),true)??[];
    try {
        $pdo=getConnection();
        if($api==='turmas'&&$_SERVER['REQUEST_METHOD']==='GET')echo json_encode(['sucesso'=>true,'dados'=>$pdo->query('SELECT t.id_turma,t.nome,t.periodo,i.nome AS nome_instituicao FROM turmas t INNER JOIN instituicoes i ON i.id_instituicao=t.id_instituicao ORDER BY i.nome,t.nome')->fetchAll()]);
        elseif($api==='instituicoes'&&$_SERVER['REQUEST_METHOD']==='GET')echo json_encode(['sucesso'=>true,'dados'=>$pdo->query('SELECT id_instituicao,nome FROM instituicoes ORDER BY nome')->fetchAll()]);
        elseif($api==='listar'&&$_SERVER['REQUEST_METHOD']==='GET'){$idTurma=filter_var($_GET['id_turma']??null,FILTER_VALIDATE_INT);$sql="SELECT u.id_usuario,u.id_instituicao,u.nome,u.email,u.telefone,COALESCE(u.status,'ativo') AS status,i.nome AS nome_instituicao,NULL AS id_matricula,t.id_turma,GROUP_CONCAT(DISTINCT t.nome ORDER BY t.nome SEPARATOR ', ') AS nome_turma FROM usuarios u INNER JOIN instituicoes i ON i.id_instituicao=u.id_instituicao LEFT JOIN professor_turmas pt ON pt.id_professor=u.id_usuario LEFT JOIN turmas t ON t.id_turma=pt.id_turma WHERE u.tipo_usuario='professor'";if($idTurma)$sql.=" AND EXISTS (SELECT 1 FROM professor_turmas px WHERE px.id_professor=u.id_usuario AND px.id_turma=".(int)$idTurma.")";$sql.=' GROUP BY u.id_usuario,u.id_instituicao,u.nome,u.email,u.telefone,u.status,i.nome,t.id_turma ORDER BY u.nome';echo json_encode(['sucesso'=>true,'dados'=>$pdo->query($sql)->fetchAll()]);}
        elseif(in_array($api,['criar','editar'],true)&&$_SERVER['REQUEST_METHOD']==='POST'){$nome=trim($dados['nome']??'');$email=trim($dados['email']??'');$senha=$dados['senha']??'';$telefone=trim($dados['telefone']??'');$inst=filter_var($dados['id_instituicao']??null,FILTER_VALIDATE_INT);$id=filter_var($dados['id_usuario']??null,FILTER_VALIDATE_INT);if($nome===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||!$inst||($api==='criar'&&strlen($senha)<6)||($api==='editar'&&$senha!==''&&strlen($senha)<6))throw new InvalidArgumentException('Confira nome, e-mail, instituição e senha.');$check=$pdo->prepare('SELECT id_instituicao FROM instituicoes WHERE id_instituicao=:id');$check->execute([':id'=>$inst]);if(!$check->fetchColumn())throw new InvalidArgumentException('Instituição não encontrada.');if($api==='criar'){$stmt=$pdo->prepare("INSERT INTO usuarios (id_instituicao,nome,email,telefone,senha_hash,tipo_usuario,status) VALUES (:inst,:nome,:email,:telefone,:senha,'professor','ativo')");$stmt->execute([':inst'=>$inst,':nome'=>$nome,':email'=>$email,':telefone'=>$telefone?:null,':senha'=>password_hash($senha,PASSWORD_BCRYPT)]);}else{if(!$id)throw new InvalidArgumentException('Professor não encontrado.');$sql="UPDATE usuarios SET id_instituicao=:inst,nome=:nome,email=:email,telefone=:telefone".($senha!==''?',senha_hash=:senha':'')." WHERE id_usuario=:id AND tipo_usuario='professor'";$params=[':inst'=>$inst,':nome'=>$nome,':email'=>$email,':telefone'=>$telefone?:null,':id'=>$id];if($senha!=='')$params[':senha']=password_hash($senha,PASSWORD_BCRYPT);$pdo->prepare($sql)->execute($params);}echo json_encode(['sucesso'=>true]);}
        elseif($api==='status'&&$_SERVER['REQUEST_METHOD']==='POST'){$id=filter_var($dados['id_usuario']??null,FILTER_VALIDATE_INT);$status=$dados['status']??'';if(!$id||!in_array($status,['ativo','inativo'],true))throw new InvalidArgumentException('Status inválido.');$stmt=$pdo->prepare("UPDATE usuarios SET status=:status WHERE id_usuario=:id AND tipo_usuario='professor'");$stmt->execute([':status'=>$status,':id'=>$id]);echo json_encode(['sucesso'=>true]);}
        elseif($api==='deletar'&&$_SERVER['REQUEST_METHOD']==='POST'){$id=filter_var($dados['id_usuario']??null,FILTER_VALIDATE_INT);if(!$id)throw new InvalidArgumentException('Professor inválido.');$pdo->beginTransaction();$pdo->prepare('DELETE FROM entregas_atividades WHERE id_atividade IN (SELECT id_atividade FROM atividades WHERE id_professor=:id)')->execute([':id'=>$id]);$pdo->prepare('DELETE FROM professor_turmas WHERE id_professor=:id')->execute([':id'=>$id]);$pdo->prepare('DELETE FROM atividades WHERE id_professor=:id')->execute([':id'=>$id]);$pdo->prepare("DELETE FROM usuarios WHERE id_usuario=:id AND tipo_usuario='professor'")->execute([':id'=>$id]);$pdo->commit();echo json_encode(['sucesso'=>true]);}
        else{http_response_code(400);echo json_encode(['sucesso'=>false,'erro'=>'Operação inválida.']);}
    }catch(InvalidArgumentException $exception){if(isset($pdo)&&$pdo->inTransaction())$pdo->rollBack();http_response_code(400);echo json_encode(['sucesso'=>false,'erro'=>$exception->getMessage()]);}
    catch(PDOException $exception){if(isset($pdo)&&$pdo->inTransaction())$pdo->rollBack();http_response_code($exception->getCode()==='23000'?409:500);echo json_encode(['sucesso'=>false,'erro'=>$exception->getCode()==='23000'?'E-mail já cadastrado.':'Não foi possível concluir a operação.']);}
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="theme-color" content="#091e0a">
    <title>Professores | Vanguard</title><link rel="stylesheet" href="css/professores-adm.css"><link rel="stylesheet" href="css/aside-adm.css">
</head>
<body data-role="professor">
    <div class="page-shell">
        <aside class="sidebar">
            <a class="brand" href="dash-adm.php" aria-label="Vanguard, painel da coordenação"><img src="imagem/SEGUNDA_LOGO.png" alt="Vanguard — Portal Educacional"></a>
            <nav class="side-nav" aria-label="Navegação principal">
                <a class="nav-item" href="dash-adm.php"><img src="imagem/casa-branca.png" alt=""><span>Visão geral</span></a>
                <a class="nav-item" href="escolas-adm.php"><img src="imagem/escolas-icon.png" alt=""><span>Escolas</span></a>
                <a class="nav-item" href="salas-adm.php"><img src="imagem/classroom.png" alt=""><span>Salas</span></a>
                <a class="nav-item active" href="professores-adm.php" aria-current="page"><img src="imagem/professor.png" alt=""><span>Professores</span></a>
                <a class="nav-item" href="calendario-adm.php"><img src="imagem/calendario-verde.png" alt=""><span>Calendário</span></a>
                <a class="nav-item" href="alunos-adm.php"><img src="imagem/reading.png" alt=""><span>Alunos</span></a>
            </nav>
        </aside>
        <div class="main-column"><header class="topbar"></header>
            <main class="content-area">
                <div class="page-heading"><div><p class="eyebrow">PAINEL DA COORDENAÇÃO</p><h1>Professores</h1><p class="subtitle">Consulte os cadastros e gerencie o acesso dos professores.</p></div><div class="user-total"><strong id="total-usuarios">—</strong><span>professores</span></div></div>
                <section class="toolbar" aria-label="Filtros de professores">
                    <label class="search-box" for="busca-usuarios"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"></circle><path d="m15.5 15.5 5 5"></path></svg><input id="busca-usuarios" type="search" placeholder="Buscar professor (nome ou e-mail)..." autocomplete="off"></label>
                    <label class="filter-box"><span class="sr-only">Filtrar por sala</span><select id="filtro-sala"><option value="">Todas as salas</option></select></label>
                    <button class="button button-primary" id="novo-usuario" type="button"><span aria-hidden="true">＋</span> Novo professor</button>
                </section>
                <div class="summary-row"><span id="resultado-usuarios">Carregando professores…</span><span class="status-summary"><i class="dot active"></i><b id="total-ativos">0</b> ativos <i class="dot inactive"></i><b id="total-inativos">0</b> inativos</span></div>
                <div class="notice" id="mensagem" role="status" aria-live="polite"></div>
                <section class="table-card" aria-label="Lista de professores"><div class="table-scroll"><table><thead><tr><th>Nome</th><th>Sala</th><th>E-mail</th><th>Status</th><th><span class="sr-only">Ações</span></th></tr></thead><tbody id="lista-usuarios"><tr><td colspan="5" class="table-state">Carregando professores…</td></tr></tbody></table></div></section>
            </main>
        </div>
    </div>
    <dialog class="user-dialog" id="modal-usuario" aria-labelledby="titulo-modal"><form id="form-usuario">
        <div class="dialog-heading"><div><p class="eyebrow">CADASTRO</p><h2 id="titulo-modal">Novo professor</h2></div><button type="button" class="icon-close" data-close="modal-usuario" aria-label="Fechar">×</button></div>
        <input type="hidden" name="id_usuario" id="id-usuario"><input type="hidden" name="id_matricula" id="id-matricula">
        <div class="field" id="campo-sala"><label for="sala-usuario">Sala <span>*</span></label><select id="sala-usuario" name="id_turma" required><option value="">Selecione uma sala</option></select></div>
        <div class="field" id="campo-instituicao"><label for="instituicao-usuario">Instituição <span>*</span></label><select id="instituicao-usuario" name="id_instituicao"><option value="">Selecione uma instituição</option></select></div>
        <label for="nome-usuario">Nome <span>*</span></label><input id="nome-usuario" name="nome" maxlength="100" autocomplete="name" required>
        <label for="email-usuario">E-mail <span>*</span></label><input id="email-usuario" name="email" type="email" maxlength="150" autocomplete="email" required>
        <label for="telefone-usuario">Telefone <span>*</span></label><input id="telefone-usuario" name="telefone" type="tel" maxlength="20" autocomplete="tel" required>
        <label for="senha-usuario">Senha <span id="senha-obrigatoria">*</span></label><input id="senha-usuario" name="senha" type="password" minlength="6" autocomplete="new-password">
        <p class="field-hint" id="senha-ajuda">A senha será armazenada com proteção.</p>
        <div class="dialog-actions"><button type="button" class="button button-muted" data-close="modal-usuario">Cancelar</button><button type="submit" class="button button-primary" id="salvar-usuario">Cadastrar professor</button></div>
    </form></dialog>
    <dialog class="user-dialog confirm-dialog" id="modal-excluir" aria-labelledby="titulo-excluir"><form id="form-excluir"><div class="dialog-heading"><div><p class="eyebrow">ATENÇÃO</p><h2 id="titulo-excluir">Excluir professor?</h2></div><button type="button" class="icon-close" data-close="modal-excluir" aria-label="Fechar">×</button></div><p class="confirm-copy" id="texto-excluir">Confirma a exclusão deste cadastro?</p><p class="confirm-hint" id="aviso-excluir"></p><div class="dialog-actions"><button type="button" class="button button-muted" data-close="modal-excluir">Cancelar</button><button type="submit" class="button button-danger" id="confirmar-exclusao">Excluir professor</button></div></form></dialog>
    <script>window.USUARIO_API = 'professores-adm.php?api=';</script>
    <script>
(() => {
    const role = document.body.dataset.role;
    const isStudent = role === 'aluno';
    const roleLabel = isStudent ? 'aluno' : 'professor';
    const plural = isStudent ? 'alunos' : 'professores';
    const crud = window.USUARIO_API;
    const list = document.getElementById('lista-usuarios');
    const form = document.getElementById('form-usuario');
    const modal = document.getElementById('modal-usuario');
    let users = [], visibleUsers = [], rooms = [], institutions = [], deletingUser = null, timer;

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, char => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[char]));
    }
    async function request(url, options) {
        const response = await fetch(url, options);
        let data;
        try { data = await response.json(); } catch { throw new Error('O servidor retornou uma resposta inválida.'); }
        if (!response.ok || data.sucesso === false) throw new Error(data.erro || 'Não foi possível concluir a operação.');
        return data;
    }
    function notify(message, error = false) {
        const box = document.getElementById('mensagem');
        box.textContent = message;
        box.className = `notice visible${error ? ' error' : ''}`;
        clearTimeout(timer);
        timer = setTimeout(() => { box.className = 'notice'; }, 4000);
    }
    function fillRooms() {
        const options = rooms.map(room => `<option value="${escapeHtml(room.id_turma)}">${escapeHtml(room.nome)} — ${escapeHtml(room.nome_instituicao)} (${escapeHtml(room.periodo || 'sem período')})</option>`).join('');
        document.getElementById('filtro-sala').innerHTML = '<option value="">Todas as salas</option>' + options;
        document.getElementById('sala-usuario').innerHTML = '<option value="">Selecione uma sala</option>' + options;
    }
    function fillInstitutions() {
        document.getElementById('instituicao-usuario').innerHTML = '<option value="">Selecione uma instituição</option>' + institutions.map(item => `<option value="${escapeHtml(item.id_instituicao)}">${escapeHtml(item.nome)}</option>`).join('');
    }
    function render() {
        const query = document.getElementById('busca-usuarios').value.trim().toLocaleLowerCase('pt-BR');
        const roomId = document.getElementById('filtro-sala').value;
        visibleUsers = users.filter(user => (!roomId || String(user.id_turma || '') === roomId) && `${user.nome || ''} ${user.email || ''} ${user.nome_turma || ''}`.toLocaleLowerCase('pt-BR').includes(query));
        const unique = new Map(users.map(user => [String(user.id_usuario), user]));
        let active = 0, inactive = 0;
        unique.forEach(user => String(user.status).toLowerCase() === 'inativo' ? inactive++ : active++);
        document.getElementById('total-usuarios').textContent = unique.size;
        document.getElementById('total-ativos').textContent = active;
        document.getElementById('total-inativos').textContent = inactive;
        document.getElementById('resultado-usuarios').textContent = `${visibleUsers.length} ${visibleUsers.length === 1 ? `${roleLabel} encontrado` : `${plural} encontrados`}`;
        if (!visibleUsers.length) {
            const hasFilters = Boolean(roomId || query);
            list.innerHTML = `<tr><td colspan="5" class="table-state">${hasFilters || users.length ? 'Nenhum resultado para os filtros selecionados.' : `Nenhum ${roleLabel} cadastrado ainda.`}</td></tr>`;
            return;
        }
        list.innerHTML = visibleUsers.map((user, index) => {
            const inactiveUser = String(user.status).toLowerCase() === 'inativo';
            return `<tr>
                <td data-label="Nome"><strong class="user-name">${escapeHtml(user.nome)}</strong></td>
                <td data-label="Sala">${escapeHtml(user.nome_turma || '—')}</td>
                <td data-label="E-mail" class="user-email">${escapeHtml(user.email)}</td>
                <td data-label="Status"><span class="status-badge${inactiveUser ? ' inactive' : ''}">${inactiveUser ? 'Inativo' : 'Ativo'}</span></td>
                <td data-label="Ações"><div class="row-actions"><button class="button button-toggle${inactiveUser ? ' reactivate' : ''}" type="button" data-toggle="${index}" aria-label="${inactiveUser ? 'Reativar' : 'Desativar'} ${escapeHtml(user.nome)}">${inactiveUser ? 'Reativar' : 'Desativar'}</button><button class="button button-edit" type="button" data-edit="${index}">Editar</button><button class="button button-delete" type="button" data-delete="${index}">Excluir</button></div></td>
            </tr>`;
        }).join('');
    }
    async function loadUsers() {
        list.innerHTML = `<tr><td colspan="5" class="table-state">Carregando ${plural}…</td></tr>`;
        const params = new URLSearchParams({ tipo_usuario: role });
        const roomId = document.getElementById('filtro-sala').value;
        if (roomId) params.set('id_turma', roomId);
        try {
            const data = await request(`${crud}listar&${params}`);
            users = data.dados || [];
            render();
        } catch (error) {
            list.innerHTML = `<tr><td colspan="5" class="table-state error-state">Não foi possível carregar ${plural}. Confira o servidor PHP e a migração de status dos usuários.</td></tr>`;
            document.getElementById('resultado-usuarios').textContent = 'Falha ao carregar';
        }
    }
    async function loadPageData() {
        try {
            const [roomData, institutionData] = await Promise.all([
                request(`${crud}turmas`), request(`${crud}instituicoes`)
            ]);
            rooms = roomData.dados || [];
            institutions = institutionData.dados || [];
            fillRooms(); fillInstitutions();
            await loadUsers();
        } catch (error) {
            list.innerHTML = `<tr><td colspan="5" class="table-state error-state">Não foi possível carregar as salas e instituições. Confira se há dados cadastrados.</td></tr>`;
        }
    }
    function openModal(user = null) {
        form.reset();
        const roomField = document.getElementById('campo-sala');
        const institutionField = document.getElementById('campo-instituicao');
        const roomSelect = document.getElementById('sala-usuario');
        const institutionSelect = document.getElementById('instituicao-usuario');
        roomField.hidden = !isStudent;
        institutionField.hidden = isStudent;
        roomSelect.required = isStudent;
        institutionSelect.required = !isStudent;
        document.getElementById('id-usuario').value = user?.id_usuario || '';
        document.getElementById('id-matricula').value = user?.id_matricula || '';
        document.getElementById('nome-usuario').value = user?.nome || '';
        document.getElementById('email-usuario').value = user?.email || '';
        document.getElementById('telefone-usuario').value = user?.telefone || '';
        document.getElementById('senha-usuario').value = '';
        document.getElementById('senha-usuario').required = !user;
        document.getElementById('senha-obrigatoria').hidden = Boolean(user);
        document.getElementById('senha-ajuda').textContent = user ? 'Deixe em branco para manter a senha atual.' : 'A senha será armazenada com proteção.';
        if (isStudent) roomSelect.value = user?.id_turma || '';
        else institutionSelect.value = user?.id_instituicao || (institutions[0]?.id_instituicao ?? '');
        document.getElementById('titulo-modal').textContent = user ? `Editar ${roleLabel}` : `Novo ${roleLabel}`;
        document.getElementById('salvar-usuario').textContent = user ? 'Salvar alterações' : `Cadastrar ${roleLabel}`;
        modal.showModal();
        document.getElementById('nome-usuario').focus();
    }
    function closeDialog(id) { document.getElementById(id).close(); }

    document.getElementById('novo-usuario').addEventListener('click', () => openModal());
    document.getElementById('busca-usuarios').addEventListener('input', render);
    document.getElementById('filtro-sala').addEventListener('change', loadUsers);
    document.querySelectorAll('[data-close]').forEach(button => button.addEventListener('click', () => closeDialog(button.dataset.close)));
    document.querySelectorAll('dialog').forEach(dialog => dialog.addEventListener('click', event => { if (event.target === dialog) dialog.close(); }));
    document.getElementById('telefone-usuario').addEventListener('input', event => {
        const digits = event.target.value.replace(/\D/g, '').slice(0, 11);
        if (digits.length > 10) event.target.value = digits.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, '($1) $2-$3');
        else if (digits.length > 6) event.target.value = digits.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
        else if (digits.length > 2) event.target.value = digits.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
        else event.target.value = digits;
    });

    form.addEventListener('submit', async event => {
        event.preventDefault();
        if (!form.reportValidity()) return;
        const payload = Object.fromEntries(new FormData(form));
        const id = payload.id_usuario;
        payload.tipo_usuario = role;
        const button = document.getElementById('salvar-usuario'); button.disabled = true;
        let endpoint;
        endpoint = `${crud}${id ? 'editar' : 'criar'}`;
        try {
            await request(endpoint, { method:'POST', headers:{ 'Content-Type':'application/json' }, body:JSON.stringify(payload) });
            modal.close(); notify(`${roleLabel[0].toUpperCase()}${roleLabel.slice(1)} ${id ? 'atualizado' : 'cadastrado'} com sucesso.`);
            await loadUsers();
        } catch (error) { notify(error.message, true); }
        finally { button.disabled = false; }
    });
    list.addEventListener('click', async event => {
        const toggle = event.target.closest('[data-toggle]');
        const edit = event.target.closest('[data-edit]');
        const remove = event.target.closest('[data-delete]');
        if (toggle) {
            const user = visibleUsers[Number(toggle.dataset.toggle)];
            if (!user) return;
            const next = String(user.status).toLowerCase() === 'inativo' ? 'ativo' : 'inativo';
            toggle.disabled = true;
            try {
                await request(`${crud}status`, { method:'POST', headers:{ 'Content-Type':'application/json' }, body:JSON.stringify({ id_usuario:user.id_usuario, tipo_usuario:role, status:next }) });
                notify(`${roleLabel[0].toUpperCase()}${roleLabel.slice(1)} ${next === 'ativo' ? 'reativado' : 'desativado'}.`);
                await loadUsers();
            } catch (error) { notify(error.message, true); toggle.disabled = false; }
        }
        if (edit) {
            const user = visibleUsers[Number(edit.dataset.edit)];
            if (user) openModal(user);
        }
        if (remove) {
            deletingUser = visibleUsers[Number(remove.dataset.delete)];
            if (!deletingUser) return;
            document.getElementById('titulo-excluir').textContent = `Excluir ${roleLabel}?`;
            document.getElementById('texto-excluir').textContent = `Tem certeza de que deseja excluir o cadastro de ${deletingUser.nome}? Essa ação não pode ser desfeita.`;
            document.getElementById('aviso-excluir').textContent = isStudent
                ? 'As matrículas e entregas de atividades ligadas a este aluno também serão apagadas.'
                : 'As turmas, atividades criadas por este professor e entregas ligadas a elas também serão apagadas.';
            document.getElementById('confirmar-exclusao').textContent = `Excluir ${roleLabel}`;
            document.getElementById('modal-excluir').showModal();
        }
    });
    document.getElementById('form-excluir').addEventListener('submit', async event => {
        event.preventDefault(); if (!deletingUser) return;
        const button = document.getElementById('confirmar-exclusao'); button.disabled = true;
        try {
            await request(`${crud}deletar`, { method:'POST', headers:{ 'Content-Type':'application/json' }, body:JSON.stringify({ id_usuario:deletingUser.id_usuario }) });
            closeDialog('modal-excluir'); notify(`${roleLabel[0].toUpperCase()}${roleLabel.slice(1)} excluído com sucesso.`); deletingUser = null; await loadUsers();
        } catch (error) { notify(error.message, true); }
        finally { button.disabled = false; }
    });
    loadPageData();
})();

</script>
</body>
</html>
