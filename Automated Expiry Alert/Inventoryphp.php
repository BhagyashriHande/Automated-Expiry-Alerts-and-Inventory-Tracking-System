<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productName = isset($_POST["productName"]) ? $conn->real_escape_string($_POST["productName"]) : "";
    $price = isset($_POST["price"]) ? $conn->real_escape_string($_POST["price"]) : "";
    $quantity = isset($_POST["quantity"]) ? $conn->real_escape_string($_POST["quantity"]) : "";
    $manufacturedDate = isset($_POST["manufacturedDate"]) ? $conn->real_escape_string($_POST["manufacturedDate"]) : "";
    $expiryDate = isset($_POST["expiryDate"]) ? $conn->real_escape_string($_POST["expiryDate"]) : "";

    if ($productName && $price && $quantity && $manufacturedDate && $expiryDate) {
        $sql = "INSERT INTO inventory (productName, price, quantity, manufacturedDate, expiryDate)
                VALUES ('$productName', '$price', '$quantity', '$manufacturedDate', '$expiryDate')";

        if ($conn->query($sql) === TRUE) {
            echo "Record inserted successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Please fill all fields.";
    }
}

$conn->close();
