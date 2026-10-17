<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$idUsuario = $_GET['id_usuario'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $pdo = getConnection();
        $idUsuario = $_GET['id_usuario'] ?? null;

        if ($idUsuario) {
            $stmt = $pdo->prepare("SELECT * FROM anotacoes WHERE id_usuario = :id_usuario ORDER BY data_criacao DESC");
            $stmt->execute([':id_usuario' => $idUsuario]);
        } else {
            $stmt = $pdo->query("SELECT * FROM anotacoes ORDER BY data_criacao DESC");
        }
        echo json_encode(['sucesso' => true, 'dados' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
    }
}
?>