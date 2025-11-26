<?php
include '../conexao.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['erro' => true, 'mensagem' => 'Não logado']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

try {
    // O SEGREDO ESTÁ AQUI: "as data_formatada"
    $sql = "SELECT id, DATE_FORMAT(data_agendamento, '%d/%m/%Y') as data_formatada, horario 
            FROM agendamentos 
            WHERE id_usuario = :id 
            ORDER BY data_agendamento ASC, horario ASC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_usuario);
    $stmt->execute();

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (PDOException $e) {
    echo json_encode(['erro' => true, 'mensagem' => $e->getMessage()]);
}
?>