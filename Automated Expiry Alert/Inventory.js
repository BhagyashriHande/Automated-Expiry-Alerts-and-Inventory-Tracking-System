
// // Inventory.js
// document.addEventListener("DOMContentLoaded", function () {
//     const savedInventory = JSON.parse(localStorage.getItem("inventory")) || [];
//     savedInventory.forEach(item => {
//         addRowToTable(
//             item.productName,
//             item.Price,
//             item.quantity,
//             item.manufacturedDate,
//             item.expiryDate
//         );
//     });
// });

// function addRowToTable(productName, Price, quantity, manufacturedDate, expiryDate) {
//     const table = document.getElementById("inventoryTable");
//     const newRow = table.insertRow();

//     const alertMessage = getExpiryAlert(expiryDate);

//     newRow.innerHTML = `
//         <td>${productName}</td>
//         <td>${Price}</td>
//         <td>${quantity}</td>
//         <td>${manufacturedDate}</td>
//         <td>${expiryDate}</td>
//         <td>${alertMessage}</td>
//         <td>
//             <button class="btn btn-danger btn-sm delete-item">Delete</button>
//         </td>
//     `;

//     newRow.querySelector(".delete-item").addEventListener("click", function () {
//         this.closest("tr").remove();
//         saveInventory();
//     });
// }

// function saveInventory() {
//     const table = document.getElementById("inventoryTable");
//     const rows = table.getElementsByTagName("tr");
//     const inventory = [];

//     for (let i = 0; i < rows.length; i++) {
//         const cells = rows[i].getElementsByTagName("td");
//         inventory.push({
//             productName: cells[0].innerText,
//             Price: cells[1].innerText,
//             quantity: cells[2].innerText,
//             manufacturedDate: cells[3].innerText,
//             expiryDate: cells[4].innerText
//         });
//     }

//     localStorage.setItem("inventory", JSON.stringify(inventory));
// }

// // Generate expiry alert message
// function getExpiryAlert(expiryDate) {
//     const today = new Date();
//     const expiry = new Date(expiryDate);
//     const timeDiff = expiry - today;
//     const daysLeft = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));

//     if (daysLeft > 0 && daysLeft <= 4) {
//         return `<span class="badge bg-warning text-dark">Expiring in ${daysLeft} day(s)</span>`;
//     } else if (daysLeft <= 0) {
//         return `<span class="badge bg-danger">Expired</span>`;
//     } else {
//         return `<span class="badge bg-success">Valid</span>`;
//     }
// }

// document.getElementById("addItem").addEventListener("click", function () {
//     const productName = document.getElementById("productName").value.trim();
//     const price = document.getElementById("price").value.trim();
//     const quantity = document.getElementById("quantity").value.trim();
//     const manufacturedDate = document.getElementById("manufacturedDate").value.trim();
//     const expiryDate = document.getElementById("expiryDate").value.trim();

//     if (productName && price && quantity && manufacturedDate && expiryDate) {
//         addRowToTable(productName, price, quantity, manufacturedDate, expiryDate);
//         saveInventory();
//         document.getElementById("inventoryForm").reset();
//     } else {
//         alert("Please fill in all fields before adding an item.");
//     }
// });

//for fetching 

// document.getElementById("addItem").addEventListener("click", function () {
//     const productName = document.getElementById("productName").value.trim();
//     const price = document.getElementById("price").value.trim();
//     const quantity = document.getElementById("quantity").value.trim();
//     const manufacturedDate = document.getElementById("manufacturedDate").value.trim();
//     const expiryDate = document.getElementById("expiryDate").value.trim();

//     if (productName && price && quantity && manufacturedDate && expiryDate) {
//         fetch("inventoryphp.php", {
//             method: "POST",
//             headers: { "Content-Type": "application/x-www-form-urlencoded" },
//             body: `productName=${encodeURIComponent(productName)}&price=${encodeURIComponent(price)}&quantity=${encodeURIComponent(quantity)}&manufacturedDate=${encodeURIComponent(manufacturedDate)}&expiryDate=${encodeURIComponent(expiryDate)}`
//         })
//         .then(response => response.text())
//         .then(data => {
//             alert(data);
//             fetchInventory(); // Refresh table after adding a new record
//         })
//         .catch(error => console.error('Error:', error));
//     } else {
//         alert("Please fill all fields.");
//     }
// });

// function fetchInventory() {
//     fetch("inventory_fetch.php")
//         .then(response => response.json())
//         .then(data => {
//             const tableBody = document.getElementById("inventoryTable");
//             tableBody.innerHTML = "";
//             data.forEach(item => {
//                 const row = `<tr>
//                     <td>${item.productName}</td>
//                     <td>${item.price}</td>
//                     <td>${item.quantity}</td>
//                     <td>${item.manufacturedDate}</td>
//                     <td>${item.expiryDate}</td>
//                 </tr>`;
//                 tableBody.innerHTML += row;
//             });
//         })
//         .catch(error => console.error('Error:', error));
// }

// document.addEventListener("DOMContentLoaded", fetchInventory);


document.addEventListener("DOMContentLoaded", fetchInventory);

// Add new item to the database
document.getElementById("addItem").addEventListener("click", function () {
    const productName = document.getElementById("productName").value.trim();
    const price = document.getElementById("price").value.trim();
    const quantity = document.getElementById("quantity").value.trim();
    const manufacturedDate = document.getElementById("manufacturedDate").value.trim();
    const expiryDate = document.getElementById("expiryDate").value.trim();

    if (productName && price && quantity && manufacturedDate && expiryDate) {
        fetch("inventoryphp.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `productName=${encodeURIComponent(productName)}&price=${encodeURIComponent(price)}&quantity=${encodeURIComponent(quantity)}&manufacturedDate=${encodeURIComponent(manufacturedDate)}&expiryDate=${encodeURIComponent(expiryDate)}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            fetchInventory(); // Refresh table after adding a new record
            document.getElementById("inventoryForm").reset();
        })
        .catch(error => console.error('Error:', error));
    } else {
        alert("Please fill all fields.");
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

    const alertMessage = getExpiryAlert(expiryDate);

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

    // Attach delete event listener
    newRow.querySelector(".delete-item").addEventListener("click", function () {
        deleteItem(productName); // Call the delete function with the product name
    });
}

// Generate expiry alert message
function getExpiryAlert(expiryDate) {
    const today = new Date();
    const expiry = new Date(expiryDate);
    const timeDiff = expiry - today;
    const daysLeft = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));

    if (daysLeft > 0 && daysLeft <= 4) {
        return `<span class="badge bg-warning text-dark">Expiring in ${daysLeft} day(s)</span>`;
    } else if (daysLeft <= 0) {
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
