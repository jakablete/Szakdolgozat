<?php
session_start();
include "../config.php";

// Helper function to generate HTML for player lists
function fetchPlayersHTML($conn, $teamId, $isActive) {
    $output = '';
    $sql = "SELECT player_id, player_name FROM players WHERE team_id = ? AND active = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error preparing statement: " . htmlspecialchars($conn->error);
        return '';
    }
    $stmt->bind_param("ii", $teamId, $isActive);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $output .= '<li>' . htmlspecialchars($row['player_name']) . 
                   ' - <button onclick="selectPlayer(' . $row['player_id'] . ', \'' . ($isActive ? 'active' : 'bench') . '\')">Select</button></li>';
    }
    $stmt->close();
    return $output;
}

// Read team IDs from the request
$teamId = $_GET['teamId'] ?? null;

if (!$teamId) {
    echo json_encode(['error' => 'No team ID provided']);
    exit;
}

// Fetch player lists for the team
$activePlayers = fetchPlayersHTML($conn, $teamId, 1);
$benchPlayers = fetchPlayersHTML($conn, $teamId, 0);

// Return HTML content as JSON
echo json_encode([
    'activePlayersHTML' => $activePlayers,
    'benchPlayersHTML' => $benchPlayers
]);
?>
