<?php
include 'connection.php';

if (isset($_GET['bill_number'])) {
    $bill_number = $_GET['bill_number'];

    $query = "SELECT b.*, p.productName 
              FROM billing b
              INNER JOIN inventory p ON b.product_id = p.id
              WHERE b.bill_number = '$bill_number'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Bill Details</h2><table border='1' cellpadding='10'>";
        echo "<tr><th>Bill Number</th><th>Date</th><th>Product Name</th><th>Quantity</th><th>Subtotal</th></tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['bill_number']}</td>
                    <td>{$row['date']}</td>
                    <td>{$row['productName']}</td>
                    <td>{$row['quantity']}</td>
                    <td>₹" . number_format($row['subtotal'], 2) . "</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No bill found with bill number $bill_number</p>";
    }
} else {
    echo "No bill number provided.";
}
?>
