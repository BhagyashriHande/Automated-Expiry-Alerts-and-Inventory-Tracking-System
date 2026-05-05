<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

// ✅ Get raw JSON input
$inputJSON = file_get_contents('php://input');
$data = json_decode($inputJSON, true);

// ✅ Validate input
if (!isset($data['items']) || !is_array($data['items']) || count($data['items']) === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid input structure']);
    exit;
}

$items = $data['items'];
$total = isset($data['total']) ? (float)$data['total'] : 0;
$date = date("Y-m-d");

// ✅ Generate next bill number
$billQuery = "SELECT COUNT(DISTINCT bill_number) AS total FROM billing";
$billResult = $conn->query($billQuery);
$billCount = $billResult->fetch_assoc()['total'];
$billNumber = "BILLNO" . ($billCount + 1);

// ✅ Insert each item
$success = true;
$productNames = [];

foreach ($items as $item) {
    if (!isset($item['product_id'], $item['productName'], $item['quantity'], $item['itemTotal'])) {
        $success = false;
        break;
    }

    $productId = (int)$item['product_id'];
    $productName = $conn->real_escape_string($item['productName']);
    $quantity = (int)$item['quantity'];
    $subtotal = (float)$item['itemTotal'];

    $productNames[] = $productName;

    // ✅ Insert into billing table
    $insertQuery = "INSERT INTO billing (bill_number, product_id, product_name, quantity, subtotal, date) 
                    VALUES ('$billNumber', '$productId', '$productName', '$quantity', '$subtotal', '$date')";

    if (!$conn->query($insertQuery)) {
        $success = false;
        break;
    }

    // ✅ Decrease stock in inventory
    $conn->query("UPDATE inventory SET quantity = quantity - $quantity WHERE id = $productId");
}

if ($success) {
    echo json_encode([
        'success' => true,
        'message' => "Bill saved successfully as $billNumber!",
        'products' => implode(", ", $productNames)
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save bill.']);
}

$conn->close();
?>
