<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true) ?? [];
$id = filter_var($dados['id_usuario'] ?? null, FILTER_VALIDATE_INT);
$status = $dados['status'] ?? '';
$tipo = $dados['tipo_usuario'] ?? '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id || !in_array($status, ['ativo', 'inativo'], true) || !in_array($tipo, ['aluno', 'professor'], true)) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'Dados inválidos para atualizar o status.']);
    exit;
}

try {
    $pdo = getConnection();
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('UPDATE usuarios SET status = :status WHERE id_usuario = :id_usuario AND tipo_usuario = :tipo_usuario');
    $stmt->execute([':status' => $status, ':id_usuario' => $id, ':tipo_usuario' => $tipo]);
    if ($stmt->rowCount() === 0) {
        $check = $pdo->prepare('SELECT id_usuario FROM usuarios WHERE id_usuario = :id_usuario AND tipo_usuario = :tipo_usuario');
        $check->execute([':id_usuario' => $id, ':tipo_usuario' => $tipo]);
        if (!$check->fetchColumn()) {
            $pdo->rollBack();
            http_response_code(404);
            echo json_encode(['sucesso' => false, 'erro' => 'Usuário não encontrado.']);
            exit;
        }
    }
    if ($tipo === 'aluno') {
        $statusMatricula = $status === 'ativo' ? 'ativa' : 'inativa';
        $matricula = $pdo->prepare('UPDATE matriculas SET status = :status WHERE id_usuario = :id_usuario');
        $matricula->execute([':status' => $statusMatricula, ':id_usuario' => $id]);
    }
    $pdo->commit();
    echo json_encode(['sucesso' => true, 'mensagem' => 'Status atualizado com sucesso.']);
} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Erro ao atualizar status: ' . $e->getMessage()]);
}
?>
