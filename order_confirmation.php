<?php
session_start();
include 'connection.php';

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if (!$order_id) {
    echo '<p>Invalid order ID.</p>';
    exit;
}

// Fetch order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
if (!$order) {
    echo '<p>Order not found.</p>';
    exit;
}

// Fetch order items
$item_stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$item_stmt->bind_param("i", $order_id);
$item_stmt->execute();
$items = $item_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="checkout.css">
</head>
<body>
<main style="max-width: 700px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 24px #eee;">
    <h2 style="color: #388e3c;">Thank you for your order!</h2>
    <p>Your order has been placed successfully. Below are your order details:</p>
    <h3>Order #<?php echo $order['id']; ?></h3>
    <ul>
        <li><b>Name:</b> <?php echo htmlspecialchars($order['user_name']); ?></li>
        <li><b>Email:</b> <?php echo htmlspecialchars($order['user_email']); ?></li>
        <li><b>Address:</b> <?php echo htmlspecialchars($order['address']); ?>, <?php echo htmlspecialchars($order['city']); ?></li>
        <li><b>Phone:</b> <?php echo htmlspecialchars($order['phone']); ?></li>
        <li><b>Payment:</b> <?php echo htmlspecialchars($order['payment_method']); ?></li>
        <li><b>Total:</b> Rs <?php echo number_format($order['total']); ?></li>
        <li><b>Date:</b> <?php echo htmlspecialchars($order['created_at']); ?></li>
    </ul>
    <h4>Order Items</h4>
    <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
        <tr style="background:#ffe4ec; color:#e91e63;">
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
        <?php while ($item = $items->fetch_assoc()): ?>
        <tr style="text-align:center;">
            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
            <td>Rs <?php echo number_format($item['price']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>Rs <?php echo number_format($item['price'] * $item['quantity']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="index.php" style="background:#e91e63; color:#fff; padding:10px 24px; border-radius:4px; text-decoration:none;">Back to Home</a>
</main>
</body>
</html>
