// DOM Elements
const searchInput = document.getElementById('searchInput');
const dateFilter = document.getElementById('dateFilter');
const amountFilter = document.getElementById('amountFilter');
const historyTableBody = document.getElementById('historyTableBody');
const emptyHistoryMessage = document.getElementById('emptyHistoryMessage');

let billingHistory = [];
let filteredHistory = [];

// Load billing history from localStorage
function loadBillingHistory() {
    const savedHistory = localStorage.getItem('billingHistory');
    if (savedHistory) {
        billingHistory = JSON.parse(savedHistory);
        filteredHistory = [...billingHistory];
    } else {
        billingHistory = [];
        filteredHistory = [];
    }
    updateHistoryDisplay();
}

// Format date for display
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Print bill function
function printBill(billData) {
    const printWindow = window.open('', '_blank');
    if (!printWindow) {
        alert('Please allow pop-ups to print the bill.');
        return;
    }

    const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Medical Bill - ${billData.billNumber}</title>
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
                <p>Date: ${formatDate(billData.date)}</p>
                <p>Bill Number: ${billData.billNumber}</p>
            </div>
            <div class="items">
                ${billData.items.map(item => `
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
                <div>₹${billData.total.toFixed(2)}</div>
            </div>
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

// Update history display
function updateHistoryDisplay() {
    if (!historyTableBody || !emptyHistoryMessage) return;

    if (filteredHistory.length === 0) {
        emptyHistoryMessage.style.display = 'block';
        historyTableBody.innerHTML = '';
        return;
    }

    emptyHistoryMessage.style.display = 'none';
    historyTableBody.innerHTML = '';

    // Sort history by date (newest first)
    const sortedHistory = [...filteredHistory].sort((a, b) => new Date(b.date) - new Date(a.date));

    sortedHistory.forEach(bill => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <span class="fw-medium">${bill.billNumber}</span>
            </td>
            <td>${formatDate(bill.date)}</td>
            <td>
                <span class="badge bg-light text-dark">${bill.items.length} items</span>
                <div class="small text-muted mt-1">
                    ${bill.items.map(item => item.name).join(', ')}
                </div>
            </td>
            <td>
                <span class="fw-medium">₹${bill.total.toFixed(2)}</span>
            </td>
            <td>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary print-bill" data-bill-number="${bill.billNumber}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary view-details" data-bs-toggle="modal" data-bs-target="#billDetailsModal" data-bill-number="${bill.billNumber}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                    </button>
                </div>
            </td>
        `;
        historyTableBody.appendChild(row);
    });

    // Add event listeners for print buttons
    document.querySelectorAll('.print-bill').forEach(button => {
        button.addEventListener('click', (e) => {
            const billNumber = e.currentTarget.dataset.billNumber;
            const bill = billingHistory.find(b => b.billNumber === billNumber);
            if (bill) {
                printBill(bill);
            }
        });
    });
}

// Filter functions
function applyFilters() {
    const searchTerm = searchInput.value.toLowerCase();
    const selectedDate = dateFilter.value;
    const selectedAmount = amountFilter.value;

    filteredHistory = billingHistory.filter(bill => {
        // Search filter
        const matchesSearch = bill.billNumber.toLowerCase().includes(searchTerm);

        // Date filter
        const matchesDate = !selectedDate || bill.date.startsWith(selectedDate);

        // Amount filter
        let matchesAmount = true;
        if (selectedAmount) {
            const [min, max] = selectedAmount.split('-').map(val => val.replace('+', '9999999'));
            matchesAmount = bill.total >= Number(min) && (max ? bill.total <= Number(max) : true);
        }

        return matchesSearch && matchesDate && matchesAmount;
    });

    updateHistoryDisplay();
}

// Event listeners
if (searchInput) searchInput.addEventListener('input', applyFilters);
if (dateFilter) dateFilter.addEventListener('change', applyFilters);
if (amountFilter) amountFilter.addEventListener('change', applyFilters);

// Initialize
loadBillingHistory();