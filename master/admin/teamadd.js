const playerForm = document.getElementById("playerForm");
const playersContainer = document.getElementById("players");
const addPlayerButton = document.getElementById("addPlayer");

let playerCount = 0;

addPlayerButton.addEventListener("click", function () {
    playerCount++;
    const playerDiv = document.createElement("div");
    playerDiv.innerHTML = `
        <div class="teamadd">
            <label for="playerName${playerCount}">Játékos neve:</label> <span class="mez">Mezszám:</span><br>
            <input class="write" type="text" id="playerName${playerCount}" name="playerName[]" >
            <input class="btn" type="number" inputmode="numeric" id="jerseyNumber${playerCount}" name="jerseyNumber[]" >
            <button type="button"  class="removePlayer">Játékos eltávolítása</button>
        </div>
    `;
    playersContainer.appendChild(playerDiv);

    playerDiv.querySelector(".removePlayer").addEventListener("click", function () {
        playerDiv.remove();
    });
});
