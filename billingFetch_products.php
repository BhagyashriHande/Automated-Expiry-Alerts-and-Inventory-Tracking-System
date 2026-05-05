<?php
include 'connection.php';

$query = "SELECT id, productName FROM inventory";
$result = mysqli_query($conn, $query);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

echo json_encode($products);
?>
