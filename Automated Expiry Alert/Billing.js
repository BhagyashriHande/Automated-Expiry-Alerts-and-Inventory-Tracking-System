// Product data (in a real application, this would come from a database)
let products = [
    { id: '1', name: 'Surgical Masks', price: 15.99, quantity: 100, category: 'PPE' },
    { id: '2', name: 'Antibiotics', price: 45.50, quantity: 50, category: 'Medicine' },
    { id: '3', name: 'Syringes', price: 12.99, quantity: 200, category: 'Equipment' },
    { id: '4', name: 'Bandages', price: 8.99, quantity: 150, category: 'First Aid' }
];

// State management
let billItems = [];
let currentBillNumber = '';

// DOM Elements
const productSelect = document.getElementById('productSelect');
const quantityInput = document.getElementById('quantityInput');
const addItemForm = document.getElementById('addItemForm');
const billItemsContainer = document.getElementById('billItems');
const emptyBillMessage = document.getElementById('emptyBillMessage');
const totalAmountElement = document.getElementById('totalAmount');
const saveBillBtn = document.getElementById('saveBillBtn');
const printBillBtn = document.getElementById('printBillBtn');

// Update bill display
function updateBillDisplay() {
    if (!billItemsContainer || !emptyBillMessage) return;

    billItemsContainer.innerHTML = '';
    
    if (billItems.length === 0) {
        emptyBillMessage.style.display = 'block';
        saveBillBtn.disabled = true;
        printBillBtn.disabled = true;
    } else {
        emptyBillMessage.style.display = 'none';
        saveBillBtn.disabled = false;
        printBillBtn.disabled = false;
        
        
        document.addEventListener('DOMContentLoaded', function () {
            const button = document.getElementById('someElement'); // Replace 'someElement' with the actual element ID
            if (button) {
                button.addEventListener('click', someFunction);
            } else {
                console.warn("Element 'someElement' not found.");
            }
        });
        
        billItems.forEach(item => {
            const itemElement = document.createElement('div');
            itemElement.className = 'bill-item bg-light rounded p-3 mb-3';
            itemElement.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">${item.name}</h6>
                        <small class="text-muted">₹${item.price} x ${item.quantity}</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fw-bold me-3">₹${item.subtotal.toFixed(2)}</span>
                        <button class="btn btn-link p-0 delete-item" data-id="${item.id}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            billItemsContainer.appendChild(itemElement);
        });
    }

    const total = billItems.reduce((sum, item) => sum + item.subtotal, 0);
    totalAmountElement.textContent = `₹${total.toFixed(2)}`;
}

// Print bill function
function printBill(billData = null) {
    const printWindow = window.open('', '_blank');
    if (!printWindow) {
        alert('Please allow pop-ups to print the bill.');
        return;
    }

    const bill = billData || {
        items: billItems,
        total: billItems.reduce((sum, item) => sum + item.subtotal, 0),
        date: new Date().toISOString(),
        billNumber: currentBillNumber || `BILL-${Date.now()}`
    };

    const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Medical Bill - ${bill.billNumber}</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                }
                .print-header {
                    text-align: center;
                    margin-bottom: 30px;
                }
                .print-header h1 {
                    margin: 0;
                    font-size: 24px;
                }
                .item-row {
                    display: flex;
                    justify-content: space-between;
                    padding: 8px 0;
                    border-bottom: 1px solid #eee;
                }
                .total-row {
                    display: flex;
                    justify-content: space-between;
                    padding: 15px 0;
                    font-weight: bold;
                    border-top: 2px solid #000;
                    margin-top: 20px;
                }
                @media print {
                    body {
                        padding: 0;
                    }
                }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h1>Medical Inventory Bill</h1>
                <p>Date: ${new Date(bill.date).toLocaleDateString()}</p>
                <p>Bill Number: ${bill.billNumber}</p>
            </div>
            <div class="items">
                ${bill.items.map(item => `
                    <div class="item-row">
                        <div>
                            <strong>${item.name}</strong>
                            <br>
                            <small>₹${item.price} x ${item.quantity}</small>
                        </div>
                        <div>₹${item.subtotal.toFixed(2)}</div>
                    </div>
                `).join('')}
            </div>
            <div class="total-row">
                <div>Total Amount:</div>
                <div>₹${bill.total.toFixed(2)}</div>
            </div>
            <button id="someElement">Click Me</button>
</body>
</html>

    `;

    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
    
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 250);
}

// Update product stock
function updateProductStock(productId, quantityToReduce) {
    products = products.map(product => {
        if (product.id === productId) {
            return {
                ...product,
                quantity: product.quantity - quantityToReduce
            };
        }
        return product;
    });
    initializeProducts(); // Refresh the product dropdown
}

// Restore product stock
function restoreProductStock(productId, quantityToRestore) {
    products = products.map(product => {
        if (product.id === productId) {
            return {
                ...product,
                quantity: product.quantity + quantityToRestore
            };
        }
        return product;
    });
    initializeProducts(); // Refresh the product dropdown
}

// Add item to bill
function addItemToBill(productId, quantity) {
    const product = products.find(p => p.id === productId);
    if (!product || quantity <= 0 || quantity > product.quantity) {
        alert('Invalid quantity or insufficient stock!');
        return false;
    }

    const existingItem = billItems.find(item => item.id === product.id);
    if (existingItem) {
        if (product.quantity >= quantity) {
            existingItem.quantity += quantity;
            existingItem.subtotal = existingItem.quantity * existingItem.price;
            updateProductStock(productId, quantity);
        } else {
            alert('Insufficient stock!');
            return false;
        }
    } else {
        billItems.push({
            ...product,
            quantity,
            subtotal: quantity * product.price
        });
        updateProductStock(productId, quantity);
    }

    return true;
}

// Initialize product select options
function initializeProducts() {
    if (!productSelect) return;
    
    productSelect.innerHTML = '<option value="">Select a product</option>';
    products.forEach(product => {
        const option = document.createElement('option');
        option.value = product.id;
        option.textContent = `${product.name} - ₹${product.price} (Stock: ${product.quantity})`;
        option.disabled = product.quantity === 0;
        productSelect.appendChild(option);
    });
}

// Event Listeners
if (addItemForm) {
    addItemForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const productId = productSelect.value;
        const quantity = parseInt(quantityInput.value, 10);

        if (addItemToBill(productId, quantity)) {
            updateBillDisplay();
            addItemForm.reset();
        }
    });
}

if (billItemsContainer) {
    billItemsContainer.addEventListener('click', (e) => {
        const deleteBtn = e.target.closest('.delete-item');
        if (deleteBtn) {
            const itemId = deleteBtn.dataset.id;
            const item = billItems.find(item => item.id === itemId);
            if (item) {
                restoreProductStock(itemId, item.quantity);
                billItems = billItems.filter(item => item.id !== itemId);
                updateBillDisplay();
            }
        }
    });
}

if (saveBillBtn) {
    saveBillBtn.addEventListener('click', async () => {
        if (billItems.length === 0) return;

        currentBillNumber = `BILL-${Date.now()}`;
        const billData = {
            items: billItems,
            total: billItems.reduce((sum, item) => sum + item.subtotal, 0),
            date: new Date().toISOString(),
            billNumber: currentBillNumber
        };

        saveBillBtn.classList.add('btn-loading');
        
        try {
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1000));
            
            // Save to localStorage
            const savedHistory = localStorage.getItem('billingHistory');
            const billingHistory = savedHistory ? JSON.parse(savedHistory) : [];
            billingHistory.push(billData);
            localStorage.setItem('billingHistory', JSON.stringify(billingHistory));

            // Clear the bill after successful save
            billItems = [];
            updateBillDisplay();
            alert('Bill saved successfully!');
        } catch (error) {
            alert('Error saving bill. Please try again.');
        } finally {
            saveBillBtn.classList.remove('btn-loading');
        }
    });
}

if (printBillBtn) {
    printBillBtn.addEventListener('click', () => {
        if (billItems.length > 0) {
            printBill();
        }
    });
}

// Initialize the application
initializeProducts();
updateBillDisplay();