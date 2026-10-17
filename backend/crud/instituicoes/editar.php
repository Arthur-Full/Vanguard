<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);

if (!empty($dados['id_instituicao'])) {
    try {
        $pdo = getConnection();

        $sql = "UPDATE instituicoes SET nome = :nome, cidade = :cidade, cnpj = :cnpj WHERE id_instituicao = :id_instituicao";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([    
            ':id_instituicao' => $dados['id_instituicao'],
            ':nome' => $dados['nome'],
            ':cidade' => $dados['cidade'] ?? null,
            ':cnpj' => $dados['cnpj'] ?? null
        ]);
        
        echo json_encode(['sucesso' =>true, 'mensagem' => 'Instituição atualizada!']);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao atualizar instituição' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'ID da instituição é obrigatorio.']);
}
?>