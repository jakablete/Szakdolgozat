<?php
session_start();
include "../config.php";

$data = json_decode(file_get_contents("php://input"), true);
$game_id = $data['game_id'];
$players = $data['players'];

foreach ($players as $player) {
    $query = "INSERT INTO game_player_stats (game_id, player_id, team_id, two_points, miss_two, three_points, miss_three, free_throw, miss_free_throw, steals, turnover, rebounds, assists, faults) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("iiiiiiiiiiiiii", $game_id, $player['player_id'], $player['team_id'], $player['two_points'], $player['miss_two'], $player['three_points'], $player['miss_three'], $player['free_throw'], $player['miss_free_throw'], $player['steals'], $player['turnovers'], $player['rebounds'], $player['assists'], $player['faults']);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Failed to prepare the game_player_stats insertion query: " . htmlspecialchars($conn->error);
    }

    $updateQuery = "UPDATE players SET active = 0 WHERE player_id = ?";
    if ($updateStmt = $conn->prepare($updateQuery)) {
        $updateStmt->bind_param("i", $player['player_id']);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        echo "Failed to prepare the player active status update query: " . htmlspecialchars($conn->error);
    }
}

echo "Data saved successfully!";
?>
