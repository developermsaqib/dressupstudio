<?php
session_start();
include 'connection.php';

// Get product ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    echo '<p>Invalid product ID.</p>';
    exit;
}

// Fetch product from DB
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
if (!$product) {
    echo '<p>Product not found.</p>';
    exit;
}

// Handle add to cart confirmation
$added = isset($_GET['added']) ? true : false;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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


    <main style="max-width: 900px; margin: 100px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 24px #eee;">
        <?php if ($added): ?>
            <div style="background: #e0ffe0; color: #2e7d32; padding: 10px 20px; border-radius: 5px; margin-bottom: 20px;">Product added to cart!</div>
        <?php endif; ?>
        <div style="display: flex; gap: 40px; align-items: flex-start;">
            <div style="flex: 1;">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; max-width: 350px; border-radius: 8px;">
            </div>
            <div style="flex: 2;">
                <h2 style="font-size: 2rem; color: #e91e63; margin-bottom: 10px;"> <?php echo htmlspecialchars($product['name']); ?> </h2>
                <p style="font-size: 1.2rem; color: #333; margin-bottom: 15px;"> <?php echo nl2br(htmlspecialchars($product['description'])); ?> </p>
                <div style="font-size: 1.5rem; color: #222; margin-bottom: 10px;">Price: <b>Rs <?php echo number_format($product['price']); ?></b></div>
                <div style="margin-bottom: 15px;">
                    Availability: <span style="color: <?php echo $product['stock'] > 0 ? '#388e3c' : '#b71c1c'; ?>; font-weight: bold;">
                        <?php echo $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                    </span>
                </div>
                <?php if ($product['stock'] > 0): ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Logged-in user: use PHP form -->
                        <form action="add_to_cart.php" method="POST" style="margin-top: 20px;">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <label for="quantity">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" style="width: 60px;">
                            <button type="submit" style="background: #e91e63; color: #fff; border: none; padding: 8px 18px; border-radius: 4px; margin-left: 10px; cursor: pointer;">Add to Cart</button>
                        </form>
                    <?php else: ?>
                        <!-- Guest: use JS/localStorage -->
                        <div style="margin-top: 20px;">
                            <label for="guest_quantity">Quantity:</label>
                            <input type="number" id="guest_quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" style="width: 60px;">
                            <button onclick="addToCart({id: <?php echo $product['id']; ?>, name: '<?php echo addslashes($product['name']); ?>', price: <?php echo $product['price']; ?>, image: '<?php echo addslashes($product['image']); ?>', quantity: parseInt(document.getElementById('guest_quantity').value), stock: <?php echo $product['stock']; ?>})" style="background: #e91e63; color: #fff; border: none; padding: 8px 18px; border-radius: 4px; margin-left: 10px; cursor: pointer;">Add to Cart</button>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div style="color: #b71c1c; font-weight: bold;">This product is currently out of stock.</div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <script src="cart.js" defer></script>
    <script>
        // Update cart counter for guests on page load
        if (typeof window.isUserLoggedIn !== 'undefined' && !window.isUserLoggedIn) {
            function updateCartCount() {
                var cart = JSON.parse(localStorage.getItem('cart') || '[]');
                var total = cart.reduce(function(sum, item) {
                    return sum + (item.quantity || 0);
                }, 0);
                var cartCountEl = document.getElementById('cart-count');
                if (cartCountEl) cartCountEl.textContent = total;
            }
            updateCartCount();
            // Also update after addToCart
            window.addToCart = (function(orig) {
                return function(item) {
                    orig(item);
                    updateCartCount();
                }
            })(window.addToCart);
        }
    </script>
</body>

</html>