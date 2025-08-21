<?php
include 'connection.php';
if (isset($_POST['addProduct'])) {
    $name = $_POST['productName'];
    $price = $_POST['productPrice'];
    $stock = $_POST['productStock'];
    $category_id = $_POST['productCategory'];
    $subcategory_id = isset($_POST['productSubcategory']) ? $_POST['productSubcategory'] : null;
    $description = isset($_POST['productDescription']) ? $_POST['productDescription'] : '';
    $image = '';
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
        $targetDir = 'uploads/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = basename($_FILES['productImage']['name']);
        $targetFile = $targetDir . uniqid() . '_' . $fileName;
        if (move_uploaded_file($_FILES['productImage']['tmp_name'], $targetFile)) {
            $image = $targetFile;
        }
    }
    $sql = "INSERT INTO products (name, price, stock, description, image, category_id, subcategory_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdissss", $name, $price, $stock, $description, $image, $category_id, $subcategory_id);
    $success = $stmt->execute();
    if ($success) {
        header('Location: admin_dashboard.php?success=1');
        exit;
    } else {
        header('Location: admin_dashboard.php?error=1');
        exit;
    }
}
?>
