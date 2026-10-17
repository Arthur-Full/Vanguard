<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

try {

    $pdo = getConnection();
    $stmt = $pdo->query("SELECT * FROM instituicoes ORDER BY nome ASC");

    echo json_encode(['sucesso' => true, 'dados' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao listar instituição' . $e->getMessage()]);
    }
?>