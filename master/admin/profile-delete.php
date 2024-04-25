<?php
session_start();
include "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];

        $query = "DELETE FROM user_form WHERE id = $user_id";
        if (mysqli_query($conn, $query)) {
            echo "success";
        } else {
            echo "error: " . mysqli_error($conn);
        }
    } else {
        echo "unauthorized";
    }
}
?>
