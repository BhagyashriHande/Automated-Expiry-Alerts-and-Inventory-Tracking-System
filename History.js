document.addEventListener('DOMContentLoaded', function () {
    const billNumberInput = document.querySelector('input[placeholder="Search by bill number..."]');
    const dateInput = document.querySelector('input[type="date"]');
    const amountFilter = document.querySelector('select');
    const tableBody = document.querySelector('table tbody');
    const allRows = Array.from(tableBody.querySelectorAll('tr'));

    // Filtering logic
    function applyFilters() {
        const billNumberValue = billNumberInput.value.toLowerCase();
        const selectedDate = dateInput.value;
        const selectedAmountRange = amountFilter.value.trim().split('-');
        let minAmount = 0, maxAmount = Infinity;

        if (selectedAmountRange.length === 2) {
            minAmount = parseFloat(selectedAmountRange[0].replace(/[₹,]/g, '')) || 0;
            maxAmount = parseFloat(selectedAmountRange[1].replace(/[₹,]/g, '')) || Infinity;
        }

        allRows.forEach(row => {
            const billNo = row.cells[0].textContent.toLowerCase();
            const billDate = row.cells[1].textContent;
            const amount = parseFloat(row.cells[5].textContent.replace(/[₹,]/g, ''));

            const matchBill = billNo.includes(billNumberValue);
            const matchDate = selectedDate === '' || billDate === selectedDate;
            const matchAmount = amount >= minAmount && amount <= maxAmount;

            row.style.display = (matchBill && matchDate && matchAmount) ? '' : 'none';
        });
    }

    // Attach filter listeners
    if (billNumberInput) billNumberInput.addEventListener('input', applyFilters);
    if (dateInput) dateInput.addEventListener('change', applyFilters);
    if (amountFilter) amountFilter.addEventListener('change', applyFilters);

    // Print function (used by onclick in button)
    window.printBill = function (billNo) {
        const row = Array.from(document.querySelectorAll('table tbody tr')).find(
            tr => tr.cells[0].textContent.trim() === billNo
        );

        if (!row) {
            alert("Bill not found!");
            return;
        }

        const date = row.cells[1].textContent;
        const quantity = row.cells[2].textContent;
        const productName = row.cells[3].textContent;
        const subtotal = row.cells[5].textContent;

        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Bill</title>');
        printWindow.document.write('<style>body{font-family:Arial;margin:20px;} .bill-summary{max-width:400px;margin:auto;} .bill-summary h2{text-align:center;} .bill-item{display:flex;justify-content:space-between;margin-bottom:10px;} .total{font-weight:bold;margin-top:20px;text-align:right;}</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<div class="bill-summary">');
        printWindow.document.write('<h2>Bill Summary</h2>');
        printWindow.document.write(`<p><strong>Bill Number:</strong> ${billNo}</p>`);
        
        printWindow.document.write(`<p><strong>Date:</strong> ${date}</p>`);
        printWindow.document.write(`<p><strong>Product Name:</strong> ${productName}</p>`);
        printWindow.document.write(`<p><strong>Quantity:</strong> ${quantity}</p>`);
        printWindow.document.write(`<div class="bill-item"><span>Subtotal</span><span>${subtotal}</span></div>`);
        
        printWindow.document.write(`<div class="total">Total: ${subtotal}</div>`);
        printWindow.document.write('</div>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    }
});
