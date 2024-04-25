const body = document.querySelector("body"),
      sidebar = body.querySelector(".sidebar"),
      toggle = body.querySelector(".toggle"),
      searchBTN = body.querySelector(".search-box"),
      modeSwitch = body.querySelector(".toggle-switch"),
      modeText = body.querySelector(".mode-text");


function setThemePreference(theme) {
    localStorage.setItem("theme", theme);
}


function toggleDarkMode() {
    body.classList.toggle("dark");
    if (body.classList.contains("dark")) {
        modeText.innerText = "Világos mód";
        setThemePreference("dark");
    } else {
        modeText.innerText = "Sötét mód";
        setThemePreference("light");
    }
}

function loadThemePreference() {
    const theme = localStorage.getItem("theme");
    if (theme === "dark") {
        body.classList.add("dark");
        modeText.innerText = "Világos mód";
    }
}

toggle.addEventListener("click", () => {
    sidebar.classList.toggle("close");
});

modeSwitch.addEventListener("click", () => {
    toggleDarkMode();
});

loadThemePreference();


