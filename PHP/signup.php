<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $login = mysqli_real_escape_string($conn, $_POST['login']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = "SELECT * FROM accounts WHERE login = '$login'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {
        echo "Username already exists";
        exit();
    }

    $sql1 = "INSERT INTO accounts (login, password)
             VALUES ('$login', '$password')";

    if (mysqli_query($conn, $sql1)) {

        $account_id = mysqli_insert_id($conn);

        $sql2 = "INSERT INTO customer (account_id, name, email)
                 VALUES ('$account_id', '$login', '$email')";

        if (mysqli_query($conn, $sql2)) {

            header("Location: ../HTML/main.html");
            exit();

        } else {
            echo "Customer error: " . mysqli_error($conn);
        }

    } else {
        echo "Account error: " . mysqli_error($conn);
    }

} else {
    echo "Invalid request";
}
?>