<?php
include "../config.php";

$jersey_number = $_POST['jersey_number'];
$player_name = $_POST['player_name'];
$team_id = $_POST['team_id'];

$sql = "INSERT INTO players (jersey_number, player_name, team_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isi", $jersey_number, $player_name, $team_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Új játékos sikeresen hozzáadva.";
} else {
    echo "Hiba történt a játékos hozzáadása közben.";
}

$stmt->close();
$conn->close();
?>
