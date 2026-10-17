<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    try {
        $pdo = getConnection();

        $idInstituicao = $_GET['id_instituicao'] ?? null;

        if ($idInstituicao) {
            $stmt = $pdo->prepare("SELECT * FROM turmas WHERE  id_instituicao = :id_instituicao ORDER BY nome ASC");
            $stmt->execute([':id_instituicao' => $idInstituicao]);
        } else {
            $stmt = $pdo->query("SELECT * FROM turmas ORDER BY nome ASC");
        }

        echo json_encode(['sucesso' => true, 'dados' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao listar turmas: ' . $e->getMessage()]);
    }
}
?>