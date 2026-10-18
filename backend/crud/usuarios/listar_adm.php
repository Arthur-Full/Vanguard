<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$tipo = $_GET['tipo_usuario'] ?? '';
$idTurma = $_GET['id_turma'] ?? '';
if (!in_array($tipo, ['aluno', 'professor'], true)) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'Tipo de usuário inválido.']);
    exit;
}

try {
    $pdo = getConnection();
    if ($tipo === 'aluno') {
        $sql = "SELECT u.id_usuario, u.id_instituicao, u.nome, u.email, u.telefone, u.tipo_usuario,
                       i.nome AS nome_instituicao,
                       m.id_matricula, m.id_turma, t.nome AS nome_turma,
                       CASE WHEN m.status IS NULL THEN COALESCE(u.status, 'ativo')
                            WHEN LOWER(m.status) IN ('ativa', 'ativo', 'active') THEN 'ativo' ELSE 'inativo' END AS status
                FROM usuarios u
                INNER JOIN instituicoes i ON i.id_instituicao = u.id_instituicao
                LEFT JOIN matriculas m ON m.id_usuario = u.id_usuario
                LEFT JOIN turmas t ON t.id_turma = m.id_turma
                WHERE u.tipo_usuario = :tipo_usuario
                  AND (:filtro_turma = '' OR m.id_turma = :id_turma)
                ORDER BY u.nome ASC";
    } else {
        $sql = "SELECT u.id_usuario, u.id_instituicao, u.nome, u.email, u.telefone, u.tipo_usuario,
                       COALESCE(u.status, 'ativo') AS status, i.nome AS nome_instituicao,
                       NULL AS id_matricula, t.id_turma, t.nome AS nome_turma
                FROM usuarios u
                INNER JOIN instituicoes i ON i.id_instituicao = u.id_instituicao
                LEFT JOIN professor_turmas pt ON pt.id_professor = u.id_usuario
                LEFT JOIN turmas t ON t.id_turma = pt.id_turma
                WHERE u.tipo_usuario = :tipo_usuario
                  AND (:filtro_turma = '' OR t.id_turma = :id_turma)
                GROUP BY u.id_usuario, u.id_instituicao, u.nome, u.email, u.telefone, u.tipo_usuario, u.status, i.nome, t.id_turma, t.nome
                ORDER BY u.nome ASC";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':tipo_usuario' => $tipo, ':filtro_turma' => $idTurma, ':id_turma' => $idTurma]);
    echo json_encode(['sucesso' => true, 'dados' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Erro ao listar usuários: ' . $e->getMessage()]);
}
?>
