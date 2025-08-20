<?php
include 'connection.php';
$result = $conn->query("SELECT * FROM login");
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
echo json_encode($users);
?>
