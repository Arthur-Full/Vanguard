<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($dados['nome'])){
    try {
        $pdo = getConnection();
        $sql = "INSERT INTO instituicoes (nome, cidade, cnpj) 
                VALUES (:nome, :cidade, :cnpj )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $dados['nome'],
            ':cidade' => $dados['cidade'] ?? null,
            ':cnpj' => $dados['cnpj'] ?? null
        ]);
        echo json_encode(['sucesso' => true, 'mensagem' => 'Instituição cadastrada!', 'id_instituicao' => $pdo->lastInsertId()]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao cadastrar isntituição: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'Nome da instituição é obrigatória.']);
}
?>