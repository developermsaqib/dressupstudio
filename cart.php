<?php
session_start();
include 'connection.php';

// --- Detect if user is logged in ---
$is_logged_in = isset($_SESSION['user_id']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="cart.css">
    <script>
        // Pass login status to JS
        window.isUserLoggedIn = <?php echo $is_logged_in ? 'true' : 'false'; ?>;
    </script>
    <script src="cart.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="cart-container">
        <div class="cart-header">
            <h1 class="cart-title">Your Shopping Cart</h1>
            <span id="cart-count"></span>
        </div>
        <main style="max-width: 900px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 24px #eee;">
            <?php if (!$is_logged_in): ?>
                <!-- Guest cart: Rendered by JS from localStorage -->
                <div id="cart-items"></div>
                <div style="margin-top:20px;">
                    <b>Subtotal:</b> <span id="subtotal">Rs 0</span><br>
                    <b>Tax (5%):</b> <span id="tax">Rs 0</span><br>
                    <b>Total:</b> <span id="total">Rs 0</span>
                </div>
                <div style="margin-top:20px;">
                    <a href="login.html?redirect=cart.php" style="background:#388e3c; color:#fff; padding:8px 18px; border-radius:4px; text-decoration:none;">Login to Checkout</a>
                </div>
            <?php else: ?>
                <!-- Logged-in user: Show PHP cart as before -->
                <?php
                // Handle quantity update or removal
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($_POST['update']) && isset($_POST['quantities'])) {
                        foreach ($_POST['quantities'] as $key => $qty) {
                            $qty = intval($qty);
                            if ($qty < 1) $qty = 1;
                            if (isset($_SESSION['cart'][$key])) {
                                // Check stock
                                $product_id = $_SESSION['cart'][$key]['id'];
                                $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
                                $stmt->bind_param("i", $product_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();
                                $max_stock = $row ? $row['stock'] : 1;
                                if ($qty > $max_stock) $qty = $max_stock;
                                $_SESSION['cart'][$key]['quantity'] = $qty;
                            }
                        }
                    }
                    if (isset($_POST['remove'])) {
                        $remove_key = intval($_POST['remove']);
                        if (isset($_SESSION['cart'][$remove_key])) {
                            array_splice($_SESSION['cart'], $remove_key, 1);
                        }
                    }
                    // Unset cart if empty
                    if (empty($_SESSION['cart'])) {
                        unset($_SESSION['cart']);
                    }
                    header('Location: cart.php');
                    exit;
                }

                $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
                $total = 0;
                foreach ($cart as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
                ?>
                <?php if (empty($cart)): ?>
                    <p>Your cart is empty.</p>
                <?php else: ?>
                    <form method="POST" action="cart.php">
                        <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
                            <tr style="background:#ffe4ec; color:#e91e63;">
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Remove</th>
                            </tr>
                            <?php foreach ($cart as $key => $item): ?>
                                <tr style="text-align:center;">
                                    <td><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="" style="width:60px;"></td>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td>Rs <?php echo number_format($item['price']); ?></td>
                                    <td>
                                        <input type="number" name="quantities[<?php echo $key; ?>]" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>" style="width:50px;">
                                    </td>
                                    <td>Rs <?php echo number_format($item['price'] * $item['quantity']); ?></td>
                                    <td>
                                        <button type="submit" name="remove" value="<?php echo $key; ?>" style="background:#b71c1c; color:#fff; border:none; padding:4px 10px; border-radius:3px; cursor:pointer;">Remove</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        <div style="text-align:right; font-size:1.2rem; margin-bottom:20px;">
                            <b>Total: Rs <?php echo number_format($total); ?></b>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <button type="submit" name="update" value="1" style="background:#e91e63; color:#fff; border:none; padding:8px 18px; border-radius:4px; cursor:pointer;">Update Cart</button>
                            <a href="checkout.php" style="background:#388e3c; color:#fff; padding:8px 18px; border-radius:4px; text-decoration:none;">Proceed to Checkout</a>
                        </div>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>