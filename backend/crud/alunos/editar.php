<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true) ?? [];
$idUsuario = filter_var($dados['id_usuario'] ?? null, FILTER_VALIDATE_INT);
$idTurma = filter_var($dados['id_turma'] ?? null, FILTER_VALIDATE_INT);
$idMatricula = filter_var($dados['id_matricula'] ?? null, FILTER_VALIDATE_INT);
$nome = trim($dados['nome'] ?? '');
$email = trim($dados['email'] ?? '');
$telefone = trim($dados['telefone'] ?? '');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$idUsuario || !$idTurma || $nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'Informe nome, e-mail e uma sala válida.']);
    exit;
}

try {
    $pdo = getConnection();
    $pdo->beginTransaction();
    $userCheck = $pdo->prepare("SELECT id_usuario, status FROM usuarios WHERE id_usuario = :id AND tipo_usuario = 'aluno'");
    $userCheck->execute([':id' => $idUsuario]);
    $usuario = $userCheck->fetch(PDO::FETCH_ASSOC);
    $roomCheck = $pdo->prepare('SELECT id_instituicao FROM turmas WHERE id_turma = :id');
    $roomCheck->execute([':id' => $idTurma]);
    $idInstituicao = $roomCheck->fetchColumn();
    if (!$usuario || !$idInstituicao) {
        $pdo->rollBack();
        http_response_code(404);
        echo json_encode(['sucesso' => false, 'erro' => 'Aluno ou sala não encontrado.']);
        exit;
    }

    $updateUser = $pdo->prepare('UPDATE usuarios SET id_instituicao = :instituicao, nome = :nome, email = :email, telefone = :telefone WHERE id_usuario = :id');
    $updateUser->execute([':instituicao' => $idInstituicao, ':nome' => $nome, ':email' => $email, ':telefone' => $telefone ?: null, ':id' => $idUsuario]);
    if ($idMatricula) {
        $updateEnrollment = $pdo->prepare('UPDATE matriculas SET id_turma = :turma WHERE id_matricula = :matricula AND id_usuario = :usuario');
        $updateEnrollment->execute([':turma' => $idTurma, ':matricula' => $idMatricula, ':usuario' => $idUsuario]);
    } else {
        $insertEnrollment = $pdo->prepare('INSERT INTO matriculas (id_usuario, id_turma, status) VALUES (:usuario, :turma, :status)');
        $insertEnrollment->execute([':usuario' => $idUsuario, ':turma' => $idTurma, ':status' => (($usuario['status'] ?? 'ativo') === 'ativo') ? 'ativa' : 'inativa']);
    }
    $pdo->commit();
    echo json_encode(['sucesso' => true, 'mensagem' => 'Aluno atualizado com sucesso.']);
} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    $erro = $e->getCode() === '23000' ? 'Este e-mail já está cadastrado ou o aluno já possui matrícula nessa sala.' : 'Erro ao atualizar aluno: ' . $e->getMessage();
    echo json_encode(['sucesso' => false, 'erro' => $erro]);
}
?>
