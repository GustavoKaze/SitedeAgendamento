<?php
include '../conexao.php';
session_start();
header('Content-Type: application/json');

// Segurança: Só Admin pode excluir aqui
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Sem permissão']);
    exit;
}

$dados = json_decode(file_get_contents("php://input"), true);
$id = $dados['id'];

try {
    $sql = "DELETE FROM agendamentos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao excluir']);
    }
} catch (PDOException $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
}
?>