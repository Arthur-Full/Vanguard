<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/auth.php';
exigirPerfil(['aluno']);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['sucesso'=>false,'erro'=>'Método não permitido.']); exit; }
$dados = json_decode(file_get_contents('php://input'), true) ?? [];
$atividade = filter_var($dados['id_atividade'] ?? null, FILTER_VALIDATE_INT);
$resposta = trim($dados['resposta'] ?? '');
if (!$atividade || $resposta === '') { http_response_code(400); echo json_encode(['sucesso'=>false,'erro'=>'Informe a atividade e a resposta.']); exit; }
try {
    $pdo = getConnection();
    $stmt = $pdo->prepare("INSERT INTO entregas_atividades (id_atividade,id_aluno,resposta) SELECT a.id_atividade,:aluno,:resposta FROM atividades a JOIN matriculas m ON m.id_turma=a.id_turma AND m.id_usuario=:aluno2 AND m.status='ativa' WHERE a.id_atividade=:atividade ON DUPLICATE KEY UPDATE resposta=VALUES(resposta),entregue_em=CURRENT_TIMESTAMP,nota=NULL,feedback=NULL,avaliado_por=NULL,avaliado_em=NULL");
    $stmt->execute([':aluno'=>usuarioAtual()['id_usuario'], ':resposta'=>$resposta, ':aluno2'=>usuarioAtual()['id_usuario'], ':atividade'=>$atividade]);
    if (!$stmt->rowCount()) { http_response_code(404); echo json_encode(['sucesso'=>false,'erro'=>'Atividade não encontrada ou aluno sem matrícula ativa.']); exit; }
    echo json_encode(['sucesso'=>true]);
} catch (PDOException $e) { http_response_code(500); echo json_encode(['sucesso'=>false,'erro'=>'Não foi possível enviar a atividade.']); }
?>
