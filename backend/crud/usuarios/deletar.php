<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);
$idUsuario = $dados['id_usuario'] ?? $_GET['id_usuario'] ?? null;

if (($_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'POST') && $idUsuario) {
    try {
        $pdo = getConnection();
        $pdo->beginTransaction();
        $tipoStmt = $pdo->prepare('SELECT tipo_usuario FROM usuarios WHERE id_usuario = :id_usuario');
        $tipoStmt->execute([':id_usuario' => $idUsuario]);
        $tipoUsuario = $tipoStmt->fetchColumn();
        if (!$tipoUsuario) {
            $pdo->rollBack();
            http_response_code(404);
            echo json_encode(['sucesso' => false, 'erro' => 'Usuário não encontrado.']);
            exit;
        }
        if ($tipoUsuario === 'professor') {
            $entregas = $pdo->prepare('DELETE FROM entregas_atividades WHERE id_atividade IN (SELECT id_atividade FROM atividades WHERE id_professor = :id_professor)');
            $entregas->execute([':id_professor' => $idUsuario]);
            $professorTurmas = $pdo->prepare('DELETE FROM professor_turmas WHERE id_professor = :id_professor');
            $professorTurmas->execute([':id_professor' => $idUsuario]);
            $atividades = $pdo->prepare('DELETE FROM atividades WHERE id_professor = :id_professor');
            $atividades->execute([':id_professor' => $idUsuario]);
        }
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = :id_usuario");
        $stmt->execute([':id_usuario' => $idUsuario]);
        $pdo->commit();
        echo json_encode(['sucesso' => true, 'mensagem' => 'Usuário excluído com sucesso!']);
    } catch (PDOException $e) {
        if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao excluir usuário: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'ID do usuário não informado.']);
}
?>
