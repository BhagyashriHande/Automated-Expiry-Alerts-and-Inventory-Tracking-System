
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="Inventory.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
    
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        
        <!-- ✅ Logo -->
        <a href="Home.php">
            <img src="medicare.avif" class="nav-img" alt="Logo">
        </a>

         <!-- ✅ Navbar Links with Icons -->
         <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="Home.php">
                        <i class="fa-solid fa-house"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Inventory.php">
                        <i class="fa-solid fa-box"></i> Inventory
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Billing.php">
                        <i class="fa-solid fa-receipt"></i> Billing
                    </a>
                </li>
            </ul>
        </div>
        <!-- ✅ Centered Search Bar -->
        <div class="search-container">
            <form action="search_result.php" method="GET" class="d-flex">
                <input name="query" placeholder="Search for Product..." class="form-control" type="search" required />
                <button type="submit" class="btn btn-outline-dark">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>

</nav>
    <div class="container mt-3 ">
        <h1 class="text-center gradient-text" >Inventory Tracking</h1>
        <div class="card shadow p-4">
        <form id="inventoryForm" method="POST" action="Inventoryphp.php" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="productName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="productName" placeholder="Enter product name">
                    </div>
                    <div class="col-md-6">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" placeholder="Enter Product Price">
                    </div>
                    <div class="col-md-6">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" placeholder="Enter quantity">
                    </div>
                    <div class="col-md-6">
                        <label for="manufacturedDate" class="form-label">Manufactured Date</label>
                        <input type="date" class="form-control" id="manufacturedDate">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="expiryDate" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" id="expiryDate">
                    </div>
                    <div class="col-md-6">
            <label for="file" class="form-label">Product Image</label>
            <input type="file" id="file" name="productImage" required accept="image/*" class="form-control" onchange="previewImage(event)" >
            <div class="image-preview mt-2">
              <img id="preview" src="#" alt="Image Preview" style="max-width: 100px; display: none; border-radius: 8px;" />
            </div>
          </div>
                </div>
                <div class="text-center my-4">
                    <button type="button" id="addItem" class="btn btn-primary btn-lg shadow">Add Item</button>
                </div>
            </form>
        </div>
        <table class="table table-hover mt-4">
            <thead class="table-dark">
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Manufactured Date</th>
                    <th>Expiry Date</th>
                    <th>Expiry Alert</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="inventoryTable"></tbody>

        </table>
        <div id="alertSection" class="mt-4"></div>
    </div>
    <footer>
  <div class="foot-panel1">
     <a href="Inventory.php"> Back to top </a>
  </div>
</footer>
<?php 
include 'footer.php';
 ?> 
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script src="Inventory.js">
</script>
</body>
</html>