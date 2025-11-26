<?php
include '../conexao.php'; // Verifique se o conexao.php está na pasta anterior a essa!
session_start();
header('Content-Type: application/json');

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Sessão expirada. Faça login novamente.']);
    exit;
}

// Recebe os dados do JSON
$dados = json_decode(file_get_contents("php://input"), true);
$id_agendamento = $dados['id_agendamento'];
$id_usuario = $_SESSION['id_usuario'];

try {
    // Só deleta se o ID pertencer ao usuário logado (Segurança)
    $sql = "DELETE FROM agendamentos WHERE id = :id_agend AND id_usuario = :id_user";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_agend', $id_agendamento);
    $stmt->bindParam(':id_user', $id_usuario);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Agendamento cancelado!']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro: Agendamento não encontrado ou não é seu.']);
        }
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no banco de dados.']);
    }
} catch (PDOException $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro SQL: ' . $e->getMessage()]);
}
?>