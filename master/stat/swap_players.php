<?php
include '../config.php';

$activePlayerId = (int) $_POST['activePlayerId'];
$benchPlayerId  = (int) $_POST['benchPlayerId'];
$teamType       = trim($_POST['teamType']);

// Itt történik a cserélés, egy tranzakcióban javasolt
$conn->begin_transaction();
header('Content-Type: application/json');
try {
    $conn->query("UPDATE players SET active = 0 WHERE player_id = $activePlayerId");
    $conn->query("UPDATE players SET active = 1 WHERE player_id = $benchPlayerId");
    $conn->commit();
    echo json_encode([
			'success'  => true,
			'teamType' => $teamType,
			'activeId' => $benchPlayerId,
			'benchId'  => $activePlayerId,
    ]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
