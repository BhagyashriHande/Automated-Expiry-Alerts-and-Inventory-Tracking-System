<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productName = isset($_POST["productName"]) ? $conn->real_escape_string($_POST["productName"]) : "";
    $price = isset($_POST["price"]) ? $conn->real_escape_string($_POST["price"]) : "";
    $quantity = isset($_POST["quantity"]) ? $conn->real_escape_string($_POST["quantity"]) : "";
    $manufacturedDate = isset($_POST["manufacturedDate"]) ? $conn->real_escape_string($_POST["manufacturedDate"]) : "";
    $expiryDate = isset($_POST["expiryDate"]) ? $conn->real_escape_string($_POST["expiryDate"]) : "";
    
    $imagePath = "";

    // 🔽 Handle file upload
    if (isset($_FILES["productImage"]) && $_FILES["productImage"]["error"] === 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // Create uploads folder if not exists
        }

        $fileName = basename($_FILES["productImage"]["name"]);
        $targetFilePath = $targetDir . time() . "_" . $fileName; // To avoid overwrite
        if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFilePath)) {
            $imagePath = $conn->real_escape_string($targetFilePath);
        }
    }

    if ($productName && $price && $quantity && $manufacturedDate && $expiryDate) {
        $sql = "INSERT INTO inventory (productName, price, quantity, manufacturedDate, expiryDate, imagePath)
                VALUES ('$productName', '$price', '$quantity', '$manufacturedDate', '$expiryDate', '$imagePath')";

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
