<?php
include 'connection.php';
$result = $conn->query("SELECT * FROM categories");
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}
echo json_encode($categories);
?>
