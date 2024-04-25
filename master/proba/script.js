function showPlayers() {
    var homeTeam = document.getElementById("home_team").value;
    var awayTeam = document.getElementById("away_team").value;
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("playersList").innerHTML = this.responseText;
        }
    };
    xhttp.open("POST", "team_selection.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("home_team=" + homeTeam + "&away_team=" + awayTeam);
}
