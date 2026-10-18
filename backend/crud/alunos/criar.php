<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true) ?? [];
$nome = trim($dados['nome'] ?? '');
$email = trim($dados['email'] ?? '');
$senha = $dados['senha'] ?? '';
$telefone = trim($dados['telefone'] ?? '');
$idTurma = filter_var($dados['id_turma'] ?? null, FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $nome === '' || $email === '' || $senha === '' || $telefone === '' || !$idTurma || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'Informe nome, e-mail, senha, telefone e uma sala válida.']);
    exit;
}

try {
    $pdo = getConnection();
    $pdo->beginTransaction();

    $turmaStmt = $pdo->prepare('SELECT id_instituicao FROM turmas WHERE id_turma = :id_turma');
    $turmaStmt->execute([':id_turma' => $idTurma]);
    $idInstituicao = $turmaStmt->fetchColumn();
    if (!$idInstituicao) {
        $pdo->rollBack();
        http_response_code(404);
        echo json_encode(['sucesso' => false, 'erro' => 'A sala selecionada não foi encontrada.']);
        exit;
    }

    $usuarioStmt = $pdo->prepare('INSERT INTO usuarios (id_instituicao, nome, email, senha_hash, telefone, tipo_usuario) VALUES (:id_instituicao, :nome, :email, :senha_hash, :telefone, \'aluno\')');
    $usuarioStmt->execute([
        ':id_instituicao' => $idInstituicao,
        ':nome' => $nome,
        ':email' => $email,
        ':senha_hash' => password_hash($senha, PASSWORD_BCRYPT),
        ':telefone' => $telefone
    ]);
    $idUsuario = $pdo->lastInsertId();

    $matriculaStmt = $pdo->prepare("INSERT INTO matriculas (id_usuario, id_turma, status) VALUES (:id_usuario, :id_turma, 'ativa')");
    $matriculaStmt->execute([':id_usuario' => $idUsuario, ':id_turma' => $idTurma]);

    $pdo->commit();
    echo json_encode(['sucesso' => true, 'mensagem' => 'Aluno cadastrado e matriculado com sucesso.', 'id_usuario' => $idUsuario]);
} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    $erro = $e->getCode() === '23000' ? 'Este e-mail já está cadastrado.' : 'Erro ao cadastrar aluno: ' . $e->getMessage();
    echo json_encode(['sucesso' => false, 'erro' => $erro]);
}
?>
