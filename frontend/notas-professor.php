<?php
require_once __DIR__ . '/../backend/config/auth.php';
exigirPerfil(['professor']);
$usuario=usuarioAtual();
$pdo=getConnection();
$idProfessor=(int)$usuario['id_usuario'];
$esc=static fn($value)=>htmlspecialchars((string)$value,ENT_QUOTES,'UTF-8');
$stmt=$pdo->prepare("SELECT t.id_turma,t.nome AS turma,GROUP_CONCAT(DISTINCT d.nome ORDER BY d.nome SEPARATOR ', ') AS disciplina FROM turmas t LEFT JOIN professor_turmas pt ON pt.id_turma=t.id_turma AND pt.id_professor=:professor LEFT JOIN disciplinas d ON d.id_disciplina=pt.id_disciplina WHERE t.id_instituicao=:instituicao AND t.status='ativa' GROUP BY t.id_turma,t.nome ORDER BY t.nome");
$stmt->execute([':professor'=>$idProfessor,':instituicao'=>(int)$usuario['id_instituicao']]);
$turmas=$stmt->fetchAll();
$resumosTurma=[];$stmt=$pdo->prepare("SELECT a.id_turma,a.id_aluno,AVG(a.nota) AS media FROM avaliacoes_alunos a JOIN matriculas m ON m.id_usuario=a.id_aluno AND m.id_turma=a.id_turma AND m.status='ativa' JOIN usuarios u ON u.id_usuario=a.id_aluno AND u.status='ativo' WHERE a.id_professor=:professor GROUP BY a.id_turma,a.id_aluno");$stmt->execute([':professor'=>$idProfessor]);foreach($stmt->fetchAll() as $resumo){$chave=(int)$resumo['id_turma'];$resumosTurma[$chave]['soma']=($resumosTurma[$chave]['soma']??0)+(float)$resumo['media'];$resumosTurma[$chave]['alunos']=($resumosTurma[$chave]['alunos']??0)+1;if((float)$resumo['media']<7)$resumosTurma[$chave]['recuperacao']=($resumosTurma[$chave]['recuperacao']??0)+1;}
$idTurma=filter_var($_GET['turma']??($turmas[0]['id_turma']??null),FILTER_VALIDATE_INT);
$turmaAtual=null;
foreach($turmas as $turma) if((int)$turma['id_turma']===(int)$idTurma){$turmaAtual=$turma;break;}
if(!$turmaAtual && $turmas){$turmaAtual=$turmas[0];$idTurma=(int)$turmaAtual['id_turma'];}
$erro='';
if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['acao']??'')==='nota'){
    $idTurmaPost=filter_var($_POST['id_turma']??null,FILTER_VALIDATE_INT);
    $idAluno=filter_var($_POST['id_aluno']??null,FILTER_VALIDATE_INT);
    $tipo=$_POST['tipo']??'';
    $titulo=trim($_POST['titulo']??'');
    $notaRaw=str_replace(',','.',trim($_POST['nota']??''));
    $data=$_POST['data_avaliacao']??date('Y-m-d');
    try{
        if(!$idTurmaPost || !$idAluno || !in_array($tipo,['atividade','trabalho','prova'],true) || $titulo==='' || !is_numeric($notaRaw) || (float)$notaRaw<0 || (float)$notaRaw>10) throw new InvalidArgumentException('Preencha atividade/tipo, título e nota de 0 a 10.');
        $vinculo=$pdo->prepare("SELECT 1 FROM turmas WHERE id_turma=:turma AND id_instituicao=:instituicao AND status='ativa' LIMIT 1");
        $vinculo->execute([':turma'=>$idTurmaPost,':instituicao'=>(int)$usuario['id_instituicao']]);
        if(!$vinculo->fetchColumn()) throw new InvalidArgumentException('Esta sala não está ativa na sua instituição.');
        $matricula=$pdo->prepare("SELECT 1 FROM matriculas m JOIN usuarios u ON u.id_usuario=m.id_usuario WHERE m.id_usuario=:aluno AND m.id_turma=:turma AND m.status='ativa' AND u.status='ativo' AND u.tipo_usuario='aluno'");
        $matricula->execute([':aluno'=>$idAluno,':turma'=>$idTurmaPost]);
        if(!$matricula->fetchColumn()) throw new InvalidArgumentException('O aluno não está ativo nesta turma.');
        $stmt=$pdo->prepare('INSERT INTO avaliacoes_alunos (id_professor,id_aluno,id_turma,tipo,titulo,nota,data_avaliacao) VALUES (:professor,:aluno,:turma,:tipo,:titulo,:nota,:data)');
        $stmt->execute([':professor'=>$idProfessor,':aluno'=>$idAluno,':turma'=>$idTurmaPost,':tipo'=>$tipo,':titulo'=>$titulo,':nota'=>(float)$notaRaw,':data'=>$data]);
        header('Location: notas-professor.php?turma='.$idTurmaPost.'&salvo=1');exit;
    }catch(InvalidArgumentException $exception){$erro=$exception->getMessage();$idTurma=$idTurmaPost?:$idTurma;foreach($turmas as $turma)if((int)$turma['id_turma']===(int)$idTurma)$turmaAtual=$turma;}
    catch(PDOException $exception){$erro='Não foi possível salvar a nota. Aplique a migração de avaliações e tente novamente.';}
}
$alunos=[];$notasPorAluno=[];$mediaTurma=0.0;$alunosRecuperacao=0;
if($turmaAtual){
    $stmt=$pdo->prepare("SELECT u.id_usuario,u.nome,u.email FROM matriculas m JOIN usuarios u ON u.id_usuario=m.id_usuario WHERE m.id_turma=:turma AND m.status='ativa' AND u.tipo_usuario='aluno' AND u.status='ativo' ORDER BY u.nome");
    $stmt->execute([':turma'=>(int)$turmaAtual['id_turma']]);$alunos=$stmt->fetchAll();
    if($alunos){
        $ids=array_map(static fn($aluno)=>(int)$aluno['id_usuario'],$alunos);$placeholders=implode(',',array_fill(0,count($ids),'?'));
        $stmt=$pdo->prepare("SELECT id_avaliacao,id_aluno,tipo,titulo,nota,data_avaliacao FROM avaliacoes_alunos WHERE id_professor=? AND id_turma=? AND id_aluno IN ($placeholders) ORDER BY data_avaliacao,id_avaliacao");
        $stmt->execute(array_merge([$idProfessor,(int)$turmaAtual['id_turma']],$ids));
        foreach($stmt->fetchAll() as $grade)$notasPorAluno[(int)$grade['id_aluno']][$grade['tipo']][]=$grade;
    }
    $medias=[];
    foreach($alunos as $aluno){$all=[];foreach(['atividade','trabalho','prova'] as $tipo)$all=array_merge($all,$notasPorAluno[(int)$aluno['id_usuario']][$tipo]??[]);if($all){$m=array_sum(array_map(static fn($g)=>(float)$g['nota'],$all))/count($all);$medias[]=$m;if($m<7)$alunosRecuperacao++;}}
    $mediaTurma=$medias?array_sum($medias)/count($medias):0;
}
$nomeUsuario=$esc($usuario['nome']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Notas | Vanguard</title><link rel="stylesheet" href="css/notas-professor.css"></head>
<body><div class="page-shell"><aside class="sidebar">
    <div class="brand-wrap"><img src="imagem/logo-maior.png" alt="Vanguard" class="brand-logo"></div>
    <div class="user-box"><div class="user-avatar"><img src="imagem/perfil.png" alt="<?= $nomeUsuario ?>"></div><div class="user-name-wrap"><strong><?= $nomeUsuario ?></strong><span>Professor</span></div></div>
    <nav class="side-nav" aria-label="Navegação principal">
        <a href="dash-professor.php" class="nav-item"><img src="imagem/casa-branca.png" alt="" aria-hidden="true"><span>DashBoard</span></a>
        <div class="nav-dropdown"><a class="nav-item nav-toggle" tabindex="0" role="button" aria-controls="submenu-salas"><img src="imagem/salas-branco.png" alt="" aria-hidden="true"><span>Salas</span><i class="seta" aria-hidden="true"></i></a><div class="submenu" id="submenu-salas"><ul><?php foreach($turmas as $turma): ?><li><a href="salas-professor.php?sala=<?= (int)$turma['id_turma'] ?>"><?= $esc($turma['turma']) ?></a></li><?php endforeach; ?></ul></div></div>
        <a href="calendario-professor.php" class="nav-item"><img src="imagem/calendario-01.png" alt="" aria-hidden="true"><span>Calendário / aulas do dia</span></a>
        <a href="notas-professor.php" class="nav-item active" aria-current="page"><img src="imagem/notas-verde.png" alt="" aria-hidden="true"><span>Notas</span></a>
    </nav></aside>
    <div class="main-column"><header class="topbar"><a href="sair.php" class="logout" aria-label="Sair"><img src="imagem/exit.png" alt="" aria-hidden="true"></a></header>
    <main class="content-area">
        <section class="nt-salas" aria-label="Salas">
            <?php if(!$turmas): ?><p class="nt-aviso">Nenhuma sala foi vinculada ao seu usuário pela coordenação.</p><?php else: foreach($turmas as $turma): $stmt=$pdo->prepare("SELECT COUNT(*) FROM matriculas m JOIN usuarios u ON u.id_usuario=m.id_usuario WHERE m.id_turma=:turma AND m.status='ativa' AND u.status='ativo' AND u.tipo_usuario='aluno'");$stmt->execute([':turma'=>(int)$turma['id_turma']]);$total=(int)$stmt->fetchColumn();$resumo=$resumosTurma[(int)$turma['id_turma']]??[]; ?>
                <a class="nt-sala" href="?turma=<?= (int)$turma['id_turma'] ?>" style="text-decoration:none;<?= (int)$turma['id_turma']===(int)$idTurma?'border-color:#5f8a8c;box-shadow:0 6px 14px rgba(0,0,0,.12)':'' ?>"><div class="nt-sala-topo"><div><h3><?= $esc($turma['turma']) ?></h3><small><?= $esc($turma['disciplina']??'') ?> · <?= $total ?> alunos</small></div><img src="imagem/grupo-verde.png" alt=""></div><div class="nt-sala-media"><strong><?= !empty($resumo['alunos'])?number_format($resumo['soma']/$resumo['alunos'],1,',','.'):'—' ?></strong><span>Média da turma</span></div><span class="nt-badge"><?= (int)($resumo['recuperacao']??0) ?> em recuperação</span></a>
            <?php endforeach;endif; ?>
        </section>
        <div class="nt-topo"><h2 class="nt-titulo"><span class="nt-selo" aria-hidden="true">A+</span>Notas dos alunos</h2><label class="nt-busca" for="nt-busca-aluno"><svg class="nt-busca-icone" viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.25" fill="none" stroke="currentColor" stroke-width="2"></circle><path d="M15.2 15.2L20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path></svg><input type="search" id="nt-busca-aluno" name="busca" placeholder="Pesquisar aluno..." autocomplete="off" spellcheck="false"></label><input type="checkbox" id="filtro-recup" class="nt-check-recup"><label for="filtro-recup" class="nt-btn-recup">Em recuperação <span class="nt-conta" aria-hidden="true"><?= $alunosRecuperacao ?></span></label></div>
        <p class="nt-contagem" aria-live="polite"><?= count($alunos) ?> alunos ativos<?= $turmaAtual?' em '.$esc($turmaAtual['turma']):'' ?></p>
        <?php if($erro): ?><p class="nt-aviso erro"><?= $esc($erro) ?></p><?php elseif(isset($_GET['salvo'])): ?><p class="nt-aviso">Nota adicionada com sucesso.</p><?php endif; ?>
        <div class="nt-listas"><section class="nt-lista" id="lista-atual" style="display:flex" aria-label="Notas da turma">
            <?php if(!$turmaAtual || !$alunos): ?><p class="nt-vazio">Ainda não há alunos ativos nesta sala.</p><?php else: foreach($alunos as $indice=>$aluno): $grades=$notasPorAluno[(int)$aluno['id_usuario']]??[];$all=[];foreach(['atividade','trabalho','prova'] as $tipo)$all=array_merge($all,$grades[$tipo]??[]);$media=$all?array_sum(array_map(static fn($g)=>(float)$g['nota'],$all))/count($all):null; ?>
                <article class="nt-aluno" style="--i:<?= $indice ?>"><header class="nt-aluno-cab"><h3><a href="perfil.php?aluno=<?= (int)$aluno['id_usuario'] ?>" style="color:inherit;text-decoration:none"><?= $esc($aluno['nome']) ?></a></h3><div class="nt-aluno-resumo"><span class="nt-aluno-media"><strong><?= $media===null?'—':number_format($media,1,',','.') ?></strong><small>Média</small></span><?php if($media===null): ?><span class="nt-pill sem_notas">Sem notas</span><?php elseif($media<7): ?><span class="nt-pill recuperacao">Recuperação</span><?php else: ?><span class="nt-pill aprovado">Aprovado</span><?php endif; ?><button type="button" class="nt-pill nt-mais" data-add-grade="<?= (int)$aluno['id_usuario'] ?>" data-name="<?= $esc($aluno['nome']) ?>">Nota +</button></div></header>
                    <div class="nt-grade"><?php foreach(['atividade'=>'Atividades','prova'=>'Provas','trabalho'=>'Trabalho'] as $tipo=>$rotulo): ?><div class="nt-linha"><span class="nt-rotulo"><?= $rotulo ?></span><div class="nt-celulas"><?php $lista=$grades[$tipo]??[];foreach(array_slice($lista,0,5) as $grade): ?><span class="nt-celula <?= (float)$grade['nota']>=7?'ok':'baixo' ?>" title="<?= $esc($grade['titulo'].' · '.$grade['data_avaliacao']) ?>"><?= number_format((float)$grade['nota'],1,',','.') ?></span><?php endforeach;for($v=count($lista);$v<5;$v++): ?><span class="nt-celula vazia"></span><?php endfor; ?></div></div><?php endforeach; ?></div>
                    <footer class="nt-legenda"><span class="nt-celula ok">7,0</span><small>Igual ou maior</small><span class="nt-celula baixo">7,0</span><small>Abaixo</small><span class="nt-celula vazia"></span><small>Sem nota cadastrada</small></footer></article>
            <?php endforeach;endif; ?>
        </section></div>
    </main></div>
</div>
<dialog class="nt-modal" id="modal-nota"><form method="post"><h3>Adicionar nota</h3><p class="nt-modal-aluno" id="nome-aluno-modal"></p><input type="hidden" name="acao" value="nota"><input type="hidden" name="id_turma" value="<?= (int)($turmaAtual['id_turma']??0) ?>"><input type="hidden" name="id_aluno" id="id-aluno-modal"><label for="tipo-nota">Campo</label><select name="tipo" id="tipo-nota" required><option value="atividade">Atividade</option><option value="trabalho">Trabalho</option><option value="prova">Prova</option></select><label for="titulo-nota">Título</label><input id="titulo-nota" name="titulo" maxlength="120" required><label for="valor-nota">Nota (0 a 10)</label><input id="valor-nota" name="nota" type="number" min="0" max="10" step="0.01" required><label for="data-nota">Data</label><input id="data-nota" name="data_avaliacao" type="date" value="<?= date('Y-m-d') ?>" required><div class="nt-modal-botoes"><button type="button" class="nt-btn-cancelar" id="cancelar-nota">Cancelar</button><button type="submit" class="nt-btn-salvar">Salvar nota</button></div></form></dialog>
<script>
document.querySelectorAll('[data-add-grade]').forEach(button=>button.addEventListener('click',()=>{document.getElementById('id-aluno-modal').value=button.dataset.addGrade;document.getElementById('nome-aluno-modal').textContent=button.dataset.name;document.getElementById('modal-nota').showModal()}));
document.getElementById('cancelar-nota').addEventListener('click',()=>document.getElementById('modal-nota').close());
document.getElementById('modal-nota').addEventListener('click',event=>{if(event.target===event.currentTarget)event.currentTarget.close()});
document.getElementById('nt-busca-aluno').addEventListener('input',function(){const query=this.value.trim().toLocaleLowerCase('pt-BR');document.querySelectorAll('.nt-aluno').forEach(card=>card.classList.toggle('nt-oculto',!card.querySelector('h3').textContent.toLocaleLowerCase('pt-BR').includes(query)))});
</script>
</body></html>
