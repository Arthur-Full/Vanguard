<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);

if (($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'POST') && !empty($dados['id_usuario'])) {
    try {
        $pdo = getConnection();

        if (!empty($dados['senha'])) {
            $senhaHash = password_hash($dados['senha'], PASSWORD_BCRYPT);
            $sql = "UPDATE usuarios SET id_instituicao = COALESCE(:id_instituicao, id_instituicao), nome = :nome, email = :email, telefone = COALESCE(:telefone, telefone), senha_hash = :senha_hash, tipo_usuario = :tipo_usuario, foto_perfil = COALESCE(:foto_perfil, foto_perfil) WHERE id_usuario = :id_usuario";
            $params = [
                ':id_usuario' => $dados['id_usuario'],
                ':id_instituicao' => $dados['id_instituicao'] ?? null,
                ':nome' => $dados['nome'],
                ':email' => $dados['email'],
                ':telefone' => $dados['telefone'] ?? null,
                ':senha_hash' => $senhaHash,
                ':tipo_usuario' => $dados['tipo_usuario'],
                ':foto_perfil' => $dados['foto_perfil'] ?? null
            ];
        } else {
            $sql = "UPDATE usuarios SET id_instituicao = COALESCE(:id_instituicao, id_instituicao), nome = :nome, email = :email, telefone = COALESCE(:telefone, telefone), tipo_usuario = :tipo_usuario, foto_perfil = COALESCE(:foto_perfil, foto_perfil) WHERE id_usuario = :id_usuario";
            $params = [
                ':id_usuario' => $dados['id_usuario'],
                ':id_instituicao' => $dados['id_instituicao'] ?? null,
                ':nome' => $dados['nome'],
                ':email' => $dados['email'],
                ':telefone' => $dados['telefone'] ?? null,
                ':tipo_usuario' => $dados['tipo_usuario'],
                ':foto_perfil' => $dados['foto_perfil'] ?? null
            ];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        echo json_encode(['sucesso' =>true, 'mensagem' => 'Usuário atualizado com sucesso!']);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao atualizar usuário: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'ID do usuário é obrigatorio.']);
}
?>
