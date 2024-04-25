<?php
include "../config.php";

if (isset($_POST['team_id'])) {
    $team_id = $_POST['team_id'];

    // Játékosok lekérése az adatbázisból
    $sql = "SELECT player_id, player_name FROM players WHERE team_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<input type='checkbox' name='selected_players[]' value='" . $row['player_id'] . "'>" . $row['player_name'] . "<br>";
        }
    } else {
        echo "Nincsenek játékosok ebben a csapatban.";
    }
}
?>