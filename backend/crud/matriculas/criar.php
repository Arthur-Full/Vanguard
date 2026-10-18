<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/auth.php';
exigirPerfil(['admin', 'coordenador']);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['sucesso'=>false,'erro'=>'Método não permitido.']); exit; }
$dados = json_decode(file_get_contents('php://input'), true) ?? [];
$aluno = filter_var($dados['id_usuario'] ?? null, FILTER_VALIDATE_INT);
$turma = filter_var($dados['id_turma'] ?? null, FILTER_VALIDATE_INT);
if (!$aluno || !$turma) { http_response_code(400); echo json_encode(['sucesso'=>false,'erro'=>'Informe o aluno e a turma.']); exit; }
try {
    $pdo = getConnection();
    $verifica = $pdo->prepare("SELECT u.id_usuario FROM usuarios u JOIN turmas t ON t.id_instituicao=u.id_instituicao WHERE u.id_usuario=:aluno AND u.tipo_usuario='aluno' AND t.id_turma=:turma");
    $verifica->execute([':aluno'=>$aluno, ':turma'=>$turma]);
    if (!$verifica->fetchColumn()) { http_response_code(404); echo json_encode(['sucesso'=>false,'erro'=>'Aluno ou turma inválidos.']); exit; }
    $stmt = $pdo->prepare("INSERT INTO matriculas (id_usuario,id_turma,status) VALUES (:aluno,:turma,'ativa') ON DUPLICATE KEY UPDATE status='ativa'");
    $stmt->execute([':aluno'=>$aluno, ':turma'=>$turma]);
    echo json_encode(['sucesso'=>true,'id_matricula'=>$pdo->lastInsertId()]);
} catch (PDOException $e) { http_response_code(500); echo json_encode(['sucesso'=>false,'erro'=>'Não foi possível criar a matrícula.']); }
?>
