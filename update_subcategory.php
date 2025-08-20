<?php
include 'connection.php';
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$category_id = $data['category_id'];
$name = $data['name'];
$sql = "UPDATE subcategories SET category_id=?, name=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isi", $category_id, $name, $id);
$success = $stmt->execute();
echo json_encode(['success' => $success]);
?>
