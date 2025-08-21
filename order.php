<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=checkout.php');
    exit;
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cart)) {
    header('Location: cart.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit;
}

// Validate inputs
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$payment_method = trim($_POST['payment_method'] ?? '');

if (!$name || !$email || !$address || !$city || !$phone || !$payment_method) {
    header('Location: checkout.php?error=1');
    exit;
}

$user_id = $_SESSION['user_id'];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Insert order
$stmt = $conn->prepare("INSERT INTO orders (user_id, user_name, user_email, address, city, phone, payment_method, total, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("issssssd", $user_id, $name, $email, $address, $city, $phone, $payment_method, $total);
$stmt->execute();
$order_id = $stmt->insert_id;

// Insert order items
$item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
foreach ($cart as $item) {
    $item_stmt->bind_param("iisdi", $order_id, $item['id'], $item['name'], $item['price'], $item['quantity']);
    $item_stmt->execute();
    // Optionally, update product stock
    $conn->query("UPDATE products SET stock = stock - " . intval($item['quantity']) . " WHERE id = " . intval($item['id']));
}

// Clear cart
unset($_SESSION['cart']);

// Show confirmation
header('Location: order_confirmation.php?order_id=' . $order_id);
exit;
?>
