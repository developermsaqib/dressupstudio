<?php
include 'connection.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
$firstname = $data['firstname'];
$lastname = $data['lastname'];
$email = $data['email'];
$role = $data['role'];
$password = isset($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : '';
$sql = "INSERT INTO login (firstname, lastname, email, password, role) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $firstname, $lastname, $email, $password, $role);
$success = $stmt->execute();
echo json_encode(['success' => $success]);
?>
