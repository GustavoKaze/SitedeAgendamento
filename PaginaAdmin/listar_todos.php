<?php
include '../conexao.php';
session_start();
header('Content-Type: application/json');

// Segurança: Só Admin entra
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo json_encode(['erro' => true, 'mensagem' => 'Acesso Negado']);
    exit;
}

try {
    // Pega dados do agendamento + nome e celular do cliente
    $sql = "SELECT 
                a.id, 
                DATE_FORMAT(a.data_agendamento, '%d/%m/%Y') as data_fmt, 
                a.horario, 
                u.usuario, 
                u.celular 
            FROM agendamentos a
            INNER JOIN usuarios u ON a.id_usuario = u.id
            ORDER BY a.data_agendamento ASC, a.horario ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (PDOException $e) {
    echo json_encode(['erro' => true, 'mensagem' => $e->getMessage()]);
}
?>