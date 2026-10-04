<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $login = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $sql = "SELECT * FROM accounts WHERE login = '$login' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            
            $_SESSION['account_id'] = $user['account_id'];
            $_SESSION['user'] = $user['login'];

            
            header("Location: ../HTML/main.html");
            exit();

        } else {
            echo "Wrong username or password ";
        }

    } else {
        echo "Wrong username or password ";
    }

} else {
    echo "Invalid request method";
}
?>