<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_db";

// ✅ Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// ❌ Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$today = date("Y-m-d");
$nextWeek = date("Y-m-d", strtotime("+7 days"));

$query = "SELECT productName, expiryDate FROM inventory WHERE expiryDate BETWEEN '$today' AND '$nextWeek'";
$result = $conn->query($query);

$alerts = [];
while ($row = $result->fetch_assoc()) {
    $alerts[] = $row;
}
$count = count($alerts);

// 🟢 Get all products
$allProducts = $conn->query("SELECT productName, quantity, expiryDate FROM inventory");

// 🟡 Get expiring within 7 days (already done as $alerts, but let's make it formal for consistency)
$expiringSoon = $conn->query("SELECT productName, expiryDate FROM inventory WHERE expiryDate BETWEEN '$today' AND '$nextWeek'");

// 🔴 Get low stock items (assuming threshold = 10)
$lowStockList = $conn->query("SELECT productName, quantity FROM inventory WHERE quantity <= 10");

// ⚫ Get expired items
$expiredList = $conn->query("SELECT productName, expiryDate FROM inventory WHERE expiryDate < '$today'");

$totalCount = $allProducts->num_rows;
$expiringCount = $expiringSoon->num_rows;
$lowStockCount = $lowStockList->num_rows;
$expiredCount = $expiredList->num_rows;



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Automated Expiry Alerts</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="Home.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<!-- ✅ Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a href="Home.php"><img src="medicare.avif" class="nav-img" alt="Logo"></a>

    <div class="collapse navbar-collapse">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="Home.php"><i class="fa-solid fa-house"></i> Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Inventory.php"><i class="fa-solid fa-box"></i> Inventory</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Billing.php"><i class="fa-solid fa-receipt"></i> Billing</a>
        </li>
      </ul>
    </div>

   <!-- 🔔 Notification Bell -->
<li class="nav-item ms-auto position-relative" style="list-style:none;">
  <a class="nav-link" href="#" id="notifyBtn">
    <i class="fa-solid fa-bell"></i>
    <?php if ($count > 0): ?>
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        <?php echo $count; ?>
      </span>
    <?php endif; ?>
  </a>
</li>

<!-- 🚪 Logout Button with icon -->
<a class="logout" href="#" id="logoutBtn"><i class="fa-solid fa-right-from-bracket me-2"></i>Log Out</a>


  </div>
</nav>

<!-- 🖼️ Image Carousel -->
<div id="carousel-container" class="container my-4" style="max-width: 100%; height: 400px;">
<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="1200">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner rounded shadow">
      <div class="carousel-item active">
        <img src="images1/images/Image3.avif" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Image 1">
      </div>
      <div class="carousel-item">
        <img src="images1/images/Image2.webp" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Image 2">
      </div>
      <div class="carousel-item">
        <img src="images1/images/Image7.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Image 3">
      </div>
      <div class="carousel-item">
        <img src="images1/images/Image5.png" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Image 4">
      </div>
      <div class="carousel-item">
        <img src="images1/images/Image6.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Image 5">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</div>

<div class="container my-5">
  <div class="row g-4 justify-content-center text-white">
    <div class="col-md-3">
      <div class="card bg-success text-center shadow h-100" id="totalCard">
        <div class="card-body">
          <h5 class="card-title">Total Products</h5>
          <h3><?= $totalCount ?></h3>
          <p class="card-text">Click to view all products</p>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card bg-warning text-center shadow h-100" id="expiringCard">
        <div class="card-body">
          <h5 class="card-title">Expiring This Week</h5>
          <h3><?= $expiringCount ?></h3>
          <p class="card-text">Click to view expiring items</p>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card bg-danger text-center shadow h-100" id="lowStockCard">
        <div class="card-body">
          <h5 class="card-title">Low Stock</h5>
          <h3><?= $lowStockCount ?></h3>
          <p class="card-text">Click to view low stock</p>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card bg-dark text-white text-center shadow h-100" id="expiredCard">
        <div class="card-body">
          <h5 class="card-title">Expired Items</h5>
          <h3><?= $expiredCount ?></h3>
          <p class="card-text">Click to view expired products</p>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- ✅ Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // 🔐 Logout confirmation
  document.getElementById("logoutBtn").addEventListener("click", function (event) {
    event.preventDefault();
    if (confirm("Are you sure you want to log out?")) {
      window.location.href = "login.php";
    }
  });

  // 🔔 Expiry Alert Modal
  document.getElementById("notifyBtn").addEventListener("click", function (event) {
    event.preventDefault();
    Swal.fire({
      title: "Expiry Alerts",
      icon: "warning",
      html: `
        <ul style="text-align:left; padding-left: 20px;">
          <?php foreach($alerts as $alert): ?>
            <li><strong><?= $alert['productName'] ?></strong> expires on <?= $alert['expiryDate'] ?></li>
          <?php endforeach; ?>
        </ul>
      `,
      confirmButtonText: "Got it!",
      customClass: {
        popup: 'rounded'
      }
    });
  });

  // 🟢 Total Products
document.getElementById("totalCard").addEventListener("click", () => {
  Swal.fire({
    title: "All Products",
    html: `
      <ul style="text-align:left; padding-left:20px; max-height:300px; overflow-y:auto;">
        <?php while($row = $allProducts->fetch_assoc()): ?>
          <li><strong><?= $row['productName'] ?></strong> - Qty: <?= $row['quantity'] ?> | Exp: <?= $row['expiryDate'] ?></li>
        <?php endwhile; ?>
      </ul>
    `,
    width: 600,
    showConfirmButton: true
  });
});

// 🟡 Expiring This Week
document.getElementById("expiringCard").addEventListener("click", () => {
  Swal.fire({
    title: "Expiring This Week",
    html: `
      <ul style="text-align:left; padding-left:20px; max-height:300px; overflow-y:auto;">
        <?php foreach($alerts as $row): ?>
          <li><strong><?= $row['productName'] ?></strong> - Exp: <?= $row['expiryDate'] ?></li>
        <?php endforeach; ?>
      </ul>
    `,
    width: 600,
    showConfirmButton: true
  });
});

// 🔴 Low Stock
document.getElementById("lowStockCard").addEventListener("click", () => {
  Swal.fire({
    title: "Low Stock Items",
    html: `
      <ul style="text-align:left; padding-left:20px; max-height:300px; overflow-y:auto;">
        <?php while($row = $lowStockList->fetch_assoc()): ?>
          <li><strong><?= $row['productName'] ?></strong> - Qty: <?= $row['quantity'] ?></li>
        <?php endwhile; ?>
      </ul>
    `,
    width: 600,
    showConfirmButton: true
  });
});

// ⚫ Expired Items
document.getElementById("expiredCard").addEventListener("click", () => {
  Swal.fire({
    title: "Expired Items",
    icon: "error",
    html: `
      <ul style="text-align:left; padding-left:20px; max-height:300px; overflow-y:auto;">
        <?php while($row = $expiredList->fetch_assoc()): ?>
          <li><strong><?= $row['productName'] ?></strong> - Expired on <?= $row['expiryDate'] ?></li>
        <?php endwhile; ?>
      </ul>
    `,
    width: 600,
    showConfirmButton: true
  });
});

</script>

<!-- ✅ Footer -->
<footer>
  <div class="foot-panel1 text-center py-3">
    <a href="Home.php">Back to top</a>
  </div>
</footer>

<?php include 'footer.php'; ?>

</body>
</html>
