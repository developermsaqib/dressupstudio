<?php
include 'connection.php';
$result = $conn->query("SELECT * FROM orders");
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
echo json_encode($orders);
?>
