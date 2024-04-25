<?php
    session_start();
    include "../config.php";

    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];
        $query = "SELECT * FROM user_form WHERE id = $user_id";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            
        }
    } else {
        header("Location: admin.php");
    }
?>




<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Profil szerkesztése</title>
    <link rel="stylesheet" type="text/css" href="user.css">
</head>
<body>
<<nav class="sidebar close">
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
        <form action="user_profile_update.php" method="post">
        <?php if (isset($_GET['error'])) { ?>
                    <p class="error"><?php echo $_GET['error']; ?></p>
                <?php } ?>
                <?php if (isset($_GET['success'])) { ?>
                    <p class="success"><?php echo $_GET['success']; ?></p>
                 <?php } ?> 
            <div class="teamadd">
                <h1>Profil szerkesztése</h1>             
                    <table class="user-info">
                        <tr>
                            <th>Név</th><td><input type="text" name="name" value="<?php echo $row['name']; ?>"><ion-icon class="ikon" name="create"></ion-icon></td>
                        </tr>
                            
                        <tr>
                            <th>Felhasználónév</th><td><input type="text" name="uname" value="<?php echo $row['uname']; ?>"><ion-icon class="ikon" name="create"></ion-icon></td>
                        </tr>

                        <tr>
                            <th>Profil típusa</th><td><input type="text" name="user_type" value="<?php echo $row['user_type']; ?>"><ion-icon class="ikon" name="create"></ion-icon></td>
                        </tr>

                        <tr>
                            <th>Jelszó</th><td><input type="password" name="password" value="<?php echo $row['password']; ?>"><ion-icon class="ikon" name="create"></ion-icon></td>
                        </tr>
                            
                    </table>
                    <div class="buttons">
                        <button class="edit" type="submit" name="update" value="profil_update">Változatások jóváhagyása</button>
                    </div> 
            </div>
        </form>
    </section>
    <script src="../admin/admin.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.esm.js"></script>

    

</body>
</html>

