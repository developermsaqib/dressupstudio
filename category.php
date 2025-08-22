<?php
session_start();
include 'connection.php';

// Get category id from URL
$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($category_id <= 0) {
    die('Invalid category!');
}

// Fetch category info
$catResult = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$catResult->bind_param("i", $category_id);
$catResult->execute();
$category = $catResult->get_result()->fetch_assoc();
if (!$category) {
    die('Category not found!');
}

// Fetch subcategories for this category
$subcatResult = $conn->prepare("SELECT * FROM subcategories WHERE category_id = ?");
$subcatResult->bind_param("i", $category_id);
$subcatResult->execute();
$subcategories = $subcatResult->get_result();

// Fetch products for this category (optionally filter by subcategory)
$selected_subcat = isset($_GET['subcat']) ? intval($_GET['subcat']) : 0;
if ($selected_subcat > 0) {
    $prodResult = $conn->prepare("SELECT * FROM products WHERE category_id = ? AND subcategory_id = ?");
    $prodResult->bind_param("ii", $category_id, $selected_subcat);
} else {
    $prodResult = $conn->prepare("SELECT * FROM products WHERE category_id = ?");
    $prodResult->bind_param("i", $category_id);
}
$prodResult->execute();
$products = $prodResult->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['name']); ?> - Gallery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="men.css">
</head>

<body>
    <header class="gallery-header">
        <h1><?php echo htmlspecialchars($category['name']); ?></h1>
    </header>

    <main class="gallery-container">
        <?php if ($subcategories->num_rows > 0): ?>
            <div class="subcategories-list">
                <h2>Subcategories</h2>
                <?php while ($subcat = $subcategories->fetch_assoc()): ?>
                    <a href="category.php?id=<?php echo $category_id; ?>&subcat=<?php echo $subcat['id']; ?>" style="margin-right: 15px;<?php if ($selected_subcat == $subcat['id']) echo ' font-weight:bold;'; ?>">
                        <?php echo htmlspecialchars($subcat['name']); ?>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <?php if ($products->num_rows === 0): ?>
            <p>No Products available in this category<?php echo $selected_subcat ? ' and subcategory' : ''; ?></p>
        <?php else: ?>
            <?php while ($prod = $products->fetch_assoc()): ?>
                <div class="gallery-item">
                    <img src="<?php echo htmlspecialchars($prod['image']); ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>">
                    <h2><?php echo htmlspecialchars($prod['name']); ?></h2>
                    <p><?php echo htmlspecialchars($prod['description']); ?></p>
                    <div class="price-section">
                        <span>Rs <?php echo htmlspecialchars($prod['price']); ?></span>
                        <?php if ($prod['stock'] > 0): ?>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <!-- Logged-in user: use PHP form -->
                                <form action="add_to_cart.php" method="POST" style="margin-top: 20px;">
                                    <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                    <label for="quantity">Quantity:</label>
                                    <input type="number" id="quantity_<?php echo $prod['id']; ?>" name="quantity" value="1" min="1" max="<?php echo $prod['stock']; ?>" style="width: 60px;">
                                    <button type="submit" style="background: #e91e63; color: #fff; border: none; padding: 8px 18px; border-radius: 4px; margin-left: 10px; cursor: pointer;">Add to Cart</button>
                                </form>
                            <?php else: ?>
                                <!-- Guest: use JS/localStorage -->
                                <div style="margin-top: 20px;">
                                    <label for="guest_quantity_<?php echo $prod['id']; ?>">Quantity:</label>
                                    <input type="number" id="guest_quantity_<?php echo $prod['id']; ?>" value="1" min="1" max="<?php echo $prod['stock']; ?>" style="width: 60px;">
                                    <button onclick="addToCart({id: <?php echo $prod['id']; ?>, name: '<?php echo addslashes($prod['name']); ?>', price: <?php echo $prod['price']; ?>, image: '<?php echo addslashes($prod['image']); ?>', quantity: parseInt(document.getElementById('guest_quantity_<?php echo $prod['id']; ?>').value), stock: <?php echo $prod['stock']; ?>})" style="background: #e91e63; color: #fff; border: none; padding: 8px 18px; border-radius: 4px; margin-left: 10px; cursor: pointer;">Add to Cart</button>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div style="color: #b71c1c; font-weight: bold;">This product is currently out of stock.</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </main>
    <script src="men.js"></script>
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