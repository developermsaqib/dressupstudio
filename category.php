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
        <?php while($prod = $products->fetch_assoc()): ?>
        <div class="gallery-item">
            <img src="<?php echo htmlspecialchars($prod['image']); ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>">
            <h2><?php echo htmlspecialchars($prod['name']); ?></h2>
            <p><?php echo htmlspecialchars($prod['description']); ?></p>
            <div class="price-section">
                <span>Rs <?php echo htmlspecialchars($prod['price']); ?></span>
                <button class="buy-btn">Add to Cart</button>
            </div>
        </div>
        <?php endwhile; ?>
    </main>

    <!-- Lightbox and JS can be reused from men.php -->
    <script src="men.js"></script>
</body>
</html>
