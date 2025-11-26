<?php
include '../conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];
    $senha = $_POST['senha'];
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO usuarios (usuario, email, celular, senha) VALUES (:usuario, :email, :celular, :senha)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':celular', $celular);
        $stmt->bindParam(':senha', $senhaHash);

        if ($stmt->execute()) {
            session_start();
            $_SESSION['id_usuario'] = $pdo->lastInsertId();
            $_SESSION['nome_usuario'] = $usuario;

            // REDIRECIONA PARA A TELA DE OPÇÕES
            echo "<script>
                    alert('Cadastro com sucesso! Bem-vindo.');
                    window.location.href = '../PaginaOpcoes/opcoes.html';
                  </script>";
        } else {
            echo "<script>alert('Erro ao cadastrar.'); history.back();</script>";
        }
    } catch (PDOException $e) {
        echo "Erro no banco: " . $e->getMessage();
    }
}
?>