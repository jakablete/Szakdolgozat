<?php
session_start();
include "../config.php";

if (!isset($_POST['home_team'])) {
    die('Home team is not selected.');
} else {
    $homeTeamId = $_POST['home_team'];
}

if (!isset($_POST['away_team'])) {
    die('Away team is not selected.');
} else {
    $awayTeamId = $_POST['away_team'];
}

$homeTeamId = $_POST['home_team'];
$awayTeamId = $_POST['away_team'];

// Fetch and store team names
$homeTeamName = getTeamName($conn, $homeTeamId);
$awayTeamName = getTeamName($conn, $awayTeamId);


function getTeamName($conn, $teamId) {
    $teamName = '';
    if ($stmt = $conn->prepare("SELECT team_name FROM teams WHERE team_id = ?")) {
        $stmt->bind_param("i", $teamId);
        $stmt->execute();
        $stmt->bind_result($teamName);
        $stmt->fetch();
        $stmt->close();
    } else {
        echo "Failed to prepare the query: " . htmlspecialchars($conn->error);
    }
    return $teamName;
}

$homeTeamName = getTeamName($conn, $homeTeamId);
$awayTeamName = getTeamName($conn, $awayTeamId);
function updatePlayerActivity($conn, $playerIds) {
    if (!empty($playerIds) && is_array($playerIds)) {
        foreach ($playerIds as $player_id) {
            $stmt = $conn->prepare("UPDATE players SET active = 1 WHERE player_id = ?");
            if ($stmt) {
                $stmt->bind_param("i", $player_id);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "Failed to prepare the query: " . htmlspecialchars($conn->error);
            }
        }
    }
}

updatePlayerActivity($conn, $_POST['home_players']);
updatePlayerActivity($conn, $_POST['away_players']);


function displayPlayersByTeam($conn, $teamId, $isActive, $teamType) {
    //$sql = "SELECT player_id, jersey_number, player_name FROM players WHERE team_id = ? AND active = ?";
    $sql = "SELECT player_id, jersey_number, player_name, active FROM players WHERE team_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        //$stmt->bind_param("ii", $teamId, $isActive);
        $stmt->bind_param("i", $teamId);
        $stmt->execute();
        $result = $stmt->get_result();
        echo '<ul data-team-type="'.$teamType.'" data-status="'.$isActive.'">';
        while ($row = $result->fetch_assoc()) {
            echo '<li style="margin-left:30px" data-player-id="'.$row['player_id'].'" '.($row['active'] == $isActive ? '' : 'hidden').'>' . "#" . htmlspecialchars($row['jersey_number']) . " " . htmlspecialchars($row['player_name']);
            if ($isActive) {
                echo "<div class='player-buttons'>";
                echo "<button style=\"background:green\" onclick=\"addPoint('$teamType', 1,{$row['player_id']}); incrementStat('{$row['player_id']}', 'freethrow'); incrementStat('{$row['player_id']}', 'missfreethrow');\">1pt</button>
                      <button style=\"background:red\" onclick=\"incrementStat('{$row['player_id']}', 'missfreethrow');\">1pt</button>
                      <button style=\"background:green\" onclick=\"addPoint('$teamType', 2,{$row['player_id']}); incrementStat('{$row['player_id']}', 'twopoints'); incrementStat('{$row['player_id']}', 'misstwo');\">2pt</button>
                      <button style=\"background:red\" onclick=\"incrementStat('{$row['player_id']}', 'misstwo');\">2pt</button>
                      <button style=\"background:green\" onclick=\"addPoint('$teamType', 3,{$row['player_id']}); incrementStat('{$row['player_id']}', 'threepoints'); incrementStat('{$row['player_id']}', 'missthree');\">3pt</button>
                      <button style=\"background:red\" onclick=\"incrementStat('{$row['player_id']}', 'missthree');\">3pt</button>
                      <button style=\"background:green\" onclick=\"incrementStat('{$row['player_id']}', 'steal');\">Szerzett l.</button>
                      <button style=\"background:red\" onclick=\"incrementStat('{$row['player_id']}', 'turnover');\">Eladott l.</button>
                      <button style=\"background:green\" onclick=\"incrementStat('{$row['player_id']}', 'rebound');\">Lepattanó</button>
                      <button style=\"background:green\" onclick=\"incrementStat('{$row['player_id']}', 'assists');\">Gólpassz</button>
                      <button style=\"background:red\" onclick=\"incrementStat('{$row['player_id']}', 'fault');\">Fault</button>";
                echo "</div></li>";
            }
        }
        echo "</ul>";
        $stmt->close();
    } else {
        echo "Query preparation failed: " . htmlspecialchars($conn->error);
    }
}


function generatePlayerOptions($conn, $teamId, $isActive) {
    $sql = "SELECT player_id, player_name FROM players WHERE team_id = ? AND active = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $teamId, $isActive);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($player = $result->fetch_assoc()) {
            echo "<option value='" . $player['player_id'] . "'>" . htmlspecialchars($player['player_name']) . "</option>";
        }
        $stmt->close();
    }
}

$currentDate = date('Y-m-d H:i:s');
$query = "INSERT INTO games (home_team_id, away_team_id, game_date, away_team_name, home_team_name) VALUES (?, ?, ?, ?, ?)";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("iisss", $homeTeamId, $awayTeamId, $currentDate, $awayTeamName, $homeTeamName);
    if ($stmt->execute()) {
        // Mentse a game_id-t a munkamenetbe
        $_SESSION['game_id'] = $conn->insert_id;
        echo "";
    } else {
        echo "Error initiating the match: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Failed to prepare the game insertion query: " . htmlspecialchars($conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mérkőzés</title>
    <link rel="stylesheet" type="text/css" href="../admin/admin.css">
    <link rel="stylesheet" href="scoreboard.css">
</head>
<body>
<button onclick="closeGameAndResetPlayers()">X</button>
<button style="margin-left:85%" id="endMatchButton">
    Mérkőzés vége
</button>
<button style="margin-left:90%; background-color:red" onclick="undoOperation()">Stat. vissza</button>
<div id="scoreboard">
    <div class="team" id="homeTeam">
        <h2><?php echo htmlspecialchars($homeTeamName); ?></h2>
    </div>
    <div class="center-text">
        <h2>vs</h2>
    </div>
    <div class="team" id="awayTeam">
        <h2><?php echo htmlspecialchars($awayTeamName); ?></h2>
    </div>
</div>
<div id="score">
    <h3 id="homeScore">0</h3>
    <div class="center-text">
        <h3>-</h3>
    </div>
    <h3 id="awayScore">0</h3>
</div>
<div id="teams">
    <div>
        <h2>Hazai játékosok</h2>
        <?php displayPlayersByTeam($conn, $homeTeamId, 1, 'home'); ?>
        <h2>Hazai csere játékosok</h2>
        <?php displayPlayersByTeam($conn, $homeTeamId, 0, 'home'); ?>
        <button onclick="homeshowSwapWindow('home')">Hazai Csere</button>
    </div>
    <div id="homeswapWindow" style="display:none;">
        <h3>Játékos Csere</h3>
        <select id="homeActivePlayers">
            <?php generatePlayerOptions($conn, $homeTeamId, 1); ?>
        </select>
        <select id="homeBenchPlayers">
            <?php generatePlayerOptions($conn, $homeTeamId, 0); ?>
        </select>
        <button onclick="performSwap('home')">Csere</button>
        <button onclick="closeSwapWindow()">Bezárás</button>
    </div>
    

    <div>
        <h2>Vendég játékosok</h2>
        <?php displayPlayersByTeam($conn, $awayTeamId, 1, 'away'); ?>
        <h2>Vendég csere játékosok</h2>
        <?php displayPlayersByTeam($conn, $awayTeamId, 0, 'away'); ?>
        <button onclick="awayshowSwapWindow('away')">Vendég Csere</button>
    </div>
    <div id="awayswapWindow" style="display:none;">
        <h3>Játékos Csere</h3>
        <select id="awayActivePlayers">
            <?php generatePlayerOptions($conn, $awayTeamId, 1); ?>
        </select>
        <select id="awayBenchPlayers">
            <?php generatePlayerOptions($conn, $awayTeamId, 0); ?>
        </select>
        <button onclick="performSwap('away')">Csere</button>
        <button onclick="closeSwapWindow()">Bezárás</button>
    </div>
</div>
<?php
// Hazai csapat játékosainak és pontjaiknak lekérdezése
$homePlayersQuery = "SELECT team_id, jersey_number, player_name, player_id FROM players WHERE team_id = ? ";
$stmt = $conn->prepare($homePlayersQuery);
$stmt->bind_param("i", $homeTeamId);
$stmt->execute();
$homePlayersResult = $stmt->get_result();

// Vendég csapat játékosainak és pontjaiknak lekérdezése
$awayPlayersQuery = "SELECT team_id, jersey_number, player_name, player_id FROM players WHERE team_id = ?";
$stmt = $conn->prepare($awayPlayersQuery);
$stmt->bind_param("i", $awayTeamId);
$stmt->execute();
$awayPlayersResult = $stmt->get_result();
?>
<div class="cimek" style="display:flex">
    <h2>Hazai Csapat Játékosai és Pontszámaik</h2>
    <h2 style="margin-left:27%">Vendég Csapat Játékosai és Pontszámaik</h2>
</div>

<div id="table">
    <table style="border: 5px solid black; border-collapse: collapse;">
        <tr>
            <th style="border: 2px solid black;">Mezszám</th>
            <th style="display:none">Játékos Neve</th>
            <th style="display:none">Team ID</th>
            <th style="border: 2px solid black;">2 pontos</th>
            <th style="border: 2px solid black;">3 pontos</th>
            <th style="border: 2px solid black;">Büntető</th>
            <th style="border: 2px solid black;">Szerzett labda</th>
            <th style="border: 2px solid black;">Eladott labda</th>
            <th style="border: 2px solid black;">Lepattanó</th>
            <th style="border: 2px solid black;">Gólpassz</th>
            <th style="border: 2px solid black;">Fault</th>
        </tr>
        <?php while ($row = $homePlayersResult->fetch_assoc()): ?>
        <tr>
            <td style="display:none";><?php echo htmlspecialchars($row['player_id']); ?></td>
            <td style="border: 2px solid black;"><?php echo htmlspecialchars($row['jersey_number']); ?></td>
            <td style="display:none"><?php echo htmlspecialchars($row['player_name']); ?></td>
            <td style="display:none" data-team-id="<?php echo htmlspecialchars($row['team_id']); ?>"></td>
            <td style="border: 2px solid black;"><span id='twopoints-<?php echo $row['player_id']; ?>'>0</span>/<span id='misstwo-<?php echo $row['player_id']; ?>'>0</span></td>
            <td style="border: 2px solid black;"><span id='threepoints-<?php echo $row['player_id']; ?>'>0</span>/<span id='missthree-<?php echo $row['player_id']; ?>'>0</span></td>
            <td style="border: 2px solid black;"><span id='freethrow-<?php echo $row['player_id']; ?>'>0</span>/<span id='missfreethrow-<?php echo $row['player_id']; ?>'>0</span></td>
            <td style="border: 2px solid black;" id='steal-<?php echo $row['player_id']; ?>'>0</td>
            <td style="border: 2px solid black;" id='turnover-<?php echo $row['player_id']; ?>'>0</td>
            <td style="border: 2px solid black;" id='rebound-<?php echo $row['player_id']; ?>'>0</td>
            <td style="border: 2px solid black;" id='assists-<?php echo $row['player_id']; ?>'>0</td>
            <td style="border: 2px solid black;" id='fault-<?php echo $row['player_id']; ?>' data-player-name='<?php echo htmlspecialchars($row['player_name']); ?>'>0</td>
            
            
        </tr>
        <?php endwhile; ?>
    </table>

    <table style="border: 5px solid black; border-collapse: collapse;">
        <tr>
            <th style="border: 2px solid black;">Mezszám</th>
            <th style="display:none">Játékos Neve</th>
            <th style="display:none">Team ID</th>
            <th style="border: 2px solid black;">2 pontos</th>
            <th style="border: 2px solid black;">3 pontos</th>
            <th style="border: 2px solid black;">Büntető</th>
            <th style="border: 2px solid black;">Szerzett labda</th>
            <th style="border: 2px solid black;">Eladott labda</th>
            <th style="border: 2px solid black;">Lepattanó</th>
            <th style="border: 2px solid black;">Gólpassz</th>
            <th style="border: 2px solid black;">Fault</th>
        </tr>
        <?php while ($row = $awayPlayersResult->fetch_assoc()): ?>
        <tr>
            <td style="display:none";><?php echo htmlspecialchars($row['player_id']); ?></td>
            <td style="border: 2px solid black;"><?php echo htmlspecialchars($row['jersey_number']); ?></td>
            <td style="display:none"><?php echo htmlspecialchars($row['player_name']); ?></td>
            <td style="display:none" data-team-id="<?php echo htmlspecialchars($row['team_id']); ?>"></td>
            <td style="border: 2px solid black;"><span id='twopoints-<?php echo $row['player_id']; ?>'>0</span>/<span id='misstwo-<?php echo $row['player_id']; ?>'>0</span></td>
            <td style="border: 2px solid black;"><span id='threepoints-<?php echo $row['player_id']; ?>'>0</span>/<span id='missthree-<?php echo $row['player_id']; ?>'>0</span></td>
            <td style="border: 2px solid black;"><span id='freethrow-<?php echo $row['player_id']; ?>'>0</span>/<span id='missfreethrow-<?php echo $row['player_id']; ?>'>0</span></td>
            <td style="border: 2px solid black;" id='steal-<?php echo $row['player_id']; ?>'>0</td>
            <td style="border: 2px solid black;" id='turnover-<?php echo $row['player_id']; ?>'>0</td>
            <td style="border: 2px solid black;" id='rebound-<?php echo $row['player_id']; ?>'>0</td>
            <td style="border: 2px solid black;" id='assists-<?php echo $row['player_id']; ?>'>0</td>
            <td style="border: 2px solid black;" id='fault-<?php echo $row['player_id']; ?>' data-player-name='<?php echo htmlspecialchars($row['player_name']); ?>'>0</td>

        </tr>
        <?php endwhile; ?>
    </table>

</div>
<script>
var homeTeamId = <?php echo json_encode($homeTeamId); ?>;
var awayTeamId = <?php echo json_encode($awayTeamId); ?>;
var gameId = <?php echo json_encode($_SESSION['game_id'] ?? 0); ?>;



function performSwap(team) {
    var activePlayerSelect = document.getElementById(team + 'ActivePlayers');
    var benchPlayerSelect = document.getElementById(team + 'BenchPlayers');

    if (!activePlayerSelect || !benchPlayerSelect) {
        console.error(team + " swap elements not found.");
        return;
    }

    var activePlayerId = activePlayerSelect.value;
    var benchPlayerId = benchPlayerSelect.value;

    if (!activePlayerId || !benchPlayerId) {
        console.error(team + " active or bench player ID not found.");
        return;
    }

    fetch('swap_players.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `activePlayerId=${activePlayerId}&benchPlayerId=${benchPlayerId}&teamType=${team}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log('Swap successful');
            //updatePlayerLists(team); // Frissíti a játékos listát
            updatePlayerLists(team, data); // Frissíti a játékos listát
            closeSwapWindow(team); // Bezárja a csereablakot
        } else {
            console.error('Swap failed:', data.message);
        }
    })
    .catch(error => console.error('Error performing swap:', error));
}


function homeshowSwapWindow() {
    document.getElementById('homeswapWindow').style.display = 'block';
}

function awayshowSwapWindow() {
    document.getElementById('awayswapWindow').style.display = 'block';
}

function closeSwapWindow() {
    document.getElementById('homeswapWindow').style.display = 'none';
    document.getElementById('awayswapWindow').style.display = 'none';
}


function updatePlayerLists(team, data) {
		let toShowPlayer1 = document.querySelector(`div#teams > div > ul[data-status="1"][data-team-type="${data.teamType}"] > li[data-player-id="${data.activeId}"]`);
		let toShowPlayer2 = document.querySelector(`div#teams > div > ul[data-status="0"][data-team-type="${data.teamType}"] > li[data-player-id="${data.benchId}"]`);
		let toHidePlayer1 = document.querySelector(`div#teams > div > ul[data-status="1"][data-team-type="${data.teamType}"] > li[data-player-id="${data.benchId}"]`);
		let toHidePlayer2 = document.querySelector(`div#teams > div > ul[data-status="0"][data-team-type="${data.teamType}"] > li[data-player-id="${data.activeId}"]`);
		toShowPlayer1.removeAttribute('hidden');
		toShowPlayer2.removeAttribute('hidden');
		toHidePlayer1.setAttribute('hidden', 'hidden');
		toHidePlayer2.setAttribute('hidden', 'hidden');
}

let operations = [];

function undoOperation() {
  if (operations.length > 0) {
    const lastOperation = operations.pop();
    switch (lastOperation.type) {
      case 'score':
        undoScore(lastOperation.team, lastOperation.points);
        break;
      case 'stat':
        decrementStat(lastOperation.playerId, lastOperation.statType);
        break;
    }
  }
}

function undoScore(team, points) {
  const scoreElementId = team + 'Score';
  const scoreElement = document.getElementById(scoreElementId);
  if (scoreElement) {
    const currentScore = parseInt(scoreElement.textContent, 10) - points;
    scoreElement.textContent = currentScore;
  }
}

function decrementStat(playerId, statType) {
  const statElementId = statType + '-' + playerId;
  const statElement = document.getElementById(statElementId);
  if (statElement) {
    const currentStat = parseInt(statElement.innerText, 10) - 1;
    statElement.innerText = currentStat;
  }
}

function addPoint(team, points) {
  const scoreElementId = team + 'Score';
  const scoreElement = document.getElementById(scoreElementId);
  if (scoreElement) {
    const currentScore = parseInt(scoreElement.textContent, 10) + points;
    scoreElement.textContent = currentScore;
    operations.push({type: 'score', team: team, points: points});
  }
}

function incrementStat(playerId, statType) {
  const statElementId = statType + '-' + playerId;
  const statElement = document.getElementById(statElementId);
  if (statElement) {
    const currentStat = parseInt(statElement.innerText, 10) + 1;
    statElement.innerText = currentStat;
    operations.push({type: 'stat', playerId: playerId, statType: statType});

    if (statType === 'fault' && currentStat >= 5) {
      const playerName = statElement.getAttribute('data-player-name');
      alert("Figyelem: " + playerName + " elérte az 5 faultot, csere szükséges!");
    }
  }
}



document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('endMatchButton').addEventListener('click', function() {
        var playersData = [];
        var tables = document.querySelectorAll("#table table");

        tables.forEach(function(table) {
            var rows = table.querySelectorAll("tr:not(:first-child)");
            rows.forEach(function(row) {
                var player_id = row.cells[0].innerText; 
                var team_id = row.cells[3].dataset.teamId;
                var twopointsElement = document.getElementById('twopoints-' + player_id);
                var misstwoElement = document.getElementById('misstwo-' + player_id);
                var threepointsElement = document.getElementById('threepoints-' + player_id);
                var missthreeElement = document.getElementById('missthree-' + player_id);
                var freethrowElement = document.getElementById('freethrow-' + player_id);
                var missfreethrowElement = document.getElementById('missfreethrow-' + player_id);
                var stealsElement = document.getElementById('steal-' + player_id);
                var turnoversElement = document.getElementById('turnover-' + player_id);
                var reboundsElement = document.getElementById('rebound-' + player_id);
                var assistsElement = document.getElementById('assists-' + player_id);
                var faultsElement = document.getElementById('fault-' + player_id);

                var player = {
                    player_id: player_id,
                    team_id: team_id,
                    two_points: twopointsElement ? twopointsElement.textContent.split('/')[0] : '0',
                    miss_two: misstwoElement ? misstwoElement.textContent.split('/')[0] : '0',
                    three_points: threepointsElement ? threepointsElement.textContent.split('/')[0] : '0',
                    miss_three: missthreeElement ? missthreeElement.textContent.split('/')[0] : '0',
                    free_throw: freethrowElement ? freethrowElement.textContent.split('/')[0] : '0',
                    miss_free_throw: missfreethrowElement ? missfreethrowElement.textContent.split('/')[0] : '0',
                    steals: stealsElement ? stealsElement.textContent : '0',
                    turnovers: turnoversElement ? turnoversElement.textContent : '0',
                    rebounds: reboundsElement ? reboundsElement.textContent : '0',
                    assists: assistsElement ? assistsElement.textContent : '0',
                    faults: faultsElement ? faultsElement.textContent : '0'
                };
                playersData.push(player);
            });
        });

        // Elküldi a gyűjtött adatokat a szervernek AJAX-on keresztül
        fetch('save_stat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({game_id: gameId, players: playersData})
        }).then(response => response.text())
        .then(data => {
            console.log(data);
        });
    });
});

document.getElementById('endMatchButton').addEventListener('click', function() {
    var homeScore = document.getElementById('homeScore').textContent;
    var awayScore = document.getElementById('awayScore').textContent;

    fetch('update_game_scores.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `game_id=${gameId}&home_score=${homeScore}&away_score=${awayScore}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Game scores updated successfully.');
            window.location.href = '../admin/list.php';  // Átirányítás a list.php oldalra
        } else {
            console.error('Failed to update game scores:', data.message);
        }
    })
    .catch(error => console.error('Error updating game scores:', error));
});
function closeGameAndResetPlayers() {
    if (!confirm('Are you sure you want to end the game and reset all player statuses?')) {
        return;
    }
    

    fetch('reset_players.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'homeTeamId=' + homeTeamId + '&awayTeamId=' + awayTeamId + '&gameId=' + gameId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '../admin/list.php';
        } else {
            alert('Failed to reset game: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

</script>
</body>
</html>