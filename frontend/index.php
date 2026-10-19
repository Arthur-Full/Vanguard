<?php
require_once __DIR__ . '/../backend/config/auth.php';
$usuario = usuarioAtual();
if ($usuario) {
    header('Location: ' . urlPainel($usuario['tipo_usuario']));
    exit;
}
$erro = '';
$instituicoes = [];
try {
    $pdo = getConnection();
    $instituicoes = $pdo->query('SELECT id_instituicao, nome FROM instituicoes ORDER BY nome')->fetchAll();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $idInstituicao = filter_var($_POST['id_instituicao'] ?? null, FILTER_VALIDATE_INT);
        $stmt = $pdo->prepare('SELECT id_usuario,id_instituicao,nome,email,senha_hash,tipo_usuario,status,foto_perfil FROM usuarios WHERE email=:email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $conta = $stmt->fetch();
        $instituicaoValida = $conta && ($conta['tipo_usuario'] === 'admin'
            || ($idInstituicao && (int)$conta['id_instituicao'] === (int)$idInstituicao));
        if ($conta && $instituicaoValida && $conta['status'] === 'ativo' && password_verify($senha, $conta['senha_hash'])) {
            session_regenerate_id(true);
            unset($conta['senha_hash']);
            $_SESSION['usuario'] = $conta;
            header('Location: ' . urlPainel($conta['tipo_usuario']));
            exit;
        }
        $erro = 'E-mail, senha ou instituição inválidos.';
    }
} catch (Throwable $e) {
    $erro = 'Não foi possível conectar ao sistema. Verifique a configuração do banco de dados.';
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar | Vanguard</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <main>
        <div class="quadrado">
            <div class="Primeira_logo"><img class="img" src="./imagem/LOGO.png" alt="Vanguard"></div>
            <div class="titulo">
                <div class="texto">
                    <h1>Bem-vindo ao Vanguard</h1>
                    <p>Entre para acompanhar sua comunidade escolar.</p>
                </div>
            </div>
            <form class="forms" method="post" action="index.php">
                <div class="espaco_forms">
                    <?php if ($erro !== ''): ?><p role="alert" style="color:#a32626"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
                    <div class="primerio_input"><label for="email">
                            <h3>E-mail <samp>*</samp></h3>
                        </label><input class="tamanho" id="email" name="email" type="email" autocomplete="username" required value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></div>
                    <div class="primerio_input"><label for="senha">
                            <h3>Senha <samp>*</samp></h3>
                        </label><input class="tamanho" id="senha" name="senha" type="password" autocomplete="current-password" required></div>
                    <div class="primerio_input"><label for="instituicao">
                            <h3>Instituição <samp>*</samp></h3>
                        </label><select class="tamanho_select" id="instituicao" name="id_instituicao" required>
                            <option value="">Selecione</option><?php foreach ($instituicoes as $instituicao): ?><option value="<?= (int)$instituicao['id_instituicao'] ?>" <?= (string)($instituicao['id_instituicao']) === (string)($_POST['id_instituicao'] ?? '') ? 'selected' : '' ?>><?= htmlspecialchars($instituicao['nome'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?>
                        </select></div>
                </div>
                <div class="espaco_btn"><button class="btn" type="submit">Entrar</button></div>
            </form>
        </div>
        <div class="segundo_quadrado"><img class="img_logo" src="./imagem/SEGUNDA_LOGO.png" alt="Vanguard — Portal Educacional"></div>
    </main>
</body>

</html>