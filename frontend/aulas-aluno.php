<?php
require_once __DIR__ . '/../backend/config/auth.php';
exigirPerfil(['aluno']);
$usuario=usuarioAtual();$pdo=getConnection();$idAluno=(int)$usuario['id_usuario'];
$esc=static fn($value)=>htmlspecialchars((string)$value,ENT_QUOTES,'UTF-8');$nomeUsuario=$esc($usuario['nome']);
$stmt=$pdo->prepare("SELECT t.id_turma,t.nome AS turma,GROUP_CONCAT(DISTINCT d.nome ORDER BY d.nome SEPARATOR ', ') AS disciplina FROM matriculas m JOIN turmas t ON t.id_turma=m.id_turma LEFT JOIN professor_turmas pt ON pt.id_turma=t.id_turma LEFT JOIN disciplinas d ON d.id_disciplina=pt.id_disciplina WHERE m.id_aluno=:aluno AND m.status='ativa' AND t.status='ativa' AND t.id_instituicao=:instituicao GROUP BY t.id_turma,t.nome ORDER BY t.nome");
$stmt->execute([':aluno'=>$idAluno,':instituicao'=>(int)$usuario['id_instituicao']]);$turmas=$stmt->fetchAll();
$idTurma=filter_var($_GET['turma']??($turmas[0]['id_turma']??null),FILTER_VALIDATE_INT);$turmaAtual=null;foreach($turmas as $turma)if((int)$turma['id_turma']===(int)$idTurma){$turmaAtual=$turma;break;}if(!$turmaAtual&&$turmas){$turmaAtual=$turmas[0];$idTurma=(int)$turmaAtual['id_turma'];}
$idTurmaAtual=$turmaAtual?(int)$turmaAtual['id_turma']:null;
$_SESSION['csrf_aulas']??=bin2hex(random_bytes(32));$csrfAulas=$_SESSION['csrf_aulas'];$flashAulas=$_SESSION['flash_aulas']??null;unset($_SESSION['flash_aulas']);
$urlRetorno='aulas-aluno.php'.($idTurmaAtual?'?turma='.$idTurmaAtual:'');
if($_SERVER['REQUEST_METHOD']==='POST'){
    $acao=$_POST['acao']??'';
    if(!hash_equals($csrfAulas,(string)($_POST['csrf']??''))){$flashAulas=['tipo'=>'erro','texto'=>'A sessão expirou. Atualize a página e tente novamente.'];}
    elseif($acao==='salvar_anotacao'){
        $idAnotacao=filter_var($_POST['id_anotacao']??null,FILTER_VALIDATE_INT);$titulo=trim((string)($_POST['titulo']??''));$conteudo=trim((string)($_POST['conteudo']??''));
        if($titulo===''||mb_strlen($titulo)>120||$conteudo===''){$flashAulas=['tipo'=>'erro','texto'=>'Preencha um título e o conteúdo da anotação.'];}
        elseif($idAnotacao){$stmt=$pdo->prepare('UPDATE anotacoes SET titulo=:titulo,conteudo=:conteudo,id_turma=:turma WHERE id_anotacao=:id AND id_usuario=:aluno');$stmt->execute([':titulo'=>$titulo,':conteudo'=>$conteudo,':turma'=>$idTurmaAtual,':id'=>$idAnotacao,':aluno'=>$idAluno]);$flashAulas=['tipo'=>'sucesso','texto'=>$stmt->rowCount()?'Anotação atualizada.':'Anotação salva.'];}
        else{$stmt=$pdo->prepare('INSERT INTO anotacoes (id_usuario,id_turma,titulo,conteudo) VALUES (:aluno,:turma,:titulo,:conteudo)');$stmt->execute([':aluno'=>$idAluno,':turma'=>$idTurmaAtual,':titulo'=>$titulo,':conteudo'=>$conteudo]);$flashAulas=['tipo'=>'sucesso','texto'=>'Anotação adicionada.'];}
    }elseif($acao==='excluir_anotacao'){
        $stmt=$pdo->prepare('DELETE FROM anotacoes WHERE id_anotacao=:id AND id_usuario=:aluno');$stmt->execute([':id'=>filter_var($_POST['id_anotacao']??null,FILTER_VALIDATE_INT),':aluno'=>$idAluno]);$flashAulas=['tipo'=>'sucesso','texto'=>'Anotação removida.'];
    }elseif($acao==='enviar_anexo'){
        $arquivo=$_FILES['anexo']??null;$mimeValido=['application/pdf'=>'pdf','application/msword'=>'doc','application/vnd.openxmlformats-officedocument.wordprocessingml.document'=>'docx','image/jpeg'=>'jpg','image/png'=>'png'];
        if(!$arquivo||$arquivo['error']!==UPLOAD_ERR_OK){$flashAulas=['tipo'=>'erro','texto'=>'Escolha um arquivo para anexar.'];}
        elseif($arquivo['size']>10*1024*1024){$flashAulas=['tipo'=>'erro','texto'=>'O anexo deve ter no máximo 10 MB.'];}
        else{$finfo=new finfo(FILEINFO_MIME_TYPE);$mime=$finfo->file($arquivo['tmp_name']);$ext=strtolower(pathinfo($arquivo['name'],PATHINFO_EXTENSION));
            if(!isset($mimeValido[$mime])||$mimeValido[$mime]!==$ext){$flashAulas=['tipo'=>'erro','texto'=>'Formato não permitido. Use PDF, DOC, DOCX, JPG ou PNG.'];}
            else{$diretorio=__DIR__.'/uploads/anexos';if(!is_dir($diretorio)&&!mkdir($diretorio,0755,true)&&!is_dir($diretorio)){$flashAulas=['tipo'=>'erro','texto'=>'Não foi possível preparar o armazenamento dos anexos.'];}
                else{$nomeSeguro=preg_replace('/[^\pL\pN._ -]/u','_',basename($arquivo['name'])) ?: 'anexo.'.$ext;$nomeArmazenado=$idAluno.'_'.bin2hex(random_bytes(16)).'.'.$ext;
                    if(move_uploaded_file($arquivo['tmp_name'],$diretorio.'/'.$nomeArmazenado)){$caminho='uploads/anexos/'.$nomeArmazenado;$stmt=$pdo->prepare('INSERT INTO anexos_aluno (id_usuario,id_turma,nome_original,caminho,tipo_mime,tamanho_bytes) VALUES (:aluno,:turma,:nome,:caminho,:mime,:tamanho)');$stmt->execute([':aluno'=>$idAluno,':turma'=>$idTurmaAtual,':nome'=>$nomeSeguro,':caminho'=>$caminho,':mime'=>$mime,':tamanho'=>(int)$arquivo['size']]);$flashAulas=['tipo'=>'sucesso','texto'=>'Anexo enviado.'];}
                    else{$flashAulas=['tipo'=>'erro','texto'=>'Não foi possível salvar o arquivo. Tente novamente.'];}
                }
            }
        }
    }elseif($acao==='excluir_anexo'){
        $stmt=$pdo->prepare('SELECT caminho FROM anexos_aluno WHERE id_anexo=:id AND id_usuario=:aluno');$stmt->execute([':id'=>filter_var($_POST['id_anexo']??null,FILTER_VALIDATE_INT),':aluno'=>$idAluno]);$caminho=$stmt->fetchColumn();
        if($caminho){$del=$pdo->prepare('DELETE FROM anexos_aluno WHERE id_anexo=:id AND id_usuario=:aluno');$del->execute([':id'=>(int)$_POST['id_anexo'],':aluno'=>$idAluno]);if(str_starts_with($caminho,'uploads/anexos/')){@unlink(__DIR__.'/'.$caminho);}$flashAulas=['tipo'=>'sucesso','texto'=>'Anexo removido.'];}
    }
    $_SESSION['flash_aulas']=$flashAulas;header('Location: '.$urlRetorno);exit;
}
$filtroTurmaSql=$idTurmaAtual?' AND (id_turma=:turma OR id_turma IS NULL)':' AND id_turma IS NULL';
$stmt=$pdo->prepare('SELECT id_anotacao,titulo,conteudo,data_criacao FROM anotacoes WHERE id_usuario=:aluno'.$filtroTurmaSql.' ORDER BY data_criacao DESC,id_anotacao DESC');$paramsAnotacoes=[':aluno'=>$idAluno];if($idTurmaAtual)$paramsAnotacoes[':turma']=$idTurmaAtual;$stmt->execute($paramsAnotacoes);$anotacoesAluno=$stmt->fetchAll();
$stmt=$pdo->prepare('SELECT id_anexo,nome_original,caminho,tamanho_bytes,data_envio FROM anexos_aluno WHERE id_usuario=:aluno'.$filtroTurmaSql.' ORDER BY data_envio DESC,id_anexo DESC');$stmt->execute($paramsAnotacoes);$anexosAluno=$stmt->fetchAll();
$notasAluno=[];if($turmaAtual){$stmt=$pdo->prepare('SELECT tipo,titulo,nota,data_avaliacao FROM avaliacoes_alunos WHERE id_aluno=:aluno AND id_turma=:turma ORDER BY data_avaliacao DESC,id_avaliacao DESC');$stmt->execute([':aluno'=>$idAluno,':turma'=>(int)$turmaAtual['id_turma']]);$notasAluno=$stmt->fetchAll();$stmt=$pdo->prepare("SELECT 'atividade' AS tipo,a.titulo,e.nota,DATE(e.avaliado_em) AS data_avaliacao FROM entregas_atividades e JOIN atividades a ON a.id_atividade=e.id_atividade WHERE e.id_aluno=:aluno AND a.id_turma=:turma AND e.nota IS NOT NULL ORDER BY e.avaliado_em DESC");$stmt->execute([':aluno'=>$idAluno,':turma'=>(int)$turmaAtual['id_turma']]);$notasAluno=array_merge($notasAluno,$stmt->fetchAll());usort($notasAluno,static fn($a,$b)=>strcmp((string)$b['data_avaliacao'],(string)$a['data_avaliacao']));}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aulas do aluno | Vanguard</title>
    <link rel="stylesheet" href="css/aulas-aluno.css?v=20261018-notes-dialog">
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
                <a href="calendario-aluno.php" class="nav-item">
                    <img src="imagem/calendario-01.png" alt="" aria-hidden="true">
                    <span>Calendário</span>
                </a>
                <a href="aulas-aluno.php" class="nav-item active" aria-current="page">
                    <img src="imagem/livro_01.png" alt="" aria-hidden="true">
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
                <div class="content-panel">
                    <?php if($flashAulas): ?><p class="actions-feedback <?= $esc($flashAulas['tipo']) ?>" role="status"><?= $esc($flashAulas['texto']) ?></p><?php endif; ?>
                    <h2 class="section-heading">Salas</h2>
                    <div class="room-tabs" aria-label="Salas">
<?php foreach($turmas as $turma): ?><a class="tab" style="color:inherit;text-decoration:none" href="?turma=<?= (int)$turma['id_turma'] ?>" <?= (int)$turma['id_turma']===(int)$idTurma?'aria-current="page"':'' ?>><?= $esc($turma['turma']) ?> <span><?= $esc($turma['disciplina']??'') ?></span></a><?php endforeach; ?>
<?php if(!$turmas): ?><p>Você ainda não possui matrícula ativa em uma sala.</p><?php endif; ?>
</div>

                    <section class="notes-panel" aria-labelledby="anotacoes-title">
                        <h2 id="anotacoes-title"><span>Anotações</span><img src="imagem/anotacoes.png" alt="" aria-hidden="true"></h2>
                        <div class="notes-grid">                            <button type="button" class="note-card add-card" id="abrir-anotacao" aria-label="Adicionar anotação"><span class="plus-sign">+</span></button>
                            <?php foreach($anotacoesAluno as $anotacao): ?>
                                <article class="note-card saved-note">
                                    <strong><?= $esc($anotacao['titulo']) ?></strong>
                                    <p><?= nl2br($esc($anotacao['conteudo'])) ?></p>
                                    <small><?= $esc(date('d/m/Y',strtotime($anotacao['data_criacao']))) ?></small>
                                    <div class="note-actions"><button type="button" class="note-edit" data-id="<?= (int)$anotacao['id_anotacao'] ?>" data-titulo="<?= $esc($anotacao['titulo']) ?>" data-conteudo="<?= $esc($anotacao['conteudo']) ?>">Editar</button><form method="post" onsubmit="return confirm('Excluir esta anotação?')"><input type="hidden" name="csrf" value="<?= $esc($csrfAulas) ?>"><input type="hidden" name="acao" value="excluir_anotacao"><input type="hidden" name="id_anotacao" value="<?= (int)$anotacao['id_anotacao'] ?>"><button type="submit" class="note-delete">Excluir</button></form></div>
                                </article>
                            <?php endforeach; ?>
                            <?php for($i=count($anotacoesAluno);$i<9;$i++): ?><article class="note-card empty-card" aria-hidden="true"></article><?php endfor; ?>
                        </div>
                        <dialog class="note-dialog" id="dialog-anotacao">
                            <form method="post" class="note-form">
                                <h3 id="titulo-dialog-anotacao">Nova anotação</h3>
                                <input type="hidden" name="csrf" value="<?= $esc($csrfAulas) ?>">
                                <input type="hidden" name="acao" value="salvar_anotacao">
                                <input type="hidden" name="id_anotacao" id="id-anotacao">
                                <label for="titulo-anotacao">Título</label><input id="titulo-anotacao" name="titulo" maxlength="120" required>
                                <label for="conteudo-anotacao">Anotação</label><textarea id="conteudo-anotacao" name="conteudo" rows="6" required></textarea>
                                <div class="note-form-actions"><button type="button" class="dialog-cancelar">Cancelar</button><button type="submit">Salvar anotação</button></div>
                            </form>
                        </dialog>
                    </section>

<section class="notes-panel" aria-labelledby="notas-title">
    <h2 id="notas-title"><span>Notas<?= $turmaAtual?' · '.$esc($turmaAtual['turma']):'' ?></span><img src="imagem/notas-verde.png" alt="" aria-hidden="true"></h2>
    <div class="notes-grid">
        <?php if(!$notasAluno): ?><article class="note-card empty-card"><p>Ainda não há notas nesta sala.</p></article><?php else: foreach($notasAluno as $nota): ?><article class="note-card"><p><strong><?= $esc(ucfirst($nota['tipo'])) ?></strong><br><?= $esc($nota['titulo']) ?><br>Nota: <?= number_format((float)$nota['nota'],1,',','.') ?>/10<br><?= $nota['data_avaliacao']?$esc(date('d/m/Y',strtotime($nota['data_avaliacao']))):'' ?></p></article><?php endforeach; endif; ?>
    </div>
</section>
                    <section class="attachments-panel" aria-labelledby="anexos-title">
                        <h2 id="anexos-title"><span>Anexos</span><img src="imagem/anexo.png" alt="" aria-hidden="true"></h2>
                        <form method="post" enctype="multipart/form-data" id="form-anexo"><input type="hidden" name="csrf" value="<?= $esc($csrfAulas) ?>"><input type="hidden" name="acao" value="enviar_anexo"><input type="file" name="anexo" id="arquivo-anexo" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" hidden required><button class="add-attachment" type="button" id="abrir-anexo">Adicionar anexo</button><small class="attachment-help">PDF, DOC, DOCX, JPG ou PNG · até 10 MB</small></form>
                        <?php if($anexosAluno): ?><ul class="attachment-list"><?php foreach($anexosAluno as $anexo): ?><li class="attachment-item"><a href="anexo-aluno.php?id=<?= (int)$anexo['id_anexo'] ?>"><?= $esc($anexo['nome_original']) ?><small><?= number_format($anexo['tamanho_bytes']/1024,0,',','.') ?> KB</small></a><form method="post" onsubmit="return confirm('Remover este anexo?')"><input type="hidden" name="csrf" value="<?= $esc($csrfAulas) ?>"><input type="hidden" name="acao" value="excluir_anexo"><input type="hidden" name="id_anexo" value="<?= (int)$anexo['id_anexo'] ?>"><button type="submit" aria-label="Remover <?= $esc($anexo['nome_original']) ?>">×</button></form></li><?php endforeach; ?></ul><?php else: ?><p class="attachments-empty">Nenhum anexo enviado nesta sala ainda.</p><?php endif; ?>
                    </section>
                </div>
            </main>
        </div>
    </div>
<script>
(() => {
    const dialog = document.getElementById('dialog-anotacao');
    const abrir = document.getElementById('abrir-anotacao');
    const titulo = document.getElementById('titulo-anotacao');
    const conteudo = document.getElementById('conteudo-anotacao');
    const id = document.getElementById('id-anotacao');
    const cabecalho = document.getElementById('titulo-dialog-anotacao');
    const abrirDialog = (nota = null) => {
        id.value = nota?.id || '';
        titulo.value = nota?.titulo || '';
        conteudo.value = nota?.conteudo || '';
        cabecalho.textContent = nota ? 'Editar anotação' : 'Nova anotação';
        dialog.showModal();
        titulo.focus();
    };
    abrir?.addEventListener('click', () => abrirDialog());
    document.querySelectorAll('.note-edit').forEach(botao => botao.addEventListener('click', () => abrirDialog({id:botao.dataset.id,titulo:botao.dataset.titulo,conteudo:botao.dataset.conteudo})));
    document.querySelector('.dialog-cancelar')?.addEventListener('click', () => dialog.close());
    dialog?.addEventListener('click', evento => { if (evento.target === dialog) dialog.close(); });
    const seletorAnexo = document.getElementById('arquivo-anexo');
    document.getElementById('abrir-anexo')?.addEventListener('click', () => seletorAnexo.click());
    seletorAnexo?.addEventListener('change', () => { if (seletorAnexo.files.length) document.getElementById('form-anexo').submit(); });
})();
</script>
</body>
</html>
