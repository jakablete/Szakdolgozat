<?php
session_start();
include "../config.php";

$homeTeamId = $_GET['homeTeamId'] ?? null;
$awayTeamId = $_GET['awayTeamId'] ?? null;

$response = ['home' => [], 'away' => []];

if ($homeTeamId) {
    $query = "SELECT player_id, player_name FROM players WHERE team_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $homeTeamId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $response['home'][] = $row;
    }
}

if ($awayTeamId) {
    $query = "SELECT player_id, player_name FROM players WHERE team_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $awayTeamId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $response['away'][] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($response,
	  JSON_INVALID_UTF8_IGNORE
	| JSON_UNESCAPED_LINE_TERMINATORS
	| JSON_UNESCAPED_SLASHES
	| JSON_UNESCAPED_UNICODE
);
?>