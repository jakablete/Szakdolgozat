<?php
session_start();
include "../config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['game_id'], $_POST['home_score'], $_POST['away_score'])) {
    $gameId = intval($_POST['game_id']);
    $homeScore = intval($_POST['home_score']);
    $awayScore = intval($_POST['away_score']);

    $query = "UPDATE games SET home_team_score = ?, away_team_score = ? WHERE game_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("iii", $homeScore, $awayScore, $gameId);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update scores: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare the query: ' . htmlspecialchars($conn->error)]);
    }
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request or missing parameters.']);
}
?>

