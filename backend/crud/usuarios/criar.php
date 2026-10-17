<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($dados)){
    try {
        $pdo = getConnection();
        $senhaHash = password_hash($dados['senha'], PASSWORD_BCRYPT);

        $sql = "INSERT INTO usuarios (id_instituicao, nome, email, senha_hash, tipo_usuario, foto_perfil) 
                VALUES (:id_instituicao, :nome, :email, :senha_hash, :tipo_usuario, :foto_perfil)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_instituicao' => $dados['id_instituicao'],
            ':nome' => $dados['nome'],
            ':email' => $dados['email'],
            ':senha_hash' => $senhaHash,
            ':tipo_usuario' => $dados['tipo_usuario'],
            ':foto_perfil' => $dados['foto_perfil'] ?? null
        ]);
        echo json_encode(['sucesso' => true, 'mensagem' => 'Usuário criado com sucesso!', 'id_usuario' => $pdo->lastInsertId()]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao criar usuario: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'Requisição inválida ou dados ausentes.']);
}
?>