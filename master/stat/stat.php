<?php
    include '../session_check.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    include "../config.php";

$teamsResult = mysqli_query($conn, "SELECT * FROM teams");

if(!$teamsResult) {
    die('Csapatok lekérdezése sikertelen: ' . mysqli_error($conn));
}
$teams = [];
while($team = mysqli_fetch_assoc($teamsResult)) {
    $teams[] = $team;
}
?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Mérkőzés indítása</title>
    <link rel="stylesheet" type="text/css" href="../admin/admin.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body style="margin-top: -3px">
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="../admin/logo.jpg" alt="logo">
                </span>

                <div class="text header-text">
                    <span class="name">Mérkőzés indítása</span>
                    <span class="profession"> Üdvözöljük,</span>
                    <span class="profession"> <?php echo $_SESSION['name']; ?> </span>
                </div>
            </div>

            <ion-icon class="toggle" name="arrow-dropright"></ion-icon>

        </header>

        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="../admin/admin.php">
                            <ion-icon class="icon" name="home" title="Főoldal"></ion-icon>
                            <span class="text nav-text" title="Főoldal">Főoldal</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="../admin/teamadd.php">
                            <ion-icon class="icon" name="add" title="Csapat hozzáadása"></ion-icon>
                            <span class="text nav-text" title="Csapat hozzáadása">Csapat hozzáadása</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="stat.php">
                            <ion-icon class="icon" name="basketball" title="Mérkőzés indítása"></ion-icon>
                            <span class="text nav-text" title="Mérkőzés indítása">Mérkőzés indítása</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="../admin/list.php">
                            <ion-icon class="icon" name="stats" title="Statisztikák"></ion-icon>
                            <span class="text nav-text" title="Statisztikák">Statisztikák</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="../admin/teams.php">
                        <ion-icon class="icon" name="people" title="Csapatok"  title="Csapatok"></ion-icon>
                        <span class="text nav-text" title="Csapatok"  title="Csapatok">Csapatok</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="../admin/allstar.php">
                        <ion-icon class="icon" name="star" title="AllStar"></ion-icon>
                        <span class="text nav-text" title="AllStar">AllStar</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="../admin/profile.php">
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
                    <span class="mode-text text" title="Sötét mód">Sötét mód</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>

                </li>
            </div>
        </div> 
    </nav>

    <section class="home">
        <div class="text">Mérkőzés indítása</div>
        <div class="container cim">
            <h1>Csapat és kezdőjátékosok kiválasztása</h1>
            <h2 style="margin-left:20px">Válaszd ki a csapatokat és a kezdőjátokosok nevét pipáld be</h2>
        </div>
        <form class="container teams" style="margin-left:40%"  action="scoreboard.php" method="post">
            <div>
                <h3 for="homeTeam">Hazai csapat:</h3>
                <select class="team-selector" id="homeTeam" name="home_team" required  data-team-type="home"> <!--onchange="fetchPlayersForTeam(this.value, 'home')"-->
                    <option value="">Válassz hazai csapatot...</option>
                    <?php foreach($teams as $team): ?>
                    <option value="<?= $team['team_id'] ?>"><?= $team['team_name'] ?></option>
                    <?php endforeach ?>
                </select>
                <div id="homePlayersList" class="playersList"></div>
            </div>
            <div>
                <h3 for="awayTeam">Vendég csapat:</h3>
                <select class="team-selector" id="awayTeam" name="away_team" required data-team-type="away"> <!--onchange="fetchPlayersForTeam(this.value, 'away')"-->
                    <option value="">Válassz vendég csapatot...</option>
                    <?php foreach($teams as $team) { echo "<option value='" . $team['team_id'] . "'>" . $team['team_name'] . "</option>"; } ?>
                </select>
                <div id="awayPlayersList" class="playersList"></div>
            </div>
            <button type="submit" id="startMatch" disabled>Mérkőzés Indítása</button>
        </form>
    </section>

<script>
document.addEventListener('DOMContentLoaded', function(ev) {
	let teamSelectors = document.querySelectorAll('select.team-selector');
	teamSelectors.forEach(function(el, idx) {
		el.addEventListener('change', function(ev) {
			fetchPlayersForTeam(parseInt(this.value), this.dataset.teamType);
		});
	});
});

function beforeStartMatch() {
	let button = document.querySelector('#startMatch');
	button.disabled = true;
	let homeTeam = document.querySelector('#homeTeam');
	let awayTeam = document.querySelector('#awayTeam');
	if (homeTeam.value && awayTeam.value) {
		let homeCheckedNum = document.querySelectorAll(`#homePlayersList .playersname > input[type="checkbox"]:checked`);
		let awayCheckedNum = document.querySelectorAll(`#awayPlayersList .playersname > input[type="checkbox"]:checked`);
		if (homeCheckedNum.length == 5 && awayCheckedNum.length == 5) {
			button.disabled = false;
		}
	}
}

function playersNameChange(ev) {
	let el = ev.target;
	let container = el.closest('.playersList');
	let checkedNum = container.querySelectorAll(`.playersname > input[type="checkbox"]:checked`);
	if (checkedNum.length < 5) {
		container.querySelectorAll(`.playersname > input[type="checkbox"]`).forEach(function(el, idx) {
			el.disabled = false;
		});
	} else {
		container.querySelectorAll(`.playersname > input[type="checkbox"]:not(:checked)`).forEach(function(el, idx) {
			el.disabled = true;
		});
	}
	beforeStartMatch();
}

function fetchPlayersForTeam(teamId, teamPrefix) {
		let otherTeamPrefix = teamPrefix === 'home' ? 'away' : 'home';
		let otherOptions = document.querySelectorAll(`select.team-selector[data-team-type="${otherTeamPrefix}"] > option`);
		otherOptions.forEach(function(el, idx) { el.disabled = false; });

    if (!teamId) {
        document.getElementById(teamPrefix + 'PlayersList').innerHTML = '';
        beforeStartMatch();
        return;
    }

    let fetchUrl = teamPrefix === 'home' ? 'fetch_players.php?homeTeamId=' + teamId : 'fetch_players.php?awayTeamId=' + teamId;
    fetch(fetchUrl)
    .then(response => response.json())
    .then(data => {
        const teamData = data[teamPrefix];
        const playersList = document.getElementById(teamPrefix + 'PlayersList');
        let content = '<h3>Játékosok:</h3>';

        if (teamData.length > 0) {
            teamData.forEach(player => {
                content += `
                    <div class="playersname">
                        <input id="player_${player.player_id}" type="checkbox" name="${teamPrefix}_players[]" value="${player.player_id}">
                        <label for="player_${player.player_id}">${player.player_name}</label>
                        <input type="hidden" name="${teamPrefix}_team_id[]" value="${teamId}">
                    </div>`;
            });
        } else {
            content += '<div>Nincsenek játékosok.</div>';
        }
        if (teamId) {
					let teamInOtherOptions = document.querySelector(`select.team-selector[data-team-type="${otherTeamPrefix}"] > option[value="${teamId}"]`);
					if (teamInOtherOptions) { teamInOtherOptions.disabled = true; }
				}

				document.querySelectorAll(`#${teamPrefix}PlayersList > .playersname input[type="checkbox"]`).forEach(function(el, idx) {
					el.removeEventListener('change', playersNameChange);
				});
        playersList.innerHTML = content;
				document.querySelectorAll(`#${teamPrefix}PlayersList > .playersname input[type="checkbox"]`).forEach(function(el, idx) {
					el.addEventListener('change', playersNameChange);
				});
				beforeStartMatch();
    })
    .catch(error => {
			console.error('Error fetching players:', error);
			beforeStartMatch();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form'); 
    form.addEventListener('submit', function(event) {
        var homeCheckboxes = document.querySelectorAll('#homePlayersList input[type="checkbox"]:checked');
        var awayCheckboxes = document.querySelectorAll('#awayPlayersList input[type="checkbox"]:checked');

        if (homeCheckboxes.length !== 5 || awayCheckboxes.length !== 5) {
            event.preventDefault();
            alert('Mindkét csapatból pontosan 5 játékost kell kiválasztani!');
        }
    });
});
</script>
</body>
<script src="../admin/admin.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.esm.js"></script>
</html>
