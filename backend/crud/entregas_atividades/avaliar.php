<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/auth.php';
exigirPerfil(['professor', 'admin']);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['sucesso'=>false,'erro'=>'Método não permitido.']); exit; }
$dados = json_decode(file_get_contents('php://input'), true) ?? [];
$entrega = filter_var($dados['id_entrega'] ?? null, FILTER_VALIDATE_INT);
$nota = filter_var($dados['nota'] ?? null, FILTER_VALIDATE_FLOAT);
$feedback = trim($dados['feedback'] ?? '');
if (!$entrega || $nota === false || $nota < 0 || $nota > 100) { http_response_code(400); echo json_encode(['sucesso'=>false,'erro'=>'Informe uma entrega e uma nota entre 0 e 100.']); exit; }
try {
    $pdo = getConnection();
    $sql = "UPDATE entregas_atividades e JOIN atividades a ON a.id_atividade=e.id_atividade SET e.nota=:nota,e.feedback=:feedback,e.avaliado_por=:avaliador,e.avaliado_em=NOW() WHERE e.id_entrega=:entrega";
    $params = [':nota'=>$nota, ':feedback'=>$feedback ?: null, ':avaliador'=>usuarioAtual()['id_usuario'], ':entrega'=>$entrega];
    if (usuarioAtual()['tipo_usuario'] === 'professor') { $sql .= ' AND a.id_professor=:professor'; $params[':professor']=usuarioAtual()['id_usuario']; }
    $stmt = $pdo->prepare($sql); $stmt->execute($params);
    if (!$stmt->rowCount()) { http_response_code(404); echo json_encode(['sucesso'=>false,'erro'=>'Entrega não encontrada ou sem permissão.']); exit; }
    echo json_encode(['sucesso'=>true]);
} catch (PDOException $e) { http_response_code(500); echo json_encode(['sucesso'=>false,'erro'=>'Não foi possível avaliar a entrega.']); }
?>
