<?php
// search.php
session_start();
include 'connection.php';

// Get search query
$query = trim($_GET['q'] ?? '');

// --- Product search ---
$sql = "SELECT p.*, c.name AS category_name, s.name AS subcategory_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN subcategories s ON p.subcategory_id = s.id
        WHERE p.name LIKE ? OR c.name LIKE ? OR s.name LIKE ?";
$stmt = $conn->prepare($sql);
$like = "%$query%";
$stmt->bind_param('sss', $like, $like, $like);
$stmt->execute();
$product_result = $stmt->get_result();

// --- Category search ---
$cat_sql = "SELECT * FROM categories WHERE name LIKE ?";
$cat_stmt = $conn->prepare($cat_sql);
$cat_stmt->bind_param('s', $like);
$cat_stmt->execute();
$category_result = $cat_stmt->get_result();

// --- Subcategory search ---
$subcat_sql = "SELECT * FROM subcategories WHERE name LIKE ?";
$subcat_stmt = $conn->prepare($subcat_sql);
$subcat_stmt->bind_param('s', $like);
$subcat_stmt->execute();
$subcategory_result = $subcat_stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php // --- HEADER copied from index.php --- 
    ?>
    <header>
        <div class="container">
            <div class="logo">
                <h1>DressUp Studio</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php#home">Home</a></li>
                    <li><a href="index.php#products">Products</a></li>
                    <li><a href="index.php#features">Features</a></li>
                    <li><a href="index.php#sale">Sale</a></li>
                    <li><a href="index.php#testimonials">Testimonials</a></li>
                    <li><a href="index.php#contact">Contact Us</a></li>
                </ul>
            </nav>
            <div class="header-icons">
                <div class="search-dropdown" style="display:inline-block;position:relative;">
                    <a href="#" class="fas fa-search" id="search-icon"></a>
                    <form id="header-search-form" method="get" action="search.php" style="display:none;position:absolute;top:30px;right:0;z-index:1000;background:#fff;padding:10px;border-radius:6px;box-shadow:0 2px 8px rgba(0,0,0,0.15);min-width:220px;">
                        <input type="text" name="q" placeholder="Search..." style="width:140px;padding:5px;">
                        <button type="submit" style="padding:5px 10px;">Go</button>
                        <span style="cursor:pointer;margin-left:8px;color:#e91e63;font-weight:bold;" id="close-search">&times;</span>
                    </form>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var icon = document.getElementById('search-icon');
                        var form = document.getElementById('header-search-form');
                        var close = document.getElementById('close-search');
                        if (icon && form && close) {
                            icon.addEventListener('click', function(e) {
                                e.preventDefault();
                                form.style.display = (form.style.display === 'block') ? 'none' : 'block';
                                if (form.style.display === 'block') {
                                    form.querySelector('input[name=q]').focus();
                                }
                            });
                            close.addEventListener('click', function() {
                                form.style.display = 'none';
                            });
                            document.addEventListener('mousedown', function(event) {
                                if (form.style.display === 'block' && !form.contains(event.target) && event.target !== icon) {
                                    form.style.display = 'none';
                                }
                            });
                        }
                    });
                </script>
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
                    $cartQty = 0;
                    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $item) {
                            if (isset($item['quantity']) && is_numeric($item['quantity']) && $item['quantity'] > 0) {
                                $cartQty += (int)$item['quantity'];
                            }
                        }
                    }
                    if ($cartQty === 0 && isset($_SESSION['cart'])) {
                        unset($_SESSION['cart']);
                    }
                    echo $cartQty;
                    ?>
                </span>
            </div>
        </div>
        <div class="mobile-menu">
            <i class="fas fa-bars"></i>
        </div>
    </header>
    <main class="container">
        <h1>Search Results for '<?php echo htmlspecialchars($query); ?>'</h1>
        <form method="get" action="search.php" style="margin-bottom:20px;">
            <input type="text" name="q" value="<?php echo htmlspecialchars($query); ?>" placeholder="Search products, categories, subcategories...">
            <button type="submit">Search</button>
        </form>
        <div class="search-results">
            <?php
            $found = false;
            // --- Show matching categories ---
            if ($category_result->num_rows > 0) {
                $found = true;
                echo '<h2>Matching Categories</h2><div class="category-grid">';
                while ($cat = $category_result->fetch_assoc()) {
                    echo '<div class="category-card">';
                    echo '<img src="' . htmlspecialchars($cat['image']) . '" alt="' . htmlspecialchars($cat['name']) . '">';
                    echo '<h3><a href="category.php?id=' . $cat['id'] . '">' . htmlspecialchars($cat['name']) . '</a></h3>';
                    echo '</div>';
                }
                echo '</div>';
            }
            // --- Show matching subcategories ---
            if ($subcategory_result->num_rows > 0) {
                $found = true;
                echo '<h2>Matching Subcategories</h2><div class="category-grid">';
                while ($subcat = $subcategory_result->fetch_assoc()) {
                    echo '<div class="category-card">';
                    echo '<img src="' . (isset($subcat['image']) ? htmlspecialchars($subcat['image']) : 'default.jpg') . '" alt="' . htmlspecialchars($subcat['name']) . '">';
                    echo '<h3><a href="subcategory.php?id=' . $subcat['id'] . '">' . htmlspecialchars($subcat['name']) . '</a></h3>';
                    echo '</div>';
                }
                echo '</div>';
            }
            // --- Show matching products ---
            if ($product_result->num_rows > 0) {
                $found = true;
                echo '<h2>Matching Products</h2><div class="product-grid">';
                while ($row = $product_result->fetch_assoc()) {
                    echo '<div class="product-card">';
                    echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                    echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                    echo '<p>Category: ' . htmlspecialchars($row['category_name']) . ' | Subcategory: ' . htmlspecialchars($row['subcategory_name']) . '</p>';
                    echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                    echo '<span>Rs ' . htmlspecialchars($row['price']) . '</span>';
                    echo '</div>';
                }
                echo '</div>';
            }
            // --- If nothing found ---
            if (!$found) {
                echo '<div style="padding:30px;text-align:center;color:#e91e63;font-size:1.2em;">No products, categories, or subcategories found matching your search.</div>';
            }
            ?>
        </div>
    </main>
    <footer id="contact">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3>DressUp Studio</h3>
                    <p>Your one-stop shop for all your needs. Quality products at affordable prices.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php#home">Home</a></li>
                        <li><a href="index.php#products">Products</a></li>
                        <li><a href="index.php#features">Features</a></li>
                        <li><a href="index.php#testimonials">Testimonials</a></li>
                        <li><a href="index.php#contact">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="#">My Account</a></li>
                        <li><a href="#">Order Tracking</a></li>
                        <li><a href="#">Wishlist</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">Shipping Policy</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Contact Us</h3>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> Main GT Road, Nowshera, Pakistan</li>
                        <li><i class="fas fa-phone"></i> +92332 9008616</li>
                        <li><i class="fas fa-envelope"></i> nomansharif6@gmail.com</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 DressUp Studio. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
    <script src="index.js"></script>
</body>

</html>