<?php
session_start();
include 'connection.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>l
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="admin dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li class="active" data-section="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</li>
        <li data-section="products"><i class="fas fa-box"></i> Products</li>
        <li data-section="categories"><i class="fas fa-tags"></i> Categories</li>
        <li data-section="subcategories"><i class="fas fa-sitemap"></i> Subcategories</li>
        <li data-section="orders"><i class="fas fa-shopping-cart"></i> Orders</li>
        <li data-section="users"><i class="fas fa-users"></i> Users</li>
    </ul>
</div>

<div class="main-content">
    <header>
        <h1>Dashboard Overview</h1>
        <div class="user-info">
            <i class="fas fa-user-circle"></i> Admin User
        </div>
    </header>

    <!-- Dashboard Section -->
    <section id="dashboard" class="content-section active">
        <h2>Dashboard Overview</h2>
        <div class="dashboard-cards">
            <div class="card">
                <h3>Total Products</h3>
                <p id="total-products">0</p>
            </div>
        </div>
    </section>

    <!-- Product Management -->
    <section id="products" class="content-section">
        <h2>Product Management</h2>
        <button id="addProductBtn" class="btn primary-btn"><i class="fas fa-plus"></i> Add New Product</button>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="productTableBody"></tbody>
            </table>
        </div>
    </section>

    <!-- Category Management -->
    <section id="categories" class="content-section">
        <h2>Category Management</h2>
        <button id="addCategoryBtn" class="btn primary-btn"><i class="fas fa-plus"></i> Add New Category</button>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="categoryTableBody"></tbody>
            </table>
        </div>
    </section>

    <!-- User Management -->
    <section id="users" class="content-section">
        <h2>User Management</h2>
        <button id="addUserBtn" class="btn primary-btn"><i class="fas fa-plus"></i> Add New User</button>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody"></tbody>
            </table>
        </div>
    </section>

    <!-- Order Management -->
    <section id="orders" class="content-section">
        <h2>Order Management</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="orderTableBody"></tbody>
            </table>
        </div>
    </section>

    <!-- Subcategory Management -->
    <section id="subcategories" class="content-section">
        <h2>Subcategory Management</h2>
        <button id="addSubcategoryBtn" class="btn primary-btn"><i class="fas fa-plus"></i> Add New Subcategory</button>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="subcategoryTableBody"></tbody>
            </table>
        </div>
    </section>
</div>

<!-- Product Modal -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2>Add/Edit Product</h2>
        <form id="productForm" enctype="multipart/form-data" action="add_product.php" method="POST">
            <input type="hidden" id="productId">
            <div class="form-group">
                <label for="productName">Product Name:</label>
                <input type="text" id="productName" name="productName" required>
            </div>
            <div class="form-group">
                <label for="productPrice">Price:</label>
                <input type="number" id="productPrice" step="0.01" name="productPrice" required>
            </div>
            <div class="form-group">
                <label for="productStock">Stock:</label>
                <input type="number" id="productStock" name="productStock" required>
            </div>
            <div class="form-group">
                <label for="productImage">Image:</label>
                <input type="file" id="productImage" name="productImage" accept="image/*">
            </div>
            <div class="form-group">
                <label for="productCategory">Category:</label>
                <select id="productCategory" name="productCategory" required></select>
            </div>
            <div class="form-group">
                <label for="productSubcategory">Subcategory:</label>
                <select id="productSubcategory" name="productSubcategory"></select>
            </div>
            <button type="submit" class="btn primary-btn" name="addProduct">Save Product</button>
        </form>
    </div>
</div>

<!-- Category Modal -->
<div id="categoryModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2>Add/Edit Category</h2>
        <form id="categoryForm" enctype="multipart/form-data" action="add_category.php" method="POST">
            <input type="hidden" id="categoryId">
            <div class="form-group">
                <label for="categoryName">Category Name:</label>
                <input type="text" id="categoryName" name="categoryName" required>
            </div>
            <div class="form-group">
                <label for="categoryImage">Image:</label>
                <input type="file" id="categoryImage" name="categoryImage" accept="image/*">
            </div>
            <button type="submit"  class="btn primary-btn" name="addCategory">Save Category</button>
        </form>
    </div>
</div>

<!-- Subcategory Modal -->
<div id="subcategoryModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2>Add/Edit Subcategory</h2>
        <form id="subcategoryForm">
            <input type="hidden" id="subcategoryId">
            <div class="form-group">
                <label for="subcategoryCategory">Category:</label>
                <select id="subcategoryCategory" required></select>
            </div>
            <div class="form-group">
                <label for="subcategoryName">Subcategory Name:</label>
                <input type="text" id="subcategoryName" required>
            </div>
            <button type="submit" class="btn primary-btn">Save Subcategory</button>
        </form>
    </div>
</div>

<!-- User Modal -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2>Add/Edit User</h2>
        <form id="userForm">
            <input type="hidden" id="userId">
            <div class="form-group">
                <label for="userFirstname">First Name:</label>
                <input type="text" id="userFirstname" required>
            </div>
            <div class="form-group">
                <label for="userLastname">Last Name:</label>
                <input type="text" id="userLastname" required>
            </div>
            <div class="form-group">
                <label for="userEmail">Email:</label>
                <input type="email" id="userEmail" required>
            </div>
            <div class="form-group">
                <label for="userRole">Role:</label>
                <select id="userRole" required>
                    <option value="admin">Admin</option>
                    <option value="customer">Customer</option>
                </select>
            </div>
            <button type="submit" class="btn primary-btn">Save User</button>
        </form>
    </div>
</div>

<script src="admin_dashboard.js"></script>
</body>
</html>
