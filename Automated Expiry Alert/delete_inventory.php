<?php
$connection = new mysqli("localhost", "root", "", "inventory_db");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productName = $connection->real_escape_string($_POST["productName"]);

    $query = "DELETE FROM inventory WHERE productName='$productName'";
    if ($connection->query($query) === TRUE) {
        echo "Item deleted successfully.";
    } else {
        echo "Error deleting item: " . $connection->error;
    }
}

$connection->close();
?>
