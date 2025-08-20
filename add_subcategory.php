<?php
include 'connection.php';
$data = json_decode(file_get_contents("php://input"), true);
$category_id = $data['category_id'];
$name = $data['name'];
$sql = "INSERT INTO subcategories (category_id, name) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $category_id, $name);
$success = $stmt->execute();
echo json_encode(['success' => $success]);
?>
