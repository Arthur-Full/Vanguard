<?php
require_once __DIR__ . '/../backend/config/auth.php';
exigirPerfil(['professor']);
$usuario = usuarioAtual();
$pdo = getConnection();
$idProfessor = (int)$usuario['id_usuario'];
$esc = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
$stmt = $pdo->prepare("SELECT t.id_turma,t.nome,t.serie,t.curso FROM turmas t WHERE t.id_instituicao=:instituicao AND t.status='ativa' ORDER BY t.nome");
$stmt->execute([':instituicao'=>(int)$usuario['id_instituicao']]);
$turmas = $stmt->fetchAll();
$idTurma = filter_var($_GET['sala'] ?? ($turmas[0]['id_turma'] ?? null), FILTER_VALIDATE_INT);
$turmaAtual = null;
foreach ($turmas as $turma) if ((int)$turma['id_turma'] === (int)$idTurma) { $turmaAtual=$turma; break; }
if (!$turmaAtual && $turmas) { $turmaAtual=$turmas[0]; $idTurma=(int)$turmaAtual['id_turma']; }
$alunos = [];
if ($turmaAtual) {
    $stmt=$pdo->prepare("SELECT u.id_usuario,u.nome FROM matriculas m JOIN usuarios u ON u.id_usuario=m.id_usuario WHERE m.id_turma=:turma AND m.status='ativa' AND u.tipo_usuario='aluno' AND u.status='ativo' ORDER BY m.data_matricula,u.nome");
    $stmt->execute([':turma'=>(int)$turmaAtual['id_turma']]);
    $alunos=$stmt->fetchAll();
}
$totalCarteiras=max(48,count($alunos));
$nomeCurto = static function ($nome): string {
    $partes = preg_split('/\s+/u', trim((string)$nome), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    if (count($partes) < 2) return $partes[0] ?? '';
    return $partes[0] . ' ' . $partes[count($partes) - 1];
};
$nomeUsuario=$esc($usuario['nome']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salas | Vanguard</title>
    <link rel="stylesheet" href="css/salas-professor.css">
</head>
<body>
    <div class="page-shell">
        <aside class="sidebar">
            <div class="brand-wrap"><img src="imagem/logo-maior.png" alt="Vanguard" class="brand-logo"></div>
            <div class="user-box"><div class="user-avatar"><img src="imagem/perfil.png" alt="<?= $nomeUsuario ?>"></div><div class="user-name-wrap"><strong><?= $nomeUsuario ?></strong><span>Professor</span></div></div>
            <nav class="side-nav" aria-label="Navegação principal">
                <a href="dash-professor.php" class="nav-item"><img src="imagem/casa-branca.png" alt="" aria-hidden="true"><span>DashBoard</span></a>
                <div class="nav-dropdown aberto">
                    <a href="#" class="nav-item nav-toggle" aria-expanded="true" aria-controls="submenu-salas"><img src="imagem/salas-branco.png" alt="" aria-hidden="true"><span>Salas</span><i class="seta" aria-hidden="true"></i></a>
                    <div class="submenu" id="submenu-salas"><ul>
                        <?php foreach ($turmas as $turma): ?><li><a href="?sala=<?= (int)$turma['id_turma'] ?>" class="<?= (int)$turma['id_turma']===(int)$idTurma?'sel':'' ?>"><?= $esc($turma['nome']) ?></a></li><?php endforeach; ?>
                    </ul></div>
                </div>
                <a href="calendario-professor.php" class="nav-item"><img src="imagem/calendario-01.png" alt="" aria-hidden="true"><span>Calendário / aulas do dia</span></a>
                <a href="notas-professor.php" class="nav-item"><img src="imagem/notas-branco.png" alt="" aria-hidden="true"><span>Notas</span></a>
            </nav>
            <a href="sair.php" class="btn-sair">Sair</a>
        </aside>
        <div class="main-column">
            <main class="content-area sala-area">
                <?php if (!$turmaAtual): ?><p>Você ainda não possui salas vinculadas pela coordenação.</p><?php else: ?>
                <div class="sala-piso">
                    <div class="sala-prof" title="Mesa do professor"><span class="sala-prof-cadeira"></span><div class="sala-prof-mesa"></div></div>
                    <div class="sala-carteiras"><div class="sala-bloco" id="bloco-esq">
                        <?php for($i=0;$i<$totalCarteiras;$i++): if($i%6<3): $aluno=$alunos[$i]??null; $ordem=intdiv($i,6)*3+($i%3); ?>
                            <div class="carteira <?= $aluno?'ok':'vazia falta' ?>" style="--i:<?= $ordem ?>" title="<?= $aluno?$esc($aluno['nome']):'Carteira vazia' ?>"><span class="nome"><?php if($aluno): ?><a class="aluno-perfil-link" href="perfil.php?aluno=<?= (int)$aluno['id_usuario'] ?>" aria-label="Abrir perfil de <?= $esc($aluno['nome']) ?>"><?= $esc($nomeCurto($aluno['nome'])) ?></a><?php endif; ?></span><span class="tampo"></span><span class="status"></span></div>
                        <?php endif; endfor; ?>
                    </div><div class="sala-bloco" id="bloco-dir">
                        <?php for($i=0;$i<$totalCarteiras;$i++): if($i%6>=3): $aluno=$alunos[$i]??null; $ordem=intdiv($i,6)*3+($i%3); ?>
                            <div class="carteira <?= $aluno?'ok':'vazia falta' ?>" style="--i:<?= $ordem ?>" title="<?= $aluno?$esc($aluno['nome']):'Carteira vazia' ?>"><span class="nome"><?php if($aluno): ?><a class="aluno-perfil-link" href="perfil.php?aluno=<?= (int)$aluno['id_usuario'] ?>" aria-label="Abrir perfil de <?= $esc($aluno['nome']) ?>"><?= $esc($nomeCurto($aluno['nome'])) ?></a><?php endif; ?></span><span class="tampo"></span><span class="status"></span></div>
                        <?php endif; endfor; ?>
                    </div></div>
                </div><?php endif; ?>
            </main>
        </div>
    </div>
</body>
</html>
