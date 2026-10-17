<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($dados['id_usuario']) && !empty($dados['titulo'])){
    try {
        $pdo = getConnection();
    
        $sql = "INSERT INTO anotacoes (id_usuario, titulo, conteudo) 
                VALUES (:id_usuario, :titulo, :conteudo )";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_usuario' => $dados['id_usuario'],
            ':titulo' => $dados['titulo'],
            ':conteudo' => $dados['conteudo'] ?? null
        ]);
        echo json_encode(['sucesso' => true, 'mensagem' => 'Anotação salva!', 'id_anotacao' => $pdo->lastInsertId()]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao adicionar anotação: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'ID do usuário e título são obrigatórios.']);
}
?>