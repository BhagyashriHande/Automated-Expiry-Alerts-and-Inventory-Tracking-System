document.addEventListener("DOMContentLoaded", fetchInventory);

// Add new item to the database
document.getElementById("addItem").addEventListener("click", function () {
    const productName = document.getElementById("productName").value.trim();
    const price = document.getElementById("price").value.trim();
    const quantity = document.getElementById("quantity").value.trim();
    const manufacturedDate = document.getElementById("manufacturedDate").value.trim();
    const expiryDate = document.getElementById("expiryDate").value.trim();
    const productImage = document.getElementById("file").files[0];

    if (productName && price && quantity && manufacturedDate && expiryDate && productImage) {
        const formData = new FormData();
        formData.append("productName", productName);
        formData.append("price", price);
        formData.append("quantity", quantity);
        formData.append("manufacturedDate", manufacturedDate);
        formData.append("expiryDate", expiryDate);
        formData.append("productImage", productImage); // Include image

        fetch("inventoryphp.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            fetchInventory(); // Refresh table after adding a new record
            document.getElementById("inventoryForm").reset();
            document.getElementById("preview").style.display = "none"; // Hide preview after submit
        })
        .catch(error => console.error('Error:', error));
    } else {
        alert("Please fill all fields and select an image.");
    }
});

// Fetch data from the database and display it
function fetchInventory() {
    fetch("inventory_fetch.php")
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById("inventoryTable");
            tableBody.innerHTML = "";
            data.forEach(item => {
                addRowToTable(item.productName, item.price, item.quantity, item.manufacturedDate, item.expiryDate);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Add a row to the table with expiry check
function addRowToTable(productName, price, quantity, manufacturedDate, expiryDate) {
    const table = document.getElementById("inventoryTable");
    const newRow = table.insertRow();

    const alertMessage = getExpiryAlert(expiryDate, productName);

    newRow.innerHTML = `
        <td>${productName}</td>
        <td>${price}</td>
        <td>${quantity}</td>
        <td>${manufacturedDate}</td>
        <td>${expiryDate}</td>
        <td>${alertMessage}</td>
        <td>
            <button class="btn btn-danger btn-sm delete-item">Delete</button>
        </td>
    `;

    newRow.querySelector(".delete-item").addEventListener("click", function () {
        deleteItem(productName);
    });
}

// Function to send email notification
function sendEmailNotification(subject, message) {
    fetch("send_email.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `subject=${encodeURIComponent(subject)}&message=${encodeURIComponent(message)}`
    })
    .then(response => response.text())
    .then(data => console.log("Email sent response:", data))
    .catch(error => console.error('Error:', error));
}

const notifiedProducts = {};
// Generate expiry alert message and send email if necessary
function getExpiryAlert(expiryDate, productName) {
    const today = new Date();
    const expiryParts = expiryDate.split('-'); // Format: YYYY-MM-DD
    const expiry = new Date(expiryParts[0], expiryParts[1] - 1, expiryParts[2]);

    today.setHours(0, 0, 0, 0);
    expiry.setHours(0, 0, 0, 0);

    const timeDiff = expiry.getTime() - today.getTime();
    const daysLeft = Math.floor(timeDiff / (1000 * 60 * 60 * 24));

    console.log(`Product: ${productName}, Days Left: ${daysLeft}, Expiry: ${expiryDate}`);

    if (daysLeft > 0 && daysLeft <= 4) {
        if (!notifiedProducts[productName + "_expiring"]) {
            sendEmailNotification(
                "Expiry Warning",
                `The product '${productName}' expires in ${daysLeft} day(s)! Expiry Date: ${expiryDate}`
            );
            notifiedProducts[productName + "_expiring"] = true;
        }
        return `<span class="badge bg-warning text-dark">Expiring in ${daysLeft} day(s)</span>`;
    } else if (daysLeft === 0) {
        if (!notifiedProducts[productName + "_today"]) {
            sendEmailNotification(
                "Expiry Today",
                `The product '${productName}' is expiring today! Expiry Date: ${expiryDate}`
            );
            notifiedProducts[productName + "_today"] = true;
        }
        return `<span class="badge bg-danger">Expires Today</span>`;
    } else if (daysLeft < 0) {
        if (!notifiedProducts[productName + "_expired"]) {
            sendEmailNotification(
                "ALERT! Expired Product",
                `The product '${productName}' has expired! Expiry Date: ${expiryDate}`
            );
            notifiedProducts[productName + "_expired"] = true;
        }
        return `<span class="badge bg-danger">Expired</span>`;
    } else {
        return `<span class="badge bg-success">Valid</span>`;
    }
}

// Delete item from the database
function deleteItem(productName) {
    fetch("delete_inventory.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `productName=${encodeURIComponent(productName)}`
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        fetchInventory();
    })
    .catch(error => console.error('Error:', error));
}
