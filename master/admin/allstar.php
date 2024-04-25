<?php
include '../session_check.php';
include "../config.php";


$sql = "SELECT * FROM games";
$result = $conn->query($sql);

$teams = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $teams[] = $row;
    }
} else {
    echo "0 results";
}
//   REBOUND
$sql = "SELECT player_name , SUM(rebounds) AS total_rebounds FROM game_player_stats 
        INNER JOIN players ON game_player_stats.player_id = players.player_id 
        GROUP BY game_player_stats.player_id 
        ORDER BY total_rebounds DESC";
$result = $conn->query($sql);

$max_rebounds = 0;
$top_rebound_players = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $player_name = $row['player_name'];
        $total_rebounds = $row['total_rebounds'];
        
        if ($total_rebounds > $max_rebounds) {
            $max_rebounds = $total_rebounds;
            $top_rebound_players = [[$player_name, $total_rebounds]];
        } elseif ($total_rebounds == $max_rebounds) {
            $top_rebound_players[] = [$player_name, $total_rebounds];
        }
    }
} else {
    $top_rebound_players = [];
}

// ASSIST
$sql = "SELECT player_name, SUM(assists) AS total_assists FROM game_player_stats 
        INNER JOIN players ON game_player_stats.player_id = players.player_id 
        GROUP BY game_player_stats.player_id 
        ORDER BY total_assists DESC";
$result = $conn->query($sql);

$max_assists = 0;
$top_assist_players = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $player_name = $row['player_name'];
        $total_assists = $row['total_assists'];
        
        if ($total_assists > $max_assists) {
            $max_assists = $total_assists;
            $top_assist_players = [[$player_name, $total_assists]];
        } elseif ($total_assists == $max_assists) {
            $top_assist_players[] = [$player_name, $total_assists];
        }
    }
} else {
    $top_assist_players = [];
}

// STEAL
$sql = "SELECT player_name, SUM(steals) AS total_steals FROM game_player_stats 
        INNER JOIN players ON game_player_stats.player_id = players.player_id 
        GROUP BY game_player_stats.player_id 
        ORDER BY total_steals DESC";
$result = $conn->query($sql);

$max_steals = 0;
$top_steals_players = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $player_name = $row['player_name'];
        $total_steals = $row['total_steals'];
        
        if ($total_steals > $max_steals) {
            $max_steals = $total_steals;
            $top_steals_players = [[$player_name, $total_steals]];
        } elseif ($total_steals == $max_steals) {
            $top_steals_players[] = [$player_name, $total_steals];
        }
    }
} else {
    $top_steals_players = [];
}
// freethrow
$sql = "SELECT player_name, SUM(free_throw) AS made_twos, SUM(miss_free_throw) AS attempted_free_throw, 
        (SUM(free_throw) / (SUM(free_throw) + SUM(miss_free_throw)) * 100) AS free_shooting_percentage 
        FROM game_player_stats 
        INNER JOIN players ON game_player_stats.player_id = players.player_id 
        GROUP BY game_player_stats.player_id 
        HAVING attempted_free_throw > 0
        ORDER BY free_shooting_percentage DESC";
$result = $conn->query($sql);

$max_free_shooting_percentage = 0;
$top_free_shooting_players = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $player_name = $row['player_name'];
        $free_shooting_percentage = $row['free_shooting_percentage'];

        // Check if this player's shooting percentage is the highest found so far
        if ($free_shooting_percentage > $max_free_shooting_percentage) {
            $max_free_shooting_percentage = $free_shooting_percentage;
            // Since this is the highest percentage so far, start a new array
            $top_free_shooting_players = [[$player_name, $free_shooting_percentage]];
        } elseif ($free_shooting_percentage == $max_free_shooting_percentage) {
            // If it matches the highest percentage, add this player to the list
            $top_free_shooting_players[] = [$player_name, $free_shooting_percentage];
        }
    }
}
// 2 POINTS
$sql = "SELECT player_name, SUM(two_points) AS made_twos, SUM(miss_two) AS attempted_twos, 
        (SUM(two_points) / (SUM(two_points) + SUM(miss_two)) * 100) AS shooting_percentage 
        FROM game_player_stats 
        INNER JOIN players ON game_player_stats.player_id = players.player_id 
        GROUP BY game_player_stats.player_id 
        HAVING attempted_twos > 0
        ORDER BY shooting_percentage DESC";
$result = $conn->query($sql);

$max_shooting_percentage = 0;
$top_shooting_players = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $player_name = $row['player_name'];
        $shooting_percentage = $row['shooting_percentage'];

        // Check if this player's shooting percentage is the highest found so far
        if ($shooting_percentage > $max_shooting_percentage) {
            $max_shooting_percentage = $shooting_percentage;
            // Since this is the highest percentage so far, start a new array
            $top_shooting_players = [[$player_name, $shooting_percentage]];
        } elseif ($shooting_percentage == $max_shooting_percentage) {
            // If it matches the highest percentage, add this player to the list
            $top_shooting_players[] = [$player_name, $shooting_percentage];
        }
    }
}

// 3 PONT
$sql = "SELECT player_name, SUM(three_points) AS made_three, SUM(miss_three) AS attempted_three, 
        (SUM(three_points) / (SUM(three_points) + SUM(miss_three)) * 100) AS three_shooting_percentage 
        FROM game_player_stats 
        INNER JOIN players ON game_player_stats.player_id = players.player_id 
        GROUP BY game_player_stats.player_id 
        HAVING attempted_three > 0
        ORDER BY three_shooting_percentage DESC";
$result = $conn->query($sql);

$max_three_shooting_percentage = 0;
$top_three_shooting_players = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $player_name = $row['player_name'];
        $three_shooting_percentage = $row['three_shooting_percentage'];

        // Check if this player's shooting percentage is the highest found so far
        if ($three_shooting_percentage > $max_three_shooting_percentage) {
            $max_three_shooting_percentage = $three_shooting_percentage;
            // Since this is the highest percentage so far, start a new array
            $top_three_shooting_players = [[$player_name, $three_shooting_percentage]];
        } elseif ($three_shooting_percentage == $max_three_shooting_percentage) {
            // If it matches the highest percentage, add this player to the list
            $top_three_shooting_players[] = [$player_name, $three_shooting_percentage];
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>AllStar</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
    <link rel="stylesheet" href="allstar.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="logo.jpg" alt="logo">
                </span>

                <div class="text header-text">
                    <span class="name">Admin oldal</span>
                    <span class="profession"> Üdvözöljük,</span>
                    <span class="profession"> <?php echo $_SESSION['uname'];?> </span>
                </div>
            </div>

            <ion-icon class="toggle" name="arrow-dropright"></ion-icon>

        </header>

        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="admin.php">
                        <ion-icon class="icon" name="home" title="Főoldal"></ion-icon>
                        <span class="text nav-text" title="Főoldal">Főoldal</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="teamadd.php">
                        <ion-icon class="icon" name="add" title="Csapat hozzáadása"></ion-icon>
                        <span class="text nav-text" title="Csapat hozzáadása">Csapat hozzáadása</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="../stat/stat.php">
                        <ion-icon class="icon" name="basketball" title="Mérkőzés indítása"></ion-icon>
                        <span class="text nav-text" title="Mérkőzés indítása">Mérkőzés indítása</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="list.php">
                        <ion-icon class="icon" name="stats" title="Statisztikák"></ion-icon>
                        <span class="text nav-text" title="Statisztikák">Statisztikák</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="teams.php">
                        <ion-icon class="icon" name="people" title="Csapatok"></ion-icon>
                        <span class="text nav-text" title="Csapatok">Csapatok</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="allstar.php">
                        <ion-icon class="icon" name="star" title="AllStar"></ion-icon>
                        <span class="text nav-text" title="AllStar">AllStar</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="profile.php">
                        <ion-icon class="icon" name="person" title="Profilom"></ion-icon>
                        <span class="text nav-text" title="Profilom">Profilom</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="../logout.php">
                        <ion-icon class="icon" name="log-out"></ion-icon>
                        <span class="text nav-text">Kijelentkezés</span>
                    </a>
                </li>

                <li class="mode">
                    <div class="day-night">
                    <ion-icon class="icon moon" name="moon"></ion-icon>
                    <ion-icon class="icon sun" name="sunny"></ion-icon>
                    </div>
                    <span class="mode-text text">Sötét mód</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>

                </li>
            </div>
        </div>
    </nav>
    <script src="admin.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.esm.js"></script>
    </nav>
    <script src="admin.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.esm.js"></script>
    <section class="home">
        <div class="text">AllStar</div>
        <div class="cim">A legjobb büntető dobószázalékkal rendelkező játékosok:</div>
            <div class="resoult">
                <?php if (!empty($top_free_shooting_players)): ?>
                    <?php foreach ($top_free_shooting_players as $player): ?>
                        <?php list($player_name, $free_shooting_percentage) = $player; ?>
                        <div class="data">Játékos neve: <?php echo $player_name; ?>, Dobószázalék: <?php echo number_format($free_shooting_percentage, 2); ?>%</div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="data">Nincs megjeleníthető adat.</div>
                <?php endif; ?>  
            </div>
        

        <div class="cim">A legjobb középtávoli dobószázalékkal rendelkező játékosok:</div>
        <div class="resoult">
            <?php if (!empty($top_shooting_players)): ?>
                <?php foreach ($top_shooting_players as $player): ?>
                    <?php list($player_name, $shooting_percentage) = $player; ?>
                    <div class="data">Játékos neve: <?php echo $player_name; ?>, Dobószázalék: <?php echo number_format($shooting_percentage, 2); ?>%</div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="data">Nincs megjeleníthető adat.</div>
            <?php endif; ?>
        </div>
        

        <div class="cim">A legjobb hárompontos dobószázalékkal rendelkező játékosok:</div>
        <div class="resoult">
            <?php if (!empty($top_three_shooting_players)): ?>
                <?php foreach ($top_three_shooting_players as $player): ?>
                    <?php list($player_name, $three_shooting_percentage) = $player; ?>
                    <div class="data">Játékos neve: <?php echo $player_name; ?>, Dobószázalék: <?php echo number_format($three_shooting_percentage, 2); ?>%</div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="data">Nincs megjeleníthető adat.</div>
            <?php endif; ?>
        </div>
        

        <div class="cim">A legtöbb lepattanóval rendelkező játékosok:</div>
        <div class="resoult">
            <?php if (!empty($top_rebound_players) && $top_rebound_players[0][1] > 0): ?>
                <?php foreach ($top_rebound_players as $player): ?>
                    <?php list($player_name, $total_rebounds) = $player; ?>
                    <div class="data">Játékos neve: <?php echo $player_name; ?>, Rebounds száma: <?php echo $total_rebounds; ?></div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="data">Nincs megjeleníthető adat.</div>
            <?php endif; ?>
        </div>
        
        <div class="cim">A legtöbb gólpasszal rendelkező játékosok:</div>
        <div class="resoult">
            <?php if (!empty($top_assist_players) && $top_assist_players[0][1] > 0): ?>
                <?php foreach ($top_assist_players as $player): ?>
                    <?php list($player_name, $total_assists) = $player; ?>
                    <div class="data">Játékos neve: <?php echo $player_name; ?>, Assistek száma: <?php echo $total_assists; ?></div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="data">Nincs megjeleníthető adat.</div>
            <?php endif; ?>
        </div>

        <div class="cim">A legtöbb szerzett labdával rendelkező játékosok:</div>
        <div class="resoult">
            <?php if (!empty($top_steals_players) && $top_steals_players[0][1] > 0): ?>
                <?php foreach ($top_steals_players as $player): ?>
                    <?php list($player_name, $total_steals) = $player; ?>
                    <div class="data" >Játékos neve: <?php echo $player_name; ?>, Steals száma: <?php echo $total_steals; ?></div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="data">Nincs megjeleníthető adat.</div>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>

    