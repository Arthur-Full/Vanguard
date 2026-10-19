<?php
require_once __DIR__ . '/../backend/config/auth.php';
exigirPerfil(['admin', 'coordenador']);
if (isset($_GET['api'])) {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $pdo = getConnection();
        $payload = json_decode(file_get_contents('php://input'), true) ?? [];
        $api = (string)$_GET['api'];
        if ($api === 'listar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            echo json_encode(['sucesso' => true, 'dados' => $pdo->query('SELECT id_instituicao,nome,cidade,cnpj FROM instituicoes ORDER BY nome')->fetchAll()]);
        } elseif (in_array($api, ['criar', 'editar'], true) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($payload['nome'] ?? '');
            $cidade = trim($payload['cidade'] ?? '');
            $cnpj = preg_replace('/\D+/', '', $payload['cnpj'] ?? '');
            $id = filter_var($payload['id_instituicao'] ?? null, FILTER_VALIDATE_INT);
            if ($nome === '' || strlen($cnpj) !== 14 || ($api === 'editar' && !$id)) throw new InvalidArgumentException('Informe o nome e um CNPJ válido com 14 números.');
            if ($api === 'criar') {
                $stmt = $pdo->prepare('INSERT INTO instituicoes (nome,cidade,cnpj) VALUES (:nome,:cidade,:cnpj)');
                $stmt->execute([':nome' => $nome, ':cidade' => $cidade ?: null, ':cnpj' => $cnpj]);
            } else {
                $stmt = $pdo->prepare('UPDATE instituicoes SET nome=:nome,cidade=:cidade,cnpj=:cnpj WHERE id_instituicao=:id');
                $stmt->execute([':nome' => $nome, ':cidade' => $cidade ?: null, ':cnpj' => $cnpj, ':id' => $id]);
            }
            echo json_encode(['sucesso' => true]);
        } elseif ($api === 'deletar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_var($payload['id_instituicao'] ?? null, FILTER_VALIDATE_INT);
            if (!$id) throw new InvalidArgumentException('Escola inválida.');
            $stmt = $pdo->prepare('DELETE FROM instituicoes WHERE id_instituicao=:id');
            $stmt->execute([':id' => $id]);
            echo json_encode(['sucesso' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['sucesso' => false, 'erro' => 'Operação inválida.']);
        }
    } catch (InvalidArgumentException $exception) {
        http_response_code(400);
        echo json_encode(['sucesso' => false, 'erro' => $exception->getMessage()]);
    } catch (PDOException $exception) {
        http_response_code($exception->getCode() === '23000' ? 409 : 500);
        echo json_encode(['sucesso' => false, 'erro' => $exception->getCode() === '23000' ? 'O CNPJ já está cadastrado ou a escola possui vínculos.' : 'Não foi possível acessar os dados da escola.']);
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
    <title>Escolas | Coordenação</title>
    <link rel="stylesheet" href="css/escolas-adm.css">
    <link rel="stylesheet" href="css/aside-adm.css">
</head>

<body>
    <div class="page-shell">
        <aside class="sidebar">
            <a class="brand" href="dash-adm.php" aria-label="Vanguard, painel da coordenação">
                <img src="imagem/SEGUNDA_LOGO.png" alt="Vanguard — Portal Educacional">
            </a>
            <nav class="side-nav" aria-label="Navegação principal">
                <a class="nav-item" href="dash-adm.php"><img src="imagem/casa-branca.png" alt=""><span>Visão geral</span></a>
                <a class="nav-item active" href="escolas-adm.php" aria-current="page"><img src="imagem/escolas-icon.png" alt=""><span>Escolas</span></a>
                <a class="nav-item" href="salas-adm.php"><img src="imagem/classroom.png" alt=""><span>Salas</span></a>
                <a class="nav-item" href="professores-adm.php"><img src="imagem/professor.png" alt=""><span>Professores</span></a>
                <a class="nav-item" href="calendario-adm.php"><img src="imagem/calendario-verde.png" alt=""><span>Calendário</span></a>
                <a class="nav-item" href="alunos-adm.php"><img src="imagem/reading.png" alt=""><span>Alunos</span></a>
            </nav>
            <div class="sidebar-decoration" aria-hidden="true">✧</div>
        </aside>

        <div class="main-column">
            <header class="topbar"><a class="topbar-logout" href="sair.php" aria-label="Sair e voltar ao login" title="Sair"><img src="imagem/exit.png" alt=""></a></header>
            <main class="content-area">
                <div class="page-heading">
                    <div>
                        <p class="eyebrow">PAINEL DA COORDENAÇÃO</p>
                        <h1>Escolas</h1>
                        <p class="subtitle">Gerencie as instituições cadastradas no portal.</p>
                    </div>
                    <div class="school-total"><strong id="total-escolas">—</strong><span>instituições</span></div>
                </div>

                <section class="toolbar" aria-label="Ferramentas de escolas">
                    <label class="search-box" for="busca-escolas">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="10.5" cy="10.5" r="6.5"></circle>
                            <path d="m15.5 15.5 5 5"></path>
                        </svg>
                        <input id="busca-escolas" type="search" placeholder="Buscar escola (nome ou cidade)..." autocomplete="off">
                    </label>
                    <button class="button button-primary" id="nova-escola" type="button"><span aria-hidden="true">＋</span> Nova escola</button>
                </section>

                <div id="mensagem" class="notice" role="status" aria-live="polite"></div>
                <section class="table-card" aria-label="Escolas cadastradas">
                    <div class="table-scroll">
                        <table>
                            <thead>
                                <tr>
                                    <th>Escola</th>
                                    <th>Cidade</th>
                                    <th>CNPJ</th>
                                    <th><span class="sr-only">Ações</span></th>
                                </tr>
                            </thead>
                            <tbody id="lista-escolas">
                                <tr>
                                    <td class="table-state" colspan="4">Carregando escolas…</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <footer class="table-footer"><span id="resultado-escolas">Carregando instituições</span><span class="live-indicator"><i></i> Dados atualizados</span></footer>
                </section>
            </main>
        </div>
    </div>

    <dialog class="school-dialog" id="modal-escola" aria-labelledby="titulo-modal">
        <form id="form-escola">
            <div class="dialog-heading">
                <div>
                    <p class="eyebrow">INSTITUIÇÃO</p>
                    <h2 id="titulo-modal">Nova escola</h2>
                </div><button class="icon-close" type="button" id="fechar-modal" aria-label="Fechar">×</button>
            </div>
            <input type="hidden" id="id-escola" name="id_instituicao">
            <label for="nome-escola">Nome da escola <span>*</span></label>
            <input id="nome-escola" name="nome" type="text" maxlength="100" placeholder="Ex.: Escola Técnica Central" required>
            <label for="cidade-escola">Cidade</label>
            <input id="cidade-escola" name="cidade" type="text" maxlength="100" placeholder="Ex.: São Paulo">
            <label for="cnpj-escola">CNPJ <span>*</span></label>
            <input id="cnpj-escola" name="cnpj" type="text" inputmode="numeric" maxlength="18" placeholder="00.000.000/0000-00" required>
            <p class="field-hint">Nome e CNPJ são obrigatórios.</p>
            <div class="dialog-actions"><button class="button button-muted" type="button" id="cancelar-modal">Cancelar</button><button class="button button-primary" id="salvar-escola" type="submit">Salvar escola</button></div>
        </form>
    </dialog>
    <script>
        (() => {
            const api = 'escolas-adm.php?api=';
            const tbody = document.getElementById('lista-escolas');
            const modal = document.getElementById('modal-escola');
            const form = document.getElementById('form-escola');
            const search = document.getElementById('busca-escolas');
            const notice = document.getElementById('mensagem');
            let schools = [];
            let noticeTimer;

            function notify(message, error = false) {
                notice.textContent = message;
                notice.className = `notice visible${error ? ' error' : ''}`;
                clearTimeout(noticeTimer);
                noticeTimer = setTimeout(() => {
                    notice.className = 'notice';
                }, 4200);
            }

            async function request(url, options) {
                const response = await fetch(url, options);
                let result;
                try {
                    result = await response.json();
                } catch {
                    throw new Error('O servidor retornou uma resposta inválida.');
                }
                if (!response.ok || result.sucesso === false) throw new Error(result.erro || 'Não foi possível concluir a operação.');
                return result;
            }

            async function loadSchools() {
                tbody.innerHTML = '<tr><td class="table-state" colspan="4">Carregando escolas…</td></tr>';
                try {
                    const institutions = await request(`${api}listar`);
                    schools = institutions.dados || [];
                    document.getElementById('total-escolas').textContent = schools.length;
                    renderSchools();
                } catch (error) {
                    tbody.innerHTML = '<tr><td class="table-state error-state" colspan="4">Não foi possível carregar as escolas. Verifique se o servidor PHP está ativo.</td></tr>';
                    document.getElementById('total-escolas').textContent = '—';
                    document.getElementById('resultado-escolas').textContent = 'Falha ao carregar';
                }
            }

            function renderSchools() {
                const query = search.value.trim().toLocaleLowerCase('pt-BR');
                const visible = schools.filter(school => `${school.nome || ''} ${school.cidade || ''} ${school.cnpj || ''}`.toLocaleLowerCase('pt-BR').includes(query));
                document.getElementById('resultado-escolas').textContent = `${visible.length} ${visible.length === 1 ? 'instituição encontrada' : 'instituições encontradas'}`;
                if (!visible.length) {
                    const empty = schools.length ? 'Nenhuma escola corresponde à busca.' : 'Ainda não há escolas cadastradas. Use “Nova escola” para adicionar a primeira.';
                    tbody.innerHTML = `<tr><td class="table-state" colspan="4">${empty}</td></tr>`;
                    return;
                }
                tbody.innerHTML = visible.map(school => `<tr>
                <td data-label="Escola"><div class="school-name"><span class="school-mark" aria-hidden="true">${escapeHtml((school.nome || 'E').trim().charAt(0).toUpperCase())}</span><strong>${escapeHtml(school.nome || 'Sem nome')}</strong></div></td>
                <td data-label="Cidade">${escapeHtml(school.cidade || '—')}</td>
                <td data-label="CNPJ">${escapeHtml(school.cnpj || 'Não informado')}</td>
                <td data-label="Ações"><div class="row-actions"><button class="button button-edit" type="button" data-edit="${escapeHtml(school.id_instituicao)}">Editar</button><button class="button button-delete" type="button" data-delete="${escapeHtml(school.id_instituicao)}">Excluir</button></div></td>
            </tr>`).join('');
            }

            function escapeHtml(value) {
                return String(value).replace(/[&<>"']/g, char => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                } [char]));
            }

            function openModal(school = null) {
                form.reset();
                document.getElementById('cnpj-escola').setCustomValidity('');
                document.getElementById('id-escola').value = school?.id_instituicao || '';
                document.getElementById('nome-escola').value = school?.nome || '';
                document.getElementById('cidade-escola').value = school?.cidade || '';
                document.getElementById('cnpj-escola').value = school?.cnpj || '';
                document.getElementById('titulo-modal').textContent = school ? 'Editar escola' : 'Nova escola';
                document.getElementById('salvar-escola').textContent = school ? 'Salvar alterações' : 'Salvar escola';
                modal.showModal();
                document.getElementById('nome-escola').focus();
            }

            document.getElementById('nova-escola').addEventListener('click', () => openModal());
            document.getElementById('fechar-modal').addEventListener('click', () => modal.close());
            document.getElementById('cancelar-modal').addEventListener('click', () => modal.close());
            modal.addEventListener('click', event => {
                if (event.target === modal) modal.close();
            });
            search.addEventListener('input', renderSchools);
            document.getElementById('cnpj-escola').addEventListener('input', event => {
                const digits = event.target.value.replace(/\D/g, '').slice(0, 14);
                let formatted = digits;
                if (digits.length > 2) formatted = `${digits.slice(0, 2)}.${digits.slice(2)}`;
                if (digits.length > 5) formatted = `${digits.slice(0, 2)}.${digits.slice(2, 5)}.${digits.slice(5)}`;
                if (digits.length > 8) formatted = `${digits.slice(0, 2)}.${digits.slice(2, 5)}.${digits.slice(5, 8)}/${digits.slice(8)}`;
                if (digits.length > 12) formatted = `${digits.slice(0, 2)}.${digits.slice(2, 5)}.${digits.slice(5, 8)}/${digits.slice(8, 12)}-${digits.slice(12)}`;
                event.target.value = formatted;
                event.target.setCustomValidity(digits.length > 0 && digits.length !== 14 ? 'Informe os 14 números do CNPJ.' : '');
            });

            form.addEventListener('submit', async event => {
                event.preventDefault();
                if (!form.reportValidity()) return;
                const id = document.getElementById('id-escola').value;
                const payload = Object.fromEntries(new FormData(form));
                const button = document.getElementById('salvar-escola');
                button.disabled = true;
                try {
                    await request(`${api}${id ? 'editar' : 'criar'}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });
                    modal.close();
                    notify(id ? 'Escola atualizada com sucesso.' : 'Escola cadastrada com sucesso.');
                    await loadSchools();
                } catch (error) {
                    notify(error.message, true);
                } finally {
                    button.disabled = false;
                }
            });

            tbody.addEventListener('click', async event => {
                const editButton = event.target.closest('[data-edit]');
                const deleteButton = event.target.closest('[data-delete]');
                if (editButton) {
                    const school = schools.find(item => String(item.id_instituicao) === editButton.dataset.edit);
                    if (school) openModal(school);
                }
                if (deleteButton) {
                    const school = schools.find(item => String(item.id_instituicao) === deleteButton.dataset.delete);
                    if (!school || !window.confirm(`Excluir a escola “${school.nome}”?`)) return;
                    try {
                        await request(`${api}deletar`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id_instituicao: school.id_instituicao
                            })
                        });
                        notify('Escola excluída com sucesso.');
                        await loadSchools();
                    } catch (error) {
                        notify(error.message, true);
                    }
                }
            });

            loadSchools();
        })();
    </script>
</body>

</html>