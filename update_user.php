<?php
include 'connection.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$firstname = $data['firstname'];
$lastname = $data['lastname'];
$email = $data['email'];
$role = $data['role'];
$sql = "UPDATE login SET firstname=?, lastname=?, email=?, role=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $firstname, $lastname, $email, $role, $id);
$success = $stmt->execute();
echo json_encode(['success' => $success]);
?>
