<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Csapat kiválasztása</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="script.js"></script>
</head>
<body>
<form id="teamForm">
    <label for="home_team">Hazai csapat:</label>
    <select name="home_team" id="home_team" onchange="showPlayers()">
            <?php
            include "../config.php";

            // Retrieve teams from the database
            $sql = "SELECT team_id, team_name FROM teams";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value=\"" . $row['team_id'] . "\">" . $row['team_name'] . "</option>";
                }
            }
            ?>
        </select>

        <label for="away_team">Vendég csapat:</label>
    <select name="away_team" id="away_team" onchange="showPlayers()">
        <?php
            include "../config.php";

            // Retrieve teams from the database
            $sql = "SELECT team_id, team_name FROM teams";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value=\"" . $row['team_id'] . "\">" . $row['team_name'] . "</option>";
                }
            }
            ?>
        </select>

        <input type="submit" value="Csapatok kiválasztása">
    </form>
    <div id="playersList">
    <!-- Ide kerülnek a játékosok -->
    </div>

</body>

</html>
