<?php
session_start();
include "../config.php";

function redirectWithError($error) {
    header("Location: teamadd.php?error=" . urlencode($error));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST['teamName'])) {
        redirectWithError('A csapatnév szükséges.');
    }
    if (empty($_POST['headCoach'])) {
        redirectWithError('Az edző neve szükséges.');
    }
    if (empty($_POST['jerseyNumber'])) {
        redirectWithError('Mezszámok megadása kötelező.');
    }
    if (empty($_POST['playerName']) || count($_POST['playerName']) < 5) {
        redirectWithError('Minimum 5 játékos nevet kell megadni.');
    }

    $teamName = mysqli_real_escape_string($conn, $_POST['teamName']);
    $coachName = mysqli_real_escape_string($conn, $_POST['headCoach']);
    $assCoach = mysqli_real_escape_string($conn, $_POST['assistantCoach']);
    $playerNames = $_POST['playerName'];
    $jerseyNumbers = $_POST['jerseyNumber'];

    mysqli_begin_transaction($conn);

    try {
        $teamInsertSQL = "INSERT INTO teams (team_name, head_coach, assistant_coach) VALUES ('$teamName', '$coachName', '$assCoach' )";
        if (!mysqli_query($conn, $teamInsertSQL)) {
            throw new Exception("Error inserting team data: " . mysqli_error($conn));
        }
        $teamID = mysqli_insert_id($conn);

        for ($i = 0; $i < count($playerNames); $i++) {
            $playerName = mysqli_real_escape_string($conn, $playerNames[$i]);
            $jerseyNumber = mysqli_real_escape_string($conn, $jerseyNumbers[$i]);

            $insertPlayerSQL = "INSERT INTO players (player_name, jersey_number, team_id) VALUES ('$playerName', '$jerseyNumber', $teamID)";
            if (!mysqli_query($conn, $insertPlayerSQL)) {
                throw new Exception("Error inserting player data: " . mysqli_error($conn));
            }
        }

        mysqli_commit($conn);
        header("Location: teams.php?success=Sikeresen hozzáadta a csapatot");

    } catch (Exception $e) {
        mysqli_rollback($conn);
        redirectWithError($e->getMessage());
    }
} else {
    redirectWithError('Nemjo.');
}
?>
