<?php
session_start();
include "../config.php";

$gameId = $_POST['game_id'] ?? null;
if (!$gameId) {
    echo json_encode(['error' => 'No game ID provided']);
    exit;
}

$sql = "SELECT gps.player_id, gps.two_points, gps.miss_two, gps.three_points, gps.miss_three, gps.miss_free_throw, gps.free_throw, gps.assists, gps.rebounds, gps.steals, gps.turnover, gps.faults, gps.team_id, p.player_name, t.team_name
        FROM game_player_stats gps
        INNER JOIN players p ON gps.player_id = p.player_id
        INNER JOIN teams t ON gps.team_id = t.team_id
        WHERE gps.game_id = ?
        ORDER BY gps.team_id, p.player_name";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $gameId);
$stmt->execute();
$result = $stmt->get_result();
$stats = [];

while ($row = $result->fetch_assoc()) {
    $stats[$row['team_id']]['team_name'] = $row['team_name'];
    $stats[$row['team_id']]['players'][] = [
        'player_id' => $row['player_id'],
        'player_name' => $row['player_name'],
        'two_points' => $row['two_points'],
        'miss_two' => $row['miss_two'],
        'three_points' => $row['three_points'],
        'miss_three' => $row['miss_three'],
        'free_throw' => $row['free_throw'],
        'miss_free_throw' => $row['miss_free_throw'],
        'assists' => $row['assists'],
        'rebounds' => $row['rebounds'],
        'steals' => $row['steals'],
        'turnover' => $row['turnover'],
        'faults' => $row['faults']
    ];
}
$stmt->close();
$conn->close();

echo json_encode($stats);
?>
