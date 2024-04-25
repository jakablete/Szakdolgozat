<?php
    include '../session_check.php';
    include "../config.php";

    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];
        $query = "SELECT * FROM user_form WHERE id = $user_id";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            
        }
    } else {
        header("Location: ../login-register/login.php");
    }
?>




<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Profilom</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
    
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
                    <span class="mode-text text" title="Sötét mód">Sötét mód</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>

                </li>
            </div>
        </div>
    </nav>

    <section class="home">
        <div class="text">Profilom</div>
        <form> 
            <div class="teamadd">
                <h1>Profil adatok</h1>             
                    <table class="user-info">
                        <tr>
                            <th>Név</th><td><?php echo $row['name']; ?></td>
                        </tr>
                            
                        <tr>
                            <th>Felhasználónév</th><td><?php echo $row['uname']; ?></td>
                        </tr>

                        <tr>
                            <th>User Type</th></ion-icon><td><?php echo $row['user_type']; ?></td>
                        </tr>

                        <?php
                        $query = "SELECT * FROM user_form";
                        $result = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_assoc($result)) {
                            $id = $row['id'];
                            $uname = $row['uname'];
                            $name = $row['name'];
                            $pass = $row['password'];
                            $user_type = $row['user_type'];

                        }
                        ?>
                            
                    </table>
                    <div class="buttons">
                        <a href="profile-edit.php">Edit Profile</a>
                        <a href="javascript:void(0);" id="deleteProfileLink">Delete Profile</a>
                        <div id="confirmationDialog" class="confirmation-dialog">
                            <p>Biztosan törli a profilját?</p>
                            <button class="igen" id="confirmDelete">Igen</button>
                            <button id="cancelDelete">Mégse</button>
                        </div>
                    </div> 
        </form>
    </section>
    <script src="admin.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.esm.js"></script>
    <script src="delete-popup.js"></script>

    

</body>
</html>

