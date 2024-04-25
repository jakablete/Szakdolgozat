<?php
include "../config.php"; // Adatbázis kapcsolat

$player_id = $_POST['player_id'];
$jersey_number = $_POST['jersey_number'];
$player_name = $_POST['player_name'];

$sql = "UPDATE players SET jersey_number = ?, player_name = ? WHERE player_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("isi", $jersey_number, $player_name, $player_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "Játékos adatai sikeresen frissítve.";
    } else {
        echo "Nem történt módosítás.";
    }
    $stmt->close();
} else {
    echo "Hiba történt: " . $conn->error;
}

$conn->close();
?>
