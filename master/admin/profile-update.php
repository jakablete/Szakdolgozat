<?php
    session_start();
    include "../config.php";

    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];

        if (isset($_POST['update'])) {
            $name = $_POST['name'];
            $uname = $_POST['uname'];
            $pass = md5($_POST['password']); // Új jelszó MD5 hash-re alakítása
            $user_type = $_POST['user_type'];
            
            $query = "UPDATE user_form SET name = '$name', uname = '$uname', user_type = '$user_type', password = '$pass'  WHERE id = $user_id";
            $result = mysqli_query($conn, $query);
            
            if ($result) {
                header("Location: profile.php?success=Sikeres profil módosítás");
            } else {
                echo "Error updating profile data.";
            }
        } else {
            header("Location: admin.php");
        }
    }
?>
