<?php
session_start(); // Inicia a sessão para verificar o status de login do usuário.
if (!isset($_SESSION['usuario'])) { // Verifica se o usuário não está logado.
    header("Location: index.php"); // Redireciona para a página de login se o usuário não estiver logado.
    exit; // Encerra o script.
}

include_once 'includes/Usuario.php'; // Inclui a classe Usuario para manipular os dados do usuário.

$usuario = new Usuario(); // Cria uma nova instância da classe Usuario.
$usuario->id = $_SESSION['usuario']; // Define o ID do usuário logado a partir da sessão.

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagem'])) { // Verifica se o formulário foi enviado e se um arquivo foi enviado.
    $targetDir = "uploads/"; // Define o diretório de destino para o upload.
    $fileName = basename($_FILES['imagem']['name']); // Extrai o nome base do arquivo enviado.
    $targetFilePath = $targetDir . $fileName; // Define o caminho completo do arquivo no diretório de destino.

    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $targetFilePath)) { // Move o arquivo carregado para o diretório de destino.
        $usuario->imagem = $fileName; // Atribui o nome do arquivo ao atributo "imagem" do objeto usuário.
        echo "Imagem enviada com sucesso."; // Exibe uma mensagem de sucesso.
    } else {
        echo "Erro ao enviar a imagem."; // Exibe uma mensagem de erro caso o upload falhe.
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Upload de Imagem</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Envio de Imagem de Perfil</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="imagem" required>
        <button type="submit">Enviar Imagem</button>
    </form>
</body>
</html>