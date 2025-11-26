<?php
include '../conexao.php';
header('Content-Type: application/json');

if (isset($_GET['data'])) {
    $data = $_GET['data'];
    // Grade de horários do barbeiro
    $padrao = ["09:00", "10:00", "11:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00"];

    try {
        $stmt = $pdo->prepare("SELECT horario FROM agendamentos WHERE data_agendamento = :data");
        $stmt->bindParam(':data', $data);
        $stmt->execute();
        $ocupados = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Remove os ocupados da lista padrão
        $livres = array_diff($padrao, $ocupados);
        echo json_encode(array_values($livres));
    } catch (PDOException $e) {
        echo json_encode([]);
    }
}
?>