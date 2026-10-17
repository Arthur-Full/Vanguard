<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    try {
        $pdo = getConnection();

        $idInstituicao = $_GET['id_instituicao'] ?? null;

        if ($idInstituicao) {
            $sql = "SELECT * FROM eventos WHERE  id_instituicao = :id_instituicao ORDER BY data_inicio ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_instituicao' => $idInstituicao]);
        } else {
            $sql = "SELECT * FROM eventos ORDER BY data_inicio ASC";
            $stmt = $pdo->query($sql);
        }

        $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['sucesso' => true, 'dados' => $eventos]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao listar eventos: ' . $e->getMessage()]);
    }
}
?>