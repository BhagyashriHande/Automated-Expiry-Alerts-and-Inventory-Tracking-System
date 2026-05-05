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
    
    <nav class="navbar navbar-expand-lg navbar-light bg-pink  navbar-fixed-top">
        <div class="container-fluid" >
          <!-- <a class="navbar-brand" href="#">TitleName</a> -->
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <img src="medicare.avif" class="nav-img" alt="">
                <a class="nav-link" href="Home.php">Home</a>
              <a class="nav-link" href="Inventory.php">Inventory</a>
              <a class="nav-link" href="Billing.php">Billing</a>
              
              <div class="nav-search">
                <input placeholder="   Search for Product..." class="search-input" type="search" name="query" id="searchInput">
                <button type="submit" class="search-button">
                <i class="fa-solid fa-magnifying-glass"></i>
              </button>
            </div>
          </div>
        </div>
      </nav>
     
    <div class="container my-5 ">
        <!-- <h1 class="text-center gradient-text" >Inventory Tracking</h1> -->
        <div class="card shadow p-4">
            <form id="inventoryForm">
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
