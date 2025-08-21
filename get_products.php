<?php
include 'connection.php';
$sql = "SELECT p.*, p.description, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id=c.id";
$result = $conn->query($sql);
$products = [];
while($row = $result->fetch_assoc()) {
    $products[] = $row;
}
echo json_encode($products);
?>
