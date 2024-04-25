<?php
session_start();
include "../config.php";
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['game_id'])) {
    $gameId = intval($_GET['game_id']);

    $query = "SELECT player_id, assists, rebounds, steals, turnover, faults
              FROM game_player_stat
            --   JOIN players p ON p.player_id = ps.player_id
            WHERE game_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $gameId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats = [];
        while ($row = $result->fetch_assoc()) {
            $stats[] = $row;
        }
        $stmt->close();
        echo json_encode($stats);
    } else {
        echo json_encode(['error' => 'Query preparation failed: ' . $conn->error]);
    }
    $conn->close();
} else {
    echo json_encode(['error' => 'Invalid request or missing parameters.']);
}
?>
