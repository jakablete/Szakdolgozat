<?php
session_start();
include '../config.php';

function validate($data){
    $data = trim($data);                
    $data = stripslashes($data);        
    $data = htmlspecialchars($data);    
    return $data;
}

if (isset($_POST['uname']) && isset($_POST['password']) && isset($_POST['name']) && isset($_POST['repassword']) && isset($_POST['user_type'])) {
    $name = validate($_POST['name']);
    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']);
    $repass = validate($_POST['repassword']);
    $user_type = validate($_POST['user_type']);
    $user_data = 'uname=' . $uname . '&name=' . $name;

    if (empty($uname)) {
        header("Location: register.php?error=Felhasználónév szükséges&$user_data");
        exit();
    } else if (empty($name)) {
        header("Location: register.php?error=Név szükséges&$user_data");
        exit();
    } else if (empty($pass)) {
        header("Location: register.php?error=Jelszó szükséges&$user_data");
        exit();
    } else if (empty($repass)) {
        header("Location: register.php?error=Adja meg mégegyszer a jelszót&$user_data");
        exit();
    } else if ($pass !== $repass) {
        header("Location: register.php?error=Jelszavak nem egyeznek&$user_data");
        exit();
    } else {

        $hashed_pass = md5($pass);

        $select = "SELECT * FROM user_form WHERE uname = '$uname' && name = '$name'";
        $result = mysqli_query($conn, $select);

        if (mysqli_num_rows($result) > 0) {
            header("Location: register.php?error=Már van ilyen felhasználó&$user_data");
            exit();
        } else {
            $insert = "INSERT INTO user_form(name, uname, password, user_type) VALUES('$name', '$uname', '$hashed_pass', '$user_type')";
            $result2 = mysqli_query($conn, $insert);
            if ($result2) {
                header("Location: login.php?success=Sikeres regisztráció&$user_data");
                exit();
            } else {
                header("Location: register.php?error=Ismeretlen hiba&$user_data");
                exit();
            }
        }
    }
}
?>
