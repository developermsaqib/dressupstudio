<?php
session_start();
include 'connection.php';

// Restrict to logged-in users
if (!isset($_SESSION['user_id'])) {
    // Redirect guests to login.html with a clear message
    header('Location: login.html?msg=Please+login+to+place+an+order');
    exit;
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cart)) {
    header('Location: cart.php');
    exit;
}

$user = [
    'name' => $_SESSION['user_name'] ?? '',
    'email' => $_SESSION['user_email'] ?? ''
];

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="admin dashboard.css">

</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>DressUp Studio</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="index.php#products">Products</a></li>
                    <li><a href="index.php#features">Features</a></li>
                    <li><a href="index.php#sale">Sale</a></li>
                    <li><a href="index.php#testimonials">Testimonials</a></li>
                    <li><a href="index.php#contact">Contact Us</a></li>

                </ul>
            </nav>
            <div class="header-icons">

                <a href="search page.html" class="fas fa-search"></a>
                <?php
                if (isset($_SESSION['user_name'])) {
                    $alpha = strtoupper(substr($_SESSION['user_name'], 0, 1));
                    echo "<div class='user-alpha' onclick=\"window.location.href='customer_dashboard.php'\">$alpha</div>";
                } else {
                    echo "<a href='signup.php' class='fas fa-user'></a>";
                }
                ?>
                <a href="cart.php" class="fas fa-shopping-cart"></a>
                <span id="cart-count" class="cart-count">
                    <?php
                    // Defensive: Only count numeric quantities and only if cart is not empty
                    $cartQty = 0;
                    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $item) {
                            if (isset($item['quantity']) && is_numeric($item['quantity']) && $item['quantity'] > 0) {
                                $cartQty += (int)$item['quantity'];
                            }
                        }
                    }
                    // If cart is empty or all items removed, unset the cart session
                    if ($cartQty === 0 && isset($_SESSION['cart'])) {
                        unset($_SESSION['cart']);
                    }
                    echo $cartQty;
                    ?>
                </span>

            </div>

        </div>
        <div class="mobile-menu">
            <!-- <i class="fas fa-bars"></i> -->
            cart
        </div>
        </div>
    </header>

    <main style="max-width: 900px; margin: 100px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 1px 3px #000;">
        <h2 style="color: #e91e63;">Checkout</h2>
        <?php if ($error): ?>
            <div style="background: #ffe0e0; color: #b71c1c; padding: 10px 20px; border-radius: 5px; margin-bottom: 20px;">Please fill all required fields.</div>
        <?php endif; ?>
        <h3>Order Summary</h3>
        <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
            <tr style="background:#ffe4ec; color:#e91e63;">
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($cart as $item): ?>
                <tr style="text-align:center;">
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>Rs <?php echo number_format($item['price']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>Rs <?php echo number_format($item['price'] * $item['quantity']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div style="text-align:right; font-size:1.2rem; margin-bottom:20px;">
            <b>Total: Rs <?php echo number_format($total); ?></b>
        </div>
        <h3>Shipping & Billing Information</h3>
        <form action="order.php" method="POST" style="max-width: 500px; margin: 0 auto; padding: 20px;">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <select class="form-control" id="payment_method" name="payment_method" required>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                    <option value="Card">Card</option>
                </select>
            </div>
            <button type="submit" class="btn primary-btn">Place Order</button>
        </form>
    </main>
</body>

</html>