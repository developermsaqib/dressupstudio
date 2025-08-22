<?php
// search.php
session_start();
include 'connection.php';

// Get search query
$query = trim($_GET['q'] ?? '');

// Prepare SQL with LIKE for product, category, subcategory
$sql = "SELECT p.*, c.name AS category_name, s.name AS subcategory_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN subcategories s ON p.subcategory_id = s.id
        WHERE p.name LIKE ? OR c.name LIKE ? OR s.name LIKE ?";
$stmt = $conn->prepare($sql);
$like = "%$query%";
$stmt->bind_param('sss', $like, $like, $like);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <h1>Search Results for '<?php echo htmlspecialchars($query); ?>'</h1>
    <form method="get" action="search.php">
        <input type="text" name="q" value="<?php echo htmlspecialchars($query); ?>" placeholder="Search products, categories, subcategories...">
        <button type="submit">Search</button>
    </form>
    <div class="search-results">
        <?php if ($result->num_rows === 0): ?>
            <p>No products found.</p>
        <?php else: ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-item">
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" style="width:100px;">
                    <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                    <p>Category: <?php echo htmlspecialchars($row['category_name']); ?> | Subcategory: <?php echo htmlspecialchars($row['subcategory_name']); ?></p>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <span>Rs <?php echo htmlspecialchars($row['price']); ?></span>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</body>

</html>