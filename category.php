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

// Fetch products for this category
$prodResult = $conn->prepare("SELECT * FROM products WHERE category_id = ?");
$prodResult->bind_param("i", $category_id);
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
        <!-- Optional: Dynamic filter controls based on product types -->
    </header>

    <main class="gallery-container">
        <?php if ($products->num_rows === 0): ?>
            <p>No Products available in this category</p>
        <?php else: ?>
            <?php while ($prod = $products->fetch_assoc()): ?>
                <div class="gallery-item">
                    <img src="<?php echo htmlspecialchars($prod['image']); ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>">
                    <h2><?php echo htmlspecialchars($prod['name']); ?></h2>
                    <p><?php echo htmlspecialchars($prod['description']); ?></p>
                    <div class="price-section">
                        <span>Rs <?php echo htmlspecialchars($prod['price']); ?></span>
                        <?php if ($prod['stock'] > 0): ?>
                            <form action="add_to_cart.php" method="POST" style="margin-top: 20px;">
                                <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                <label for="quantity">Quantity:</label>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $prod['stock']; ?>" style="width: 60px;">
                                <button type="submit" style="background: #e91e63; color: #fff; border: none; padding: 8px 18px; border-radius: 4px; margin-left: 10px; cursor: pointer;">Add to Cart</button>
                            </form>
                        <?php else: ?>
                            <div style="color: #b71c1c; font-weight: bold;">This product is currently out of stock.</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </main>

    <!-- Lightbox and JS can be reused from men.php -->
    <script src="men.js"></script>
</body>

</html>