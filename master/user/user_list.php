<?php
session_start();
include "../config.php";


$sql = "SELECT game_id, game_date, home_team_name, away_team_score, home_team_score, away_team_name FROM games";
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
    <title>Mérkőzések</title>
    <link rel="stylesheet" type="text/css" href="user.css">
    <link rel="stylesheet" href="../admin/search.css">
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
        <div class="text">Mérkőzések</div>

</div>
        <form style="margin-left:5% !important;" id="search" method="post">
        <?php
        if (isset($_GET['error'])) { ?>
                        <p class="error"><?php echo $_GET['error']; ?></p>
                    <?php } ?>
                    <?php if (isset($_GET['success'])) { ?>
                        <p class="success" ><?php echo $_GET['success']; ?></p>
                    <?php } ?>
            <div class="content">
                <table class="table" id="gamesTable">
                    <thead>
                        <tr class="table-header">
                            <th class="search">
                                <div><b>Mérkőzésazonosító</b><span class="asc">▲</span><span class="desc">▼</span></div>
                            </th>
                            <th class="search">
                                <div><b>Dátum</b><span class="asc">▲</span><span class="desc">▼</span></div>
                            </th>
                            <th class="search">
                                <div><b>Hazai csapat</b><span class="asc">▲</span><span class="desc">▼</span></div>
                            </th>
                            <th class="search">
                                <div><b>Vendég csapat</b><span class="asc">▲</span><span class="desc">▼</span></div>
                            </th>
                            <th class="search">
                                <div><b>Hazai csapat pontjai</b><span class="asc">▲</span><span class="desc">▼</span></div>
                            </th>
                            <th class="search">
                                <div><b>Vendég csapat pontjai</b><span class="asc">▲</span><span class="desc">▼</span></div>
                            </th>
                            <th class="search">
                                <div><b>Játékos statisztikák</b><span class="asc">▲</span><span class="desc">▼</span> </div>
                            </th>
                        </tr>
                        <tr>
                            <th class="th"><input  type="text" name="s_azonosito" id="s_azonosito"><span onClick="window.location.reload();" ></span></th>
                            <th class="th"><input  type="text" name="s_datum" id="s_datum"><span onClick="window.location.reload();" ></i></span></th>
                            <th class="th"><input  type="text" name="s_hazai" id="s_hazai"><span onClick="window.location.reload();" ></span></th>
                            <th class="th"><input  type="text" name="s_vendeg" id="s_vendeg"><span onClick="window.location.reload();" ></span></th>
                            <th class="th"><input  type="text" name="s_hscore" id="s_hscore"><span onClick="window.location.reload();" ></span></th>
                            <th class="th"><input  type="text" name="s_vscore" id="s_vscore"><span onClick="window.location.reload();" ></span></th>
                            <th class="th"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teams as $team): ?>
                            <tr>
                                <td class="column-azonosito"><?= htmlspecialchars($team['game_id']) ?></td>
                                <td class="column-datum"><?= htmlspecialchars($team['game_date']) ?></td>
                                <td class="column-hazai"><?= htmlspecialchars($team['home_team_name']) ?>
                                <td class="column-vendeg"><?= htmlspecialchars($team['away_team_name']) ?>
                                <td class="column-hscore"><?= htmlspecialchars($team['home_team_score']) ?>
                                <td class="column-vscore"><?= htmlspecialchars($team['away_team_score']) ?>
                                <td ><a href="javascript:void(0);" class="view-players" data-team-id="<?= htmlspecialchars($team['game_id']) ?>">Játékos statisztikák</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    
                </table>
            </div>
        </form>
    </section>
    <script>

// document.addEventListener("DOMContentLoaded", function() {
//     const playerLinks = document.querySelectorAll('.view-players');
//     playerLinks.forEach(link => {
//         link.addEventListener('click', function(event) {
//             const teamId = this.getAttribute('data-team-id');
//             fetch(`get_players.php?team_id=${teamId}`)
//                 .then(response => {
//                     if (!response.ok) throw new Error('Network response was not OK');
//                     return response.json();
//                 })
//                 .then(data => {
//                     // Handle data
//                 })
//                 .catch(error => console.error('Error fetching player data:', error));
//         });
//     });
// });

function showPlayersPopup(players) {
    const popup = document.getElementById('playersPopup');
    let content = '<ul>';
    players.forEach(player => {
        content += `<li>${player.number} - ${player.name}</li>`;
    });
    content += '</ul>';
    popup.innerHTML = content;
    popup.style.display = 'block';
}


$(document).ready(function() {
    $('#s_azonosito, #s_datum, #s_hazai, #s_vendeg, #s_hscore, #s_vscore').keyup(function() {
        var columnClass = $(this).attr('id').replace('s_', 'column-'); 
        var searchValue = $(this).val().toLowerCase();

        $('#gamesTable tbody tr').filter(function() {
            $(this).toggle($(this).find('.' + columnClass).text().toLowerCase().indexOf(searchValue) > -1)
        });
    });
});



$(document).ready(function() {
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

// document.addEventListener("DOMContentLoaded", function() {
//     const playerLinks = document.querySelectorAll('.view-players');
//     playerLinks.forEach(link => {
//         link.addEventListener('click', function(event) {
//             event.preventDefault();
//             const gameId = this.getAttribute('data-team-id');
//             fetch(`get_game_stats.php?game_id=${gameId}`)
//                 .then(response => {
//                     if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
//                     return response.json();
//                 })
//                 .then(stats => {
//                     showPlayersStats(stats);
//                 })
//                 .catch(error => {
//                     console.error('Error fetching player stats:', error);
//                 });
//         });
//     });
// });

// function showPlayersStats(stats) {
//     const popup = document.getElementById('playersPopup');
//     let content = `<table class='stats-table'>
//                        <thead>
//                            <tr>
//                                <th>Player ID</th>
//                                <th>Points</th>
//                                <th>Assists</th>
//                                <th>Rebounds</th>
//                                <th>Steals</th>
//                                <th>Blocks</th>
//                            </tr>
//                        </thead>
//                        <tbody>`;
//     stats.forEach(stat => {
//         content += `<tr>
//                         <td>${stat.player_id}</td>
//                         <td>${stat.points}</td>
//                         <td>${stat.assists}</td>
//                         <td>${stat.rebounds}</td>
//                         <td>${stat.steals}</td>
//                         <td>${stat.blocks}</td>
//                     </tr>`;
//     });
//     content += `</tbody></table>`;
//     popup.innerHTML = content;
//     popup.style.display = 'block';
// }

</script>

</body>
</html>

    