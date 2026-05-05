document.addEventListener('DOMContentLoaded', function () {
    const addItemForm = document.getElementById('addItemForm');
    const productSelect = document.getElementById('productSelect');
    const quantityInput = document.getElementById('quantityInput');
    const billItems = document.getElementById('billItems');
    const totalAmount = document.getElementById('totalAmount');
    const saveBillBtn = document.getElementById('saveBillBtn');
    const printBillBtn = document.getElementById('printBillBtn');
    const historyContainer = document.getElementById('billingHistory');

    let bill = [];
    let total = 0;

    addItemForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const selectedProduct = productSelect.options[productSelect.selectedIndex];

        if (!selectedProduct) {
            alert('Please select a product.');
            return;
        }

        const productId = selectedProduct.getAttribute('data-product-id');
        const productName = selectedProduct.textContent.split(' - ')[0]; // Extract name from option text
        const productPrice = parseFloat(selectedProduct.getAttribute('data-price'));
        const availableQuantity = parseInt(selectedProduct.getAttribute('data-quantity'));
        const enteredQuantity = parseInt(quantityInput.value);

        if (!productId || isNaN(enteredQuantity) || enteredQuantity <= 0) {
            alert('Please select a product and enter a valid quantity.');
            return;
        }

        if (enteredQuantity > availableQuantity) {
            alert('Insufficient stock. Please enter a quantity within the available limit.');
            return;
        }

        const itemTotal = productPrice * enteredQuantity;
        total += itemTotal;

        bill.push({
            product_id: productId,
            productName: productName,
            quantity: enteredQuantity,
            price: productPrice,
            itemTotal
        });

        updateBillSummary();
        saveBillBtn.disabled = false;
        printBillBtn.disabled = false;
        addItemForm.reset();
    });

    function updateBillSummary() {
        billItems.innerHTML = '';

        if (bill.length === 0) {
            document.getElementById('emptyBillMessage').style.display = 'block';
        } else {
            document.getElementById('emptyBillMessage').style.display = 'none';

            bill.forEach(item => {
                const itemRow = document.createElement('div');
                itemRow.className = 'd-flex justify-content-between mb-2';
                itemRow.innerHTML = `
                    <div>${item.productName} (x${item.quantity})</div>
                    <div>${item.itemTotal.toFixed(2)}₹</div>
                `;
                billItems.appendChild(itemRow);
            });
        }

        totalAmount.textContent = total.toFixed(2) + '₹';
    }

    saveBillBtn.addEventListener('click', function () {
        if (bill.length === 0) {
            alert('No items to save.');
            return;
        }

        const billData = {
            items: bill,
            total: total
        };

        fetch('save_bill.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(billData)
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Failed to save bill: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    async function fetchProducts() {
        try {
            const response = await fetch('inventory_fetch.php');
            const products = await response.json();

            productSelect.innerHTML = '<option value="" disabled selected>Select a product</option>';
            

            products.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = `${product.productName} - ₹${product.price}`;
                option.setAttribute('data-price', product.price);
                option.setAttribute('data-quantity', product.quantity);
                option.setAttribute('data-product-id', product.id);

                productSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error fetching products:', error);
        }
    }

    async function fetchBillingHistory() {
        try {
            const response = await fetch('Billing.php');
            const billingHistory = await response.json();

            historyContainer.innerHTML = '';

            if (billingHistory.length === 0) {
                historyContainer.innerHTML = '<p>No billing history available.</p>';
                return;
            }

            billingHistory.forEach(bill => {
                const billEntry = document.createElement('div');
                billEntry.className = 'billing-entry mb-3 p-2 border rounded';

                billEntry.innerHTML = `
                    <div><strong>Date:</strong> ${bill.date}</div>
                    <div><strong>Total Amount:</strong> ₹${bill.total_amount}</div>
                    <div><strong>Product Names:</strong> ${bill.productName}</div>
                `;

                historyContainer.appendChild(billEntry);
            });
        } catch (error) {
            console.error('Error fetching billing history:', error);
        }
    }
    printBillBtn.addEventListener('click', function () {
        if (bill.length === 0) {
            alert('No items to print.');
            return;
        }
    
        let printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Bill</title>');
        printWindow.document.write('<style>body{font-family:Arial} .bill-item{display:flex;justify-content:space-between;margin-bottom:5px;}</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h2>Bill Summary</h2>');
        
        bill.forEach(item => {
            printWindow.document.write(`
                <div class="bill-item">
                    <div>${item.productName} (x${item.quantity})</div>
                    <div>${item.itemTotal.toFixed(2)}₹</div>
                </div>
            `);
        });
    
        printWindow.document.write(`<hr><strong>Total: ₹${total.toFixed(2)}</strong>`);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    });
    

    fetchProducts();
    fetchBillingHistory();
    printBillBtn();
});
