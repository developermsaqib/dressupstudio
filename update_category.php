<?php
include 'connection.php';
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$name = $data['name'];

$sql = "UPDATE categories SET name=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $name, $id);
$success = $stmt->execute();

echo json_encode(['success' => $success]);
?>
