<?php
include 'connect.php';
session_start();

$data = json_decode(file_get_contents("php://input"), true);

$products = $data['products'];

$user = $_SESSION['user'] ?? 'guest';

$total = 0;

foreach ($products as $p) {
    $total += $p['price'] * $p['quantity'];
}

mysqli_query($conn, "INSERT INTO orders (user, total) VALUES ('$user', $total)");

$order_id = mysqli_insert_id($conn);

foreach ($products as $p) {
    $name = $p['name'];
    $price = $p['price'];
    $qty = $p['quantity'];
    $sub = $price * $qty;

    mysqli_query($conn,
        "INSERT INTO order_items (order_id, product_name, price, quantity, subtotal)
        VALUES ($order_id, '$name', $price, $qty, $sub)"
    );
}

echo "success";
?>