<?php
// Inclui o arquivo 'Database.php', que contém a classe responsável pela conexão com o banco de dados.
// Isso permite que o código use a conexão com o banco sem reescrever a lógica de conexão.
include_once 'Database.php';

class Usuario { // Define a classe 'Usuario', que representa um usuário no sistema.
    
    // Declara a variável protegida que armazenará a conexão com o banco de dados.
    protected $conn;
    
    // Declara as variáveis públicas que representam as propriedades do usuário:
    public $nome; // Nome do usuário.
    public $email; // Email do usuário.
    public $senha; // Senha do usuário.
    public $imagem; // Nome do arquivo de imagem de perfil do usuário.
    public $data_nascimento; // Data de nascimento do usuário.
    public $is_admin = 0; // Indica se o usuário é administrador (valor padrão: 0, não-administrador).

    // Construtor da classe 'Usuario'. Esse método é chamado automaticamente ao instanciar a classe.
    public function __construct() {
        // Cria uma instância da classe 'Database', que contém a lógica de conexão com o banco.
        $database = new Database();
        // Atribui a conexão retornada pelo método 'getConnection' à variável $this->conn para uso em métodos da classe.
        $this->conn = $database->getConnection();
    }

    // Método público para salvar um novo usuário no banco de dados.
    public function salvar() {
        // Define a consulta SQL para inserir os dados do usuário na tabela 'usuarios'.
        $query = "INSERT INTO usuarios SET nome=:nome, email=:email, senha=:senha, imagem=:imagem, data_nascimento=:data_nascimento, is_admin=:is_admin";
        
        // Prepara a consulta SQL, tornando-a segura contra injeção de SQL.
        $stmt = $this->conn->prepare($query);

        // Associa os valores das propriedades da classe (definidas previamente) aos placeholders da consulta:
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        
        // Hash da senha do usuário para garantir segurança antes de armazená-la no banco.
        // A função password_hash agora é armazenada em uma variável para evitar o erro de referência.
        $senhaHash = password_hash($this->senha, PASSWORD_DEFAULT);
        $stmt->bindParam(":senha", $senhaHash);
        
        $stmt->bindParam(":imagem", $this->imagem);
        $stmt->bindParam(":data_nascimento", $this->data_nascimento);
        $stmt->bindParam(":is_admin", $this->is_admin);

        // Executa a consulta e retorna o resultado (true para sucesso ou false para falha).
        return $stmt->execute();
    }

    // Método público para login, que verifica as credenciais do usuário e inicia a sessão se forem válidas.
    public function login($email, $senha) {
        // Define a consulta SQL para buscar o usuário pelo email fornecido.
        $query = "SELECT * FROM usuarios WHERE email=:email";
        
        // Prepara a consulta para proteger contra injeção de SQL.
        $stmt = $this->conn->prepare($query);

        // Associa o email fornecido ao placeholder da consulta.
        $stmt->bindParam(":email", $email);
        
        // Executa a consulta.
        $stmt->execute();

        // Verifica se a consulta encontrou algum usuário com o email fornecido.
        if ($stmt->rowCount() > 0) {
            // Se encontrado, recupera os dados do usuário como um array associativo.
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifica se a senha fornecida corresponde à senha armazenada no banco (usando password_verify).
            if (password_verify($senha, $row['senha'])) {
                // Se a senha for válida, inicia a sessão e define as variáveis de sessão.
                session_start();
                $_SESSION['usuario'] = $row['id']; // Armazena o ID do usuário na sessão.
                $_SESSION['is_admin'] = $row['is_admin']; // Armazena o status de administrador.
                return true; // Retorna true para indicar sucesso no login.
            }
        }
        // Se o email não for encontrado ou a senha estiver incorreta, retorna false.
        return false;
    }

    // Método público que retorna o painel do usuário. Este método é polimórfico e pode ser sobrescrito por subclasses.
    public function getPainel() {
        // Retorna um texto de boas-vindas personalizado para o usuário.
        return "<h3>Painel do Usuário</h3><p>Bem-vindo, {$this->nome}!</p>";
    }
}
?>