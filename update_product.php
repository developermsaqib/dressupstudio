<?php
// update_product.php
header('Content-Type: application/json');
include 'connection.php';

$response = ["success" => false];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Get product ID
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if (!$id) throw new Exception('Missing product ID');

    // Get fields
    $name = trim($_POST['productName'] ?? '');
    $price = floatval($_POST['productPrice'] ?? 0);
    $stock = intval($_POST['productStock'] ?? 0);
    $category_id = intval($_POST['productCategory'] ?? 0);
    $subcategory_id = intval($_POST['productSubcategory'] ?? 0);

    if (!$name || !$price || !$stock || !$category_id) {
        throw new Exception('Missing required fields');
    }

    // Handle image upload (optional)
    $image_path = null;
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $ext = pathinfo($_FILES['productImage']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('prod_', true) . '.' . $ext;
        $target = $upload_dir . $filename;
        if (!move_uploaded_file($_FILES['productImage']['tmp_name'], $target)) {
            throw new Exception('Failed to upload image');
        }
        $image_path = $target;
    }

    // Build SQL
    $sql = "UPDATE products SET name=?, price=?, stock=?, category_id=?, subcategory_id=?";
    $params = [$name, $price, $stock, $category_id, $subcategory_id];
    if ($image_path) {
        $sql .= ", image=?";
        $params[] = $image_path;
    }
    $sql .= " WHERE id=?";
    $params[] = $id;

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $response['success'] = true;
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
?>
