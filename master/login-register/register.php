
<!DOCTYPE html>
<html >

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body>
        <header>
            <h2 class="logo">Kosárlabda statisztikák</h2>
            <nav class="navigation">
                <a href="register.php">Regisztráció</a>
                <a href="../index.php">Bejelentkezés</a>
                <a href="info.php">Információk</a>
            </nav>
        </header>

        <div class="wrapper2">

            <div class="form-box">
                <h2>Regisztráció</h2>
                <form action="register-check.php" method="post">
                    <?php if (isset($_GET['error'])) { ?>
                        <p class="error"><?php echo $_GET['error']; ?></p>
                    <?php } ?>
                    <?php if (isset($_GET['success'])) { ?>
                        <p class="success"><?php echo $_GET['success']; ?></p>
                    <?php } ?>
                    <div class="input-box">
                        <span class="icon"><ion-icon name="contact"></ion-icon></span>
                        <input type="text" name="name" placeholder="Név" >
                        <label>Név</label>
                    </div>
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
                    <div class="input-box">
                        <span class="icon"><ion-icon name="refresh-circle"></ion-icon></span>
                        <input type="password" name="repassword" placeholder="Jelszó újra" >
                        <label>Jelszó újra</label>
                    </div>
                    <select name="user_type">
                        <option value="user">user</option>
                        <option value="admin">admin</option>
                    </select>
                    <button type="bejelent" name="gomb" class="gomb">Regisztráció</button>
                    <div class="nincs-meg-reg">
                        <p>Van már fiókom <a href="../index.php" class="login-link">Belépés</a></p>
                    </div>
                    
                </form>
            </div> 
        </div>

        <script src="script.js"></script>
        <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
</body>
</html>
