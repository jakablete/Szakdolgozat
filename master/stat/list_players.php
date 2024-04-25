<?php
include "../config.php";
header('Content-Type: application/json');

// Ellenőrizzük, hogy a kérés GET módszerrel érkezik-e és van-e érvényes csapat azonosító
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['teamId']) && is_numeric($_GET['teamId'])) {
    $teamId = intval($_GET['teamId']);
    $isActive = isset($_GET['active']) ? intval($_GET['active']) : 1;

    $response = ['success' => false, 'players' => []];
    $sql = "SELECT player_id, player_name, active FROM players WHERE team_id = ? AND active = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $teamId, $isActive);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $response['players'][] = [
                    'player_id' => $row['player_id'],
                    'player_name' => $row['player_name'],
                    'active' => $row['active']
                ];
            }
            $response['success'] = true;
        }
        $stmt->close();
    }
    echo json_encode($response);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
