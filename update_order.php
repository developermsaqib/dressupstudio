<?php
include 'connection.php';
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$status = $data['status'];
$sql = "UPDATE orders SET status=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $id);
$success = $stmt->execute();
echo json_encode(['success' => $success]);
?>
