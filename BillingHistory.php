<?php
include('connection.php');

$query = "SELECT * FROM billing ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Billing History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Billing History</h1>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Bill Number</th>
                    <th>Date</th>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['bill_number']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['product_id']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>₹<?php echo number_format($row['subtotal'], 2); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
