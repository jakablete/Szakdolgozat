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
$conn->close();
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Mérkőzések</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
    <link rel="stylesheet" href="search.css">
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

    <section class="home">
        <div class="text">Mérkőzések</div>

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
                                <div><b>Játékos statisztikák</b></div>
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
                                <td><a href="javascript:void(0);" class="view-stats" data-game-id="<?= htmlspecialchars($team['game_id']) ?>">Játékos statisztikák</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    
                </table>
            </div>
            <div id="modal" class="modal" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="close" onclick="$('#modal').hide();">&times;</span>
                        <h2>Játékos statisztikák</h2>
                    </div>
                    <div class="modal-body">
                        <!-- Stats will be loaded here -->
                    </div>
                </div>
            </div>

        </form>
    </section>
<script>

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


    $('.view-stats').click(function() {
        var homeTeam = $(this).data('home_team_id')
        var ayawTeam = $(this).data('away_team_id')
        var gameId = $(this).data('game-id');
        $.ajax({
            url: 'fetch_player_stats.php',
            type: 'POST',
            data: {
                game_id: gameId
            },
            success: function(data) {
                try {
                    var stats = JSON.parse(data);  // feltételezzük, hogy 'data' az a JSON szöveg, amit a szerverről kaptunk
var content = "";

Object.keys(stats).forEach(function(teamId) {
    var team = stats[teamId];
    var players = team.players;
    content += "<h2>" + team.team_name + "</h2>";  // Kiírjuk a csapat nevét
    content += "<table border='1' style='width:100%; text-align:center;'><thead><tr><th>Játékos neve</th><th>Összes pont</th><th>Két pontos</th><th>Három pontos</th><th>Büntetők</th><th>Gólpassz</th><th>Lepattanó</th><th>Szerzett</th><th>Eladott</th><th>Fault</th></tr></thead><tbody>";
    players.forEach(function(player) {
        var totalPoints = (player.two_points * 2) + (player.three_points * 3) + player.free_throw;
        content += "<tr><td>" + player.player_name + "</td><td>" + totalPoints + "</td><td>" + player.two_points + "/" + player.miss_two + "</td><td>" + player.three_points + "/" + player.miss_three + "</td><td>" + player.free_throw + "/" + player.miss_free_throw + "</td><td>" + player.assists + "</td><td>" + player.rebounds + "</td><td>" + player.steals + "</td><td>" + player.turnover + "</td><td>" + player.faults + "</td></tr>";
    });
    content += "</tbody></table>";
});
showModal(content);
                } catch (error) {
                    console.error("Parsing error:", error);
                    alert("Hiba történt az adatok feldolgozása közben.");
                }
            },
            error: function(xhr) {
                alert("Hiba történt: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    function showModal(content) {
        var modal = $('#modal');
        if (!modal.length) {
            $('body').append('<div id="modal" class="modal"><div class="modal-content"><div class="modal-header"><span class="close">&times;</span><h2>Player Stats</h2></div><div class="modal-body">' + content + '</div><div class="modal-footer"><button onclick="$(\'#modal\').hide();">Close</button></div></div></div>');
            $('.close').click(function() {
                $('#modal').hide();
            });
        } else {
            modal.find('.modal-body').html(content);
        }
        modal.show();
    }
});

</script>

</body>
</html>

    