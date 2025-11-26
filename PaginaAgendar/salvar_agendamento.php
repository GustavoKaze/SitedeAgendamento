<?php
include '../conexao.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Você precisa estar logado!']);
    exit;
}

$dados = json_decode(file_get_contents("php://input"), true);

if ($dados) {
    $id = $_SESSION['id_usuario'];
    $data = $dados['data'];
    $hora = $dados['horario'];

    try {
        // Verifica se já não pegaram o horário
        $check = $pdo->prepare("SELECT id FROM agendamentos WHERE data_agendamento = :d AND horario = :h");
        $check->execute([':d' => $data, ':h' => $hora]);

        if ($check->rowCount() > 0) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Horário indisponível.']);
            exit;
        }

        $sql = "INSERT INTO agendamentos (id_usuario, data_agendamento, horario) VALUES (:id, :data, :hora)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id, ':data' => $data, ':hora' => $hora]);

        echo json_encode(['sucesso' => true, 'mensagem' => 'Agendado com sucesso!']);
    } catch (PDOException $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro: ' . $e->getMessage()]);
    }
}
?>