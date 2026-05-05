<?php
// Direct DB connection (no include file)
$conn = new mysqli("localhost", "root", "", "inventory_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize result
$result = null;

// Handle search
if (isset($_GET['query'])) {
    $searchTerm = $conn->real_escape_string($_GET['query']);
    $sql = "SELECT * FROM inventory WHERE productName LIKE '%$searchTerm%'";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Search Results</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
  <style>
    body {
      background-color: #f8f9fa;
    }
    .product-card {
      width: 100%;
      max-width: 550px;
    }
    .product-img {
  width: 100%;                /* make image take full width */
  max-height: 250px;
  height: auto;               /* keep aspect ratio */
  object-fit: contain;        /* OR use cover for full fill */
  border-radius: 10px;
  display: block;
  margin: 0 auto;
  background-color: #f8f8f8;  /* Optional: light background */
}

  </style>
</head>
<body>

<div class="container mt-4">

  <!-- ✅ Back Button in Top-Right -->
  <div class="d-flex justify-content-end">
    <a href="Inventory.php" class="btn btn-outline-primary mb-4">
      <i class="fa-solid fa-arrow-left me-2"></i> Back to Inventory
    </a>
  </div>

  <!-- ✅ Centered Content -->
  <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '
            <div class="card product-card shadow p-3">
              <img src="' . htmlspecialchars($row['imagePath']) . '" class="card-img-top product-img" alt="Product Image">
              <div class="card-body">
                <h5 class="card-title">' . htmlspecialchars($row['productName']) . '</h5>
                <p class="card-text">
                  <strong>Price:</strong> ₹' . $row['price'] . '<br>
                  <strong>Quantity:</strong> ' . $row['quantity'] . '<br>
                  <strong>Manufactured:</strong> ' . $row['manufacturedDate'] . '<br>
                  <strong>Expiry:</strong> ' . $row['expiryDate'] . '
                </p>
              </div>
            </div>';
        }
    } else {
        echo '<div class="alert alert-warning text-center">No matching products found.</div>';
    }
    ?>
  </div>
</div>

</body>
</html>