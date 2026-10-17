<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    try {
        $pdo = getConnection();

        $idTurma = $_GET['id_turma'] ?? null;

        if ($idTurma) {
            $stmt = $pdo->prepare("SELECT * FROM atividades WHERE  id_turma = :id_turma ORDER BY data_entrega DESC");
            $stmt->execute([':id_turma' => $idTurma]);
        } else {
            $stmt = $pdo->query("SELECT * FROM atividades ORDER BY data_entrega DESC");
        }

        echo json_encode(['sucesso' => true, 'dados' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao listar atividades: ' . $e->getMessage()]);
    }
}
?>