<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Inventory Billing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Billing.css">
</head>
<body class="bg-light">
    <header class="bg-white shadow-sm">
        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <path d="M3.29 7.03 12 12l8.71-4.97"></path>
                        <path d="M12 22V12"></path>
                    </svg>
                    <h1 class="ms-3 h3 mb-0 fw-bold">Medical Inventory Billing</h1>
                </div>
                <a href="History.php
                " class="btn btn-outline-primary d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 8v4l3 3"></path>
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    Billing History
                </a>
            </div>
        </div>
    </header>

    <main class="container py-4">
        <div class="row g-4">
            <!-- Add Items Section -->
            <div class="col-lg-6">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h2 class="h5 mb-4">Add Items to Bill</h2>
                    <form id="addItemForm" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Product</label>
                            <select id="productSelect" class="form-select" required>
                                <option value="">Select a product</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" id="quantityInput" class="form-control" min="1" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                                <path d="M12 5v14"></path>
                                <path d="M5 12h14"></path>
                            </svg>
                            Add to Bill
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bill Summary Section -->
            <div class="col-lg-6">
                <div class="bg-white p-4 rounded shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h5 mb-0">Bill Summary</h2>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted">
                            <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1-2-1Z"></path>
                            <path d="M16 8h-6"></path>
                            <path d="M16 12h-6"></path>
                            <path d="M16 16h-6"></path>
                        </svg>
                    </div>

                    <div id="billItems" class="mb-4">
                        <!-- Bill items will be inserted here -->
                    </div>

                    <div id="emptyBillMessage" class="text-center text-muted py-4">
                        No items added to bill
                    </div>

                    <div class="border-top pt-3 mt-3">
                        <div class="d-flex justify-content-between align-items-center h5">
                            <span>Total Amount:</span>
                            <span id="totalAmount">0.00₹</span>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button id="saveBillBtn" class="btn btn-success flex-grow-1 d-flex align-items-center justify-content-center" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                            Save Bill
                        </button>
                        <button id="printBillBtn" class="btn btn-primary d-flex align-items-center justify-content-center" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                <rect x="6" y="14" width="12" height="8"></rect>
                            </svg>
                            Print Bill
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Print Template (hidden by default) -->
    <div id="printTemplate" class="d-none">
        <div class="print-header text-center mb-4">
            <h1>Medical Inventory Bill</h1>
            <p class="mb-0">Date: <span id="printDate"></span></p>
            <p>Bill Number: <span id="printBillNumber"></span></p>
        </div>
        <div id="printItems"></div>
        <div class="print-footer mt-4">
            <div class="d-flex justify-content-between">
                <strong>Total Amount:</strong>
                <span id="printTotal"></span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="Billing.js"></script>
</body>
</html>