<?php
// Inclui o arquivo 'Usuario.php', que contém a definição da classe 'Usuario'.
// Isso permite que a classe 'Admin' herde a funcionalidade da classe 'Usuario'.
include_once 'Usuario.php';

class Admin extends Usuario { // Define a classe 'Admin', que estende (herda) a classe 'Usuario'.

    // A classe 'Admin' herda o construtor da classe 'Usuario', que estabelece a conexão com o banco de dados.
    // Portanto, não é necessário redefini-lo aqui.

    // Redefine (sobrescreve) o método 'getPainel' para retornar um painel específico para o administrador.
    public function getPainel() {
        // Método polimórfico - Retorna um painel de boas-vindas exclusivo para o administrador.
        // Utiliza o nome do administrador e fornece uma mensagem informativa sobre o gerenciamento de usuários.
        return "<h3>Painel do Administrador</h3><p>Olá! Aqui você pode gerenciar todos os usuários.</p>";
    }

    // Método exclusivo do administrador para listar todos os usuários cadastrados no sistema.
    public function listarUsuarios() {
        // Define a consulta SQL para selecionar informações de todos os usuários na tabela 'usuarios'.
        // Essa consulta recupera o 'id', 'nome', 'email', 'data_nascimento' e 'imagem' de cada usuário.
        $query = "SELECT id, nome, email, data_nascimento, imagem FROM usuarios";

        // Prepara a consulta para execução, usando a conexão com o banco de dados herdada.
        $stmt = $this->conn->prepare($query);

        // Executa a consulta.
        $stmt->execute();

        // Recupera todos os resultados da consulta em formato de array associativo (chave-valor),
        // onde cada linha representa um usuário, e retorna esse array.
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>