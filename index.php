<?php
session_start();
include 'connection.php';





?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DressUp Studio- Your Online Shopping Destination</title>
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
                    <li><a href="#home">Home</a></li>
                    <li><a href="#products">Products</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#sale">Sale</a></li>
                    <li><a href="#testimonials">Testimonials</a></li>
                    <li><a href="#contact">Contact Us</a></li>

                </ul>
            </nav>
            <div class="header-icons">

                <!-- Search icon triggers dropdown search form -->
                <div class="search-dropdown" style="display:inline-block;position:relative;">
                    <a href="#" class="fas fa-search" id="search-icon"></a>
                    <form id="header-search-form" method="get" action="search.php" style="display:none;position:absolute;top:30px;right:0;z-index:1000;background:#fff;padding:10px;border-radius:6px;box-shadow:0 2px 8px rgba(0,0,0,0.15);min-width:220px;">
                        <input type="text" name="q" placeholder="Search..." style="width:140px;padding:5px;">
                        <button type="submit" style="padding:5px 10px;">Go</button>
                        <span style="cursor:pointer;margin-left:8px;color:#e91e63;font-weight:bold;" id="close-search">&times;</span>
                    </form>
                </div>
                <script>
                    // Toggle search form on icon click
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
                            // Optional: Hide on click outside
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
            <i class="fas fa-bars"></i>
        </div>
        </div>
    </header>

    <section class="hero" id="home">
        <div class="video-background">
            <video autoplay muted loop playsinline>
                <source src="Pink final.mp4" type="video/mp4">

                Your browser does not support the video tag.
            </video>
        </div>
        <div class="container">
            <div class="hero-content">
                <h1>One Stop Shop For All</h1>
                <p>Your Perfect Look Start Here</p>
                <a href="#products" class="btn">Shop Now</a>
            </div>

        </div>
        </div>
    </section>

    <section class="categories">
        <div class="container">
            <h2>Shop by Category</h2>
            <div class="category-grid">
                <!-- Static category cards -->
                <div class="category-card">
                    <img src="4f040655293dd7580952be58583e5492.jpg" alt="Kids">
                    <h3><a href="kids.php">Kids</a></h3>
                </div>
                <div class="category-card">
                    <img src="men.jpg" alt="Men">
                    <h3><a href="men.php">Men</a></h3>
                </div>
                <div class="category-card">
                    <img src="women.jpg" alt="Women">
                    <h3><a href="women.php">Women</a></h3>
                </div>
                <div class="category-card">
                    <img src="accessories.jpg" alt="Accessories">
                    <h3><a href="acessories.php">Accessories</a></h3>
                </div>
                <div class="category-card">
                    <img src="perfumes.jpg" alt="Perfume">
                    <h3><a href="perfume.php">Perfume</a></h3>
                </div>
                <div class="category-card">
                    <img src="peresonal care.jpg" alt="Personal Care">
                    <h3><a href="personal care.php"> PersonalCare</a></h3>
                </div>
                <div class="category-card">
                    <img src="beauty.jpg" alt="Beauty Products">
                    <h3><a href="beauty products.php"> Beauty Products</a></h3>
                </div>
                <div class="category-card">
                    <img src="baby esstial.jpg" alt="Baby Essentials">
                    <h3><a href="baby essential.php"> Baby Essentials</a></h3>
                </div>

            </div>
        </div>
        <div class="container">
            <h2>Categories</h2>
            <div class="category-grid">
                <!-- Dynamic category cards -->
                <?php
                $catResult = $conn->query("SELECT * FROM categories");
                while ($cat = $catResult->fetch_assoc()) {
                    echo '<div class="category-card">';
                    echo '<img src="' . $cat['image'] . '" alt="' . $cat['name'] . '">';
                    echo '<h3><a href="category.php?id=' . $cat['id'] . '">' . $cat['name'] . '</a></h3>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </section>

    <section class="featured-products" id="products">
        <div class="container">
            <h1>Products</h1>
            <form method="get" action="search.php" style="margin-bottom: 20px;">
                <input type="text" name="q" id="productSearch" placeholder="Search products, categories, subcategories..." style="width: 100%; padding: 10px;">
                <button type="submit" style="margin-top: 5px;">Search</button>
            </form>
            <div class="product-grid">
                <!-- Static product cards -->
                <div class="product-card">
                    <img src="static-product1.jpg" alt="Static Product 1">
                    <h3>Static Product 1</h3>
                    <p>Rs 1000</p>
                    <a href="#" class="btn">View</a>
                </div>
                <div class="product-card">
                    <img src="static-product2.jpg" alt="Static Product 2">
                    <h3>Static Product 2</h3>
                    <p>Rs 2000</p>
                    <a href="#" class="btn">View</a>
                </div>

            </div>
        </div>
    </section>

    <section class="sale" id="sale"></section>
    <section class="banner">
        <div class="container">
            <h2>Summer Sale - Up to 50% Off</h2>
            <p>Limited time offer. Shop now before it's gone!</p>
            <a href="#products" class="btn">View Deals</a>
        </div>
    </section>

    <section class="features" id="features">
        <div class="container">
            <h2>Why Choose Us</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-truck"></i>
                    <h3>Free Shipping</h3>
                    <p>On all orders over Rs 50</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-undo"></i>
                    <h3>Easy Returns</h3>
                    <p>30-day return policy</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-lock"></i>
                    <h3>Secure Payment</h3>
                    <p>100% secure checkout</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-headset"></i>
                    <h3>24/7 Support</h3>
                    <p>Dedicated support</p>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonials" id="testimonials">
        <div class="container">
            <h2>What Our Customers Say</h2>
            <div class="testimonial-slider">
                <div class="testimonial active">
                    <p>"Great products and excellent customer service. Will definitely shop here again!"</p>
                    <div class="customer">
                        <img src="areeba.jpg" alt="Customer">
                        <h4>Areeba</h4>
                    </div>
                </div>
                <div class="testimonial">
                    <p>"Fast delivery and high-quality products. Very satisfied with my purchase."</p>
                    <div class="customer">
                        <img src="ayesha.jpg" alt="Customer">
                        <h4>Ayesha</h4>
                    </div>
                </div>
                <div class="testimonial">
                    <p>"The best online shopping experience I've had. Highly recommended!"</p>
                    <div class="customer">
                        <img src="huma.jpg" alt="Customer">
                        <h4>Huma khan</h4>
                    </div>
                </div>
                <div class="slider-controls">
                    <button class="prev"><i class="fas fa-chevron-left"></i></button>
                    <button class="next"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </section>

    <section class="newsletter">
        <div class="container">
            <h2>Subscribe to Our Newsletter</h2>
            <p>Get the latest updates on new products and special offers.</p>
            <form id="newsletter-form">
                <input type="email" placeholder="Enter your email" required>
                <button type="submit" class="btn">Subscribe</button>
            </form>
        </div>
    </section>

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
                        <li><a href="#home">Home</a></li>
                        <li><a href="#products">Products</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#testimonials">Testimonials</a></li>
                        <li><a href="#contact Us">Contact Us</a></li>
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
    <script>
        // Update cart counter for guests on page load
        (function() {
            // Only run for guests (not logged in)
            var isGuest = typeof window.isUserLoggedIn === 'undefined' || !window.isUserLoggedIn;
            if (isGuest) {
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
                if (window.addToCart) {
                    window.addToCart = (function(orig) {
                        return function(item) {
                            orig(item);
                            updateCartCount();
                        }
                    })(window.addToCart);
                }
            }
        })();
    </script>
</body>

</html>
</div>
</form>