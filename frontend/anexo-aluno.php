<?php
require_once __DIR__ . '/../backend/config/auth.php';
exigirPerfil(['aluno']);

$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if(!$id){http_response_code(404);exit('Anexo não encontrado.');}

$stmt=getConnection()->prepare('SELECT nome_original,caminho,tipo_mime FROM anexos_aluno WHERE id_anexo=:id AND id_usuario=:aluno');
$stmt->execute([':id'=>$id,':aluno'=>(int)usuarioAtual()['id_usuario']]);
$anexo=$stmt->fetch();
$diretorio=realpath(__DIR__.'/uploads/anexos');
$arquivo=$anexo?realpath(__DIR__.'/'.$anexo['caminho']):false;
if(!$anexo||!$diretorio||!$arquivo||!str_starts_with($arquivo,$diretorio.DIRECTORY_SEPARATOR)||!is_file($arquivo)){
    http_response_code(404);exit('Anexo não encontrado.');
}

$nome=basename($anexo['nome_original']);
header('Content-Type: '.$anexo['tipo_mime']);
header('Content-Length: '.filesize($arquivo));
header("Content-Disposition: attachment; filename=\"anexo\"; filename*=UTF-8''".rawurlencode($nome));
header('X-Content-Type-Options: nosniff');
readfile($arquivo);
exit;
