
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
        <header>
            <h2 class="logo">Kosárlabda statisztikák</h2>
            <nav class="navigation">
                <a href="./login-register/register.php">Regisztráció</a>
                <a href="index.php">Bejelentkezés</a>
                <a href="./login-register/info.php">Információk</a>
            </nav>
        </header>

        <div class="wrapper">
            
            <div class="form-box login">
                <h2>Bejelentkezés</h2>
                <?php if (isset($_GET['error'])) { ?>
                        <p class="error"><?php echo $_GET['error']; ?></p>
                    <?php } ?>
                    <?php if (isset($_GET['success'])) { ?>
                        <p class="success"><?php echo $_GET['success']; ?></p>
                    <?php } ?>
                <form action="./login-register/login.php" method="post">
                    <div class="input-box">
                        <span class="icon"><ion-icon name="person"></ion-icon></span>
                        <input type="text" name="uname" placeholder="Felhasználónév" >
                        <label>Felhasználónév</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><ion-icon name="lock"></ion-icon></span>
                        <input type="password" name="password" placeholder="Jelszó" >
                        <label>Jelszó</label>
                    </div>
                    <div class="remember">
                        <label><input type="checkbox">Emlékezz rám</label>
                        <a href="#">Elfelejtette a jelszavát?</a>
                    </div>
                    <button type="bejelent" class="gomb">Bejelentkezés</button>
                    <div class="nincs-meg-reg">
                        <p>Nincs még fiókja?<a href="./login-register/register.php" class="reg-link">Regisztráció</a></p>
                    </div>
                
                </form>
            </div>

    
        </div>


    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
</body>

</html>
