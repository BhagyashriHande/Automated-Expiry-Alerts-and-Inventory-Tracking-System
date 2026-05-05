<?php
include 'connection.php';

$query = "SELECT b.*, p.productName 
          FROM billing b
          INNER JOIN inventory p ON b.product_id = p.id
          ORDER BY b.date DESC";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Billing History - Medical Inventory Billing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Billing.css">
    <script>
        function printBill(billNumber) {
            window.open("print_bill.php?bill_number=" + billNumber, "_blank");
        }
    </script>
</head>
<body>
    <header class="bg-white shadow-sm">
    <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                        <path d="M12 8v4l3 3"></path>
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <h1 class="ms-3 h3 mb-0 fw-bold">Billing History</h1>
                </div>
                <a href="Billing.php" class="btn btn-outline-primary d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5"></path>
                        <path d="M12 19l-7-7 7-7"></path>
                    </svg>
                    Back to Billing
                </a>
            </div>
        </div>
    </header>

    <main class="container py-4">
        <h1 class="mb-4">Billing History</h1>
        <!-- New added code -->
        <div class="row">
            <div class="col-12">
                <div class="bg-white p-4 rounded shadow-sm">
                    <!-- Search and Filter Section -->
                    <div class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <path d="m21 21-4.3-4.3"></path>
                                        </svg>
                                    </span>
                                    <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search by bill number...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input type="date" id="dateFilter" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <select id="amountFilter" class="form-select">
                                    <option value="">Filter by amount</option>
                                    <option value="0-100">₹0 - ₹100</option>
                                    <option value="101-500">₹101 - ₹500</option>
                                    <option value="501+">₹501+</option>
                                </select>
                                
                            </div>
                        </div>
                    </div>

                    <!-- Code for table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Bill Number</th>
                                    <th>Date</th>
                                    <th>Product ID</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Action</th> <!-- Added Action column -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?= $row['bill_number']; ?></td>
                                            <td><?= $row['date']; ?></td>
                                            <td><?= $row['product_id']; ?></td>
                                            <td><?= $row['product_name']; ?></td> <!-- Fixed key from productName to product_name -->
                                            <td><?= $row['quantity']; ?></td>
                                            <td>₹<?= number_format($row['subtotal'], 2); ?></td>
                                            <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="printBill('<?= $row['bill_number']; ?>')">
                                           🖨️ Print
                                           </button>

                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No billing records found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State Message -->
                    <div id="emptyHistoryMessage" class="text-center text-muted py-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-3 text-muted">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="3" y1="9" x2="21" y2="9"></line>
                            <line x1="9" y1="21" x2="9" y2="9"></line>
                        </svg>
                        <h5>No Billing History</h5>
                        <p class="text-muted">No bills have been generated yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="History.js"></script>
</body>
</html>
