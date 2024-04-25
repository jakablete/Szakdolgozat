<?php
include "../config.php";  // Az adatbázis konfigurációs fájl betöltése

$team_id = isset($_GET['team_id']) ? intval($_GET['team_id']) : 0;
$response = [];

$sql = "SELECT player_id, player_name, jersey_number FROM players WHERE team_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    $stmt->close();
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
?>