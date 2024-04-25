<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "../config.php";

if (isset($_POST['uname']) && isset($_POST['password'])) {

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']);

    if (empty($uname)) {
        header("Location: ../index.php?error=Felhasználónév szükséges");
        exit();
    } else if (empty($pass)) {
        header("Location: ../index.php?error=Jelszó szükséges");
        exit();
    } else {
        $stmt = $conn->prepare("SELECT * FROM user_form WHERE uname = ?");
        $stmt->bind_param("s", $uname);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (md5($pass) === $row['password']) {
                $_SESSION['uname'] = $row['uname'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['user_type'] = $row['user_type'];
                $_SESSION['logged_in'] = true;

                if ($row['user_type'] === 'admin') {
                    header("Location: ../admin/admin.php");
                } else {
                    header("Location: ../user/user.php");
                }
                exit();
            } else {
                header("Location: ../index.php?error=Hibás felhasználónév vagy jelszó");
                exit();
            }
        } else {
            header("Location: ../index.php?error=Hibás felhasználónév vagy jelszó");
            exit();
        }
        $stmt->close();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>
