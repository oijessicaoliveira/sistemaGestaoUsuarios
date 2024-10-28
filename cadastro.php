<?php
include_once 'includes/Database.php'; // Inclui o arquivo de conexão com o banco de dados.
include_once 'includes/Usuario.php'; // Inclui a classe Usuario para gerenciar dados do usuário.

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verifica se o formulário foi enviado via método POST.
    $usuario = new Usuario(); // Cria uma nova instância da classe Usuario.
    $usuario->nome = $_POST['nome']; // Atribui o valor do campo "nome" ao atributo "nome".
    $usuario->email = $_POST['email']; // Atribui o valor do campo "email" ao atributo "email".
    $usuario->senha = $_POST['senha']; // Atribui o valor do campo "senha" ao atributo "senha".
    $usuario->data_nascimento = $_POST['data_nascimento']; // Atribui a data de nascimento ao atributo "data_nascimento".
    $usuario->is_admin = isset($_POST['is_admin']) ? 1 : 0; // Define como admin caso a opção seja marcada.

    if (!empty($_FILES['imagem']['name'])) { // Verifica se uma imagem foi enviada.
        $targetDir = "uploads/"; // Define o diretório de destino para o upload.
        $fileName = basename($_FILES['imagem']['name']); // Obtém o nome do arquivo enviado.
        $targetFilePath = $targetDir . $fileName; // Define o caminho completo do arquivo no servidor.

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $targetFilePath)) { // Move o arquivo para o diretório destino.
            $usuario->imagem = $fileName; // Atribui o nome do arquivo ao atributo imagem do usuário.
        } else {
            echo "Erro ao fazer upload da imagem."; // Mensagem de erro caso o upload falhe.
            exit; // Encerra o script.
        }
    }

    if ($usuario->salvar()) { // Chama o método salvar() para inserir os dados no banco de dados.
        echo "Usuário cadastrado com sucesso!"; // Mensagem de sucesso.
    } else {
        echo "Erro ao cadastrar usuário."; // Mensagem de erro em caso de falha.
    }
}
?>