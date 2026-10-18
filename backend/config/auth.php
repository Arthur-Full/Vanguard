<?php
require_once __DIR__ . '/conexao.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function usuarioAtual(): ?array
{
    return isset($_SESSION['usuario']) && is_array($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
}

function exigirPerfil(array $perfis): void
{
    $usuario = usuarioAtual();
    if (!$usuario) {
        header('Location: index.php');
        exit;
    }
    if (!in_array($usuario['tipo_usuario'] ?? '', $perfis, true)) {
        http_response_code(403);
        exit('Acesso não autorizado.');
    }
}

function urlPainel(string $perfil): string
{
    return match ($perfil) {
        'admin', 'coordenador' => 'dash-adm.php',
        'professor' => 'dash-professor.php',
        'aluno' => 'dash-aluno.php',
        default => 'index.php',
    };
}

function feriadosDoAno(int $ano): array
{
    $stmt = getConnection()->prepare('SELECT data, titulo, tipo, observacao FROM feriados WHERE ano=:ano ORDER BY data, titulo');
    $stmt->execute([':ano' => $ano]);
    return $stmt->fetchAll();
}
?>
