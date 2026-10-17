<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    try {
        $pdo = getConnection();

        $idInstituicao = $_GET['id_instituicao'] ?? null;

        if ($idInstituicao) {
            $sql = "SELECT id_usuario, id_instituicao, nome, email, tipo_usuario, foto_perfil, data_criacao 
            FROM usuarios WHERE  id_instituicao = :id_instituicao ORDER BY nome ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_instituicao' => $idInstituicao]);
        } else {
            $sql = "SELECT id_usuario, id_instituicao, nome, email, tipo_usuario, foto_perfil, data_criacao 
            FROM usuarios ORDER BY nome ASC";
            $stmt = $pdo->query($sql);
        }

        echo json_encode(['sucesso' => true, 'dados' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao listar usuário: ' . $e->getMessage()]);
    }
}
?>