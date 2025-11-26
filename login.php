<?php
include 'conexao.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    try {
        // Busca id, senha e nivel de acesso (is_admin)
        $sql = "SELECT id, senha, is_admin FROM usuarios WHERE usuario = :usuario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifica a criptografia da senha
            if (password_verify($senha, $dados['senha'])) {
                
                // Salva tudo na sessão
                $_SESSION['id_usuario'] = $dados['id'];
                $_SESSION['nome_usuario'] = $usuario;
                $_SESSION['is_admin'] = $dados['is_admin'];

                // --- O REDIRECIONAMENTO FINAL ---
                if ($dados['is_admin'] == 1) {
                    // Se for o Davi (Admin) -> Vai para o Painel de Controle
                    header("Location: PaginaAdmin/admin.html");
                } else {
                    // Se for Cliente -> Vai para o Menu de Agendamento
                    header("Location: PaginaOpcoes/opcoes.html");
                }
                exit;

            } else {
                echo "<script>alert('Senha incorreta!'); history.back();</script>";
            }
        } else {
            echo "<script>alert('Usuário não encontrado!'); history.back();</script>";
        }
    } catch (PDOException $e) {
        echo "Erro no sistema: " . $e->getMessage();
    }
}
?>