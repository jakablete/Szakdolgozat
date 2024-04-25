<?php
session_start();
include "../config.php";

$sql = "SELECT team_id, team_name, head_coach, assistant_coach FROM teams";
$result = $conn->query($sql);

$teams = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $teams[] = $row;
    }
} else {
    echo "0 results";
}
$conn->close();
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Statisztikák</title>
    <link rel="stylesheet" type="text/css" href="user.css">
    <link rel="stylesheet" href="../admin/search.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="../admin/logo.jpg" alt="logo">
                </span>

                <div class="text header-text">
                    <span class="name">User oldal</span>
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
                        <a href="user.php">
                        <ion-icon class="icon" name="home" title="Főoldal"></ion-icon>
                        <span class="text nav-text" title="Főoldal">Főoldal</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="user_list.php">
                        <ion-icon class="icon" name="stats" title="Statisztikák"></ion-icon>
                        <span class="text nav-text" title="Statisztikák">Statisztikák</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="user_teams.php">
                        <ion-icon class="icon" name="people" title="Csapatok"></ion-icon>
                        <span class="text nav-text" title="Csapatok">Csapatok</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="user_profile.php">
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
    <script src="../admin/admin.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.esm.js"></script>
    <section class="home">
        <div class="text">Csapatok</div>
        <div id="playersPopup" style="display:none;"></div>
        <div id="editPlayerPopup" style="display:none;"></div>
        <div id="addPlayerPopup" style="display:none;">
            <h3>Új játékos hozzáadása</h3>
            <form id="addPlayerForm">
                Mezszám: <input type="text" id="newJerseyNumber"><br><br>
                Név: <input type="text" id="newPlayerName"><br><br>
                <input type="hidden" id="teamIdToAddPlayer" value="">
                <button type="button" onclick="addPlayer()">Mentés</button>
                <button type="button" onclick="closePopup('addPlayerPopup')">Bezárás</button>
            </form>
        </div>
        <form style="margin-left:15% !important;" id="search" method="post">
            <?php if (isset($_GET['error'])) { ?>
                <p class="error"><?php echo $_GET['error']; ?></p>
            <?php } ?>
            <?php if (isset($_GET['success'])) { ?>
                <p class="success"><?php echo $_GET['success']; ?></p>
            <?php } ?>
            <div class="content">
                <table class="table" id="gamesTable">
                    <thead>
                        <tr class="table-header">
                            <th class="search"><div>Csapatazonosító<span class="asc">▲</span><span class="desc">▼</span></div></th>
                            <th class="search"><div>Csapatnév<span class="asc">▲</span><span class="desc">▼</span></div></th>
                            <th class="search"><div>Edző<span class="asc">▲</span><span class="desc">▼</span></div></th>
                            <th class="search"><div>Másodedző<span class="asc">▲</span><span class="desc">▼</span></div></th>
                            <th class="search"><div>Játékosok<span class="asc">▲</span><span class="desc">▼</span></div></th>
                        </tr>
                        <tr>
                            <th class="th"><input type="text" name="s_azonosito" id="s_azonosito"></th>
                            <th class="th"><input type="text" name="s_csapatnev" id="s_csapatnev"></th>
                            <th class="th"><input type="text" name="s_edzo" id="s_edzo"></th>
                            <th class="th"><input type="text" name="s_medzo" id="s_medzo"></th>
                            <th class="th"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teams as $team): ?>
                            <tr>
                                <td><?= htmlspecialchars($team['team_id']) ?></td>
                                <td><?= htmlspecialchars($team['team_name']) ?></td>
                                <td><?= htmlspecialchars($team['head_coach']) ?></td>
                                <td><?= htmlspecialchars($team['assistant_coach']) ?></td>
                                <td><a href="javascript:void(0);" class="view-players" data-team-id="<?= htmlspecialchars($team['team_id']) ?>">Játékosok</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </form>
    </section>

    <script src="../admin/admin.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const playerLinks = document.querySelectorAll('.view-players');
            playerLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    const teamId = this.getAttribute('data-team-id');
                    fetch(`user_get_players.php?team_id=${teamId}`)
                        .then(response => response.json())
                        .then(players => {
                            showPlayersPopup(players);
                        })
                        .catch(error => console.error('Error fetching player data:', error));
                });
            });
        });

        function showPlayersPopup(players, teamId) {
        const popup = document.getElementById('playersPopup');
        let content = `<table class='player-info-table'>
                           <thead>
                               <tr>
                                   <th>Mezszám</th>
                                   <th>Név</th>
                               </tr>
                           </thead>
                           <tbody>`;
        players.forEach(player => {
            content += `<tr>
                            <td>${player.jersey_number}</td>
                            <td>${player.player_name}</td>
                        </tr>`;
        });
        content += `</tbody></table>`;
        content += `<button onclick="closePopup('playersPopup')">Bezárás</button>`;
        popup.innerHTML = content;
        popup.style.display = 'block';
    }


        function closePopup(popupId) {
            document.getElementById(popupId).style.display = 'none';
        }

        function showAddPlayerForm(teamId) {
    document.getElementById('teamIdToAddPlayer').value = teamId;
    document.getElementById('playersPopup').style.display = 'none';
}

$(document).ready(function() {
    // Keresési mezők eseménykezelője
    $('#s_azonosito, #s_csapatnev, #s_edzo, #s_medzo').on('keyup', function() {
        var columnIndex = $(this).parent().index();  // Az aktuális oszlop indexének meghatározása
        var searchValue = $(this).val().toLowerCase();  // A keresőmező értékének kisbetűssé alakítása

        $('#gamesTable tbody tr').each(function() {
            var row = $(this);
            var cellValue = row.find('td').eq(columnIndex).text().toLowerCase();  // Az adott oszlopban lévő szöveg
            row.toggle(cellValue.indexOf(searchValue) > -1);  // Sor megjelenítése vagy elrejtése
        });
    });
});
(document).ready(function() {
    $('.table-header th').click(function() {
        var self = this;
        var table = $(this).closest('.table');
        var rows = table.find('tbody tr').toArray().sort(comparer($(this).index()));
        this.asc = !this.asc;
        if (!this.asc) {
            rows = rows.reverse();
        }
        for (var i = 0; i < rows.length; i++) {
            table.append(rows[i]);
        }

        $('.table-header th').find('span.asc, span.desc');

        if (this.asc) {
            $(this).find('span.asc').css('color', 'grey');
            $(this).find('span.desc').css('color', 'black');
        } else {
            $(this).find('span.desc').css('color', 'grey');
            $(this).find('span.asc').css('color', 'black');
        }

        updateSortIndicators($(this), this.asc);
    });

    function comparer(index) {
        return function(a, b) {
            var valA = getCellValue(a, index), valB = getCellValue(b, index);
            return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB);
        };
    }

    function getCellValue(row, index){
        return $(row).children('td').eq(index).text();
    }

    function updateSortIndicators(header, ascending) {
        header.find('span.asc').show();
        header.find('span.desc').show();
    }
});

    </script>
</body>
</html>
