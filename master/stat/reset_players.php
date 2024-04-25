<?php
include "../config.php";


// Ellenőrzés, hogy a POST adatok rendelkezésre állnak-e
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['homeTeamId']) && isset($_POST['awayTeamId'])) {
    $homeTeamId = $_POST['homeTeamId'];
    $awayTeamId = $_POST['awayTeamId'];
    $gameId = $_POST['gameId']; 

    // Játékosok aktivitásának visszaállítása
    $resetQuery = "UPDATE players SET active = 0 WHERE team_id IN (?, ?)";
    $stmt = $conn->prepare($resetQuery);
    if ($stmt) {
        $stmt->bind_param("ii", $homeTeamId, $awayTeamId);
        $stmt->execute();
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error preparing player reset query: ' . $conn->error]);
        exit;
    }

    // Mérkőzés törlése
    $deleteGame = "DELETE FROM games WHERE game_id = ?";
    $stmt = $conn->prepare($deleteGame);
    if ($stmt) {
        $stmt->bind_param("i", $gameId);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error preparing game deletion query: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Required data not provided.']);
}
?>
