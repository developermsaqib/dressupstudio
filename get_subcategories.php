<?php
include 'connection.php';
$result = $conn->query("SELECT s.*, c.name AS category_name FROM subcategories s LEFT JOIN categories c ON s.category_id=c.id");
$subcategories = [];
while ($row = $result->fetch_assoc()) {
    $subcategories[] = $row;
}
echo json_encode($subcategories);
?>
