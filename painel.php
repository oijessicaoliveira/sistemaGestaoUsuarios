<?php
// Inicia uma nova sessão ou retoma uma sessão existente, permitindo o uso de variáveis de sessão.
session_start();

// Verifica se a sessão 'usuario' não está definida, o que significa que o usuário não está logado.
if (!isset($_SESSION['usuario'])) {
    // Redireciona o usuário para a página 'index.php' se ele não estiver logado.
    header("Location: index.php");
    exit; // Encerra a execução do script para garantir o redirecionamento imediato.
}

// Inclui as classes 'Usuario' e 'Admin', necessárias para instanciar objetos dessas classes.
include_once 'includes/Usuario.php';
include_once 'includes/Admin.php';

// Verifica se o usuário logado tem o status de administrador.
if ($_SESSION['is_admin'] == 1) {
    // Se o usuário for administrador (is_admin igual a 1), cria uma instância da classe 'Admin'.
    $usuario = new Admin();
} else {
    // Caso contrário, cria uma instância da classe 'Usuario' para um usuário comum.
    $usuario = new Usuario();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Usuário</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
// Exibe o painel apropriado chamando o método polimórfico 'getPainel()' da classe instanciada.
// O método exibe uma saudação personalizada dependendo de ser um administrador ou um usuário comum.
echo $usuario->getPainel();

// Verifica se a instância de $usuario é da classe 'Admin' para exibir a lista de usuários apenas para administradores.
if ($usuario instanceof Admin) {
    // Chama o método 'listarUsuarios()' para obter uma lista de todos os usuários no sistema.
    $usuarios = $usuario->listarUsuarios();
?>

    <!-- Exibe a lista de usuários em formato de tabela HTML -->
    <h3>Lista de Usuários:</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Data de Nascimento</th>
            <th>Imagem</th>
        </tr>

        <!-- Percorre o array associativo $usuarios para exibir cada usuário na tabela -->
        <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?php echo $u['id']; ?></td> <!-- Exibe o ID do usuário -->
            <td><?php echo $u['nome']; ?></td> <!-- Exibe o nome do usuário -->
            <td><?php echo $u['email']; ?></td> <!-- Exibe o email do usuário -->
            <td><?php echo $u['data_nascimento']; ?></td> <!-- Exibe a data de nascimento do usuário -->
            <td>
                <?php if ($u['imagem']): ?>
                    <!-- Se o usuário tiver uma imagem de perfil, exibe a imagem com um link para a pasta de uploads -->
                    <img src="uploads/<?php echo $u['imagem']; ?>" alt="Imagem de Perfil" width="50">
                <?php else: ?>
                    <!-- Caso contrário, exibe a mensagem 'Sem imagem' -->
                    Sem imagem
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php } ?>
    <!-- Link para alterar a imagem de perfil do usuário -->
    <a href="upload.php">Alterar Imagem de Perfil</a>
    <!-- Link para fazer logout do sistema -->
    <a href="logout.php">Sair</a>
</body>
</html>
