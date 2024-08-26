<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duka-system</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="orders/style.css">
    <script src="orders/scripts.js"></script>

    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .td-but {
            display: flex;
            justify-content: space-around; /* Distribute buttons evenly */
            align-items: center; /* Align buttons vertically in the center */
            gap: 5px; /* Optional: adds spacing between buttons */
        }

        .td-but button {
            background-color: transparent; /* Make the button background transparent */
            border: none; /* Remove button border */
            padding: 0; /* Remove button padding */
            cursor: pointer;
        }

        .td-but img {
            width: 20px; /* Adjust icon size if needed */
            height: 20px;
        }
        .orderListing{
            margin: 50px 150px;
        }



        /* Add this in your styles.css */
        .popup-form {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 2px solid #ccc;
            z-index: 1000;
            width: 50%;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .popup-content {
            position: relative;
        }

        .popup-form .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
        }

        .popup-form form div {
            margin-bottom: 15px;
        }

        .popup-form form label {
            display: block;
            margin-bottom: 5px;
        }

        .popup-form form input[type="text"],
        .popup-form form input[type="number"],
        .popup-form form input[type="radio"],
        .popup-form form button {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
        }

        .popup-form form button {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .popup-form form button:hover {
            background-color: #218838;
        }

        .suggestions {
            position: absolute;
            border: 1px solid #ccc;
            background-color: #fff;
            max-height: 200px;
            overflow-y: auto;
            width: 30%;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        .suggestion-item {
            padding: 10px;
            cursor: pointer;
        }
        .suggestion-item:hover {
            background-color: #f0f0f0;
        }

        


    </style>

</head>

<body>
    <div>
        <div class="sidebar">
            <h3 style="text-align:center;">Dashboard</h3>
            <a href="stock.php">Stock</a>
            <a href="#" class="active">Orders</a>
            <a href="transactions.php">Transactions</a>
            <a href="debts.html">Debts</a>
        </div>
    </div>

    <div class="content">
        
        <div id="CreateOrder" class="tabcontent">
            <div class="order-form-container">
                <div class="form-left">
                    
                    <div class="tab">
                        <button class="tablinks active" id="createOrderTab" onclick="openTab(event, 'CreateOrder')">Create New Order</button>
                        <button class="tablinks" id="orderListTab" onclick="openTab(event, 'OrderList')">Order List</button>
                    </div>

                    <div class="order-form">
                        <p style="text-align: center;"> <label for="search">Search by product name:</label> </p>
                        <input type="text" id="search" onkeyup="liveSearch()">
                        <div id="suggestions" class="suggestions"></div>

                        <label for="product_name">Product Name:</label>
                        <input type="text" id="product_name" readonly>

                        <label for="product_nickname">Product Nickname:</label>
                        <input type="text" id="product_nickname" readonly>

                        <label for="expiry_date">Expiry Date:</label>
                        <input type="text" id="expiry_date" readonly>

                        <label for="customer_name">Customer's Name:</label>
                        <input type="text" id="customer_name">

                        <div class="price-quantity">
                            <div>
                                <label for="buying_price">Buying Price:</label>
                                <input type="text" id="buying_price" readonly>
                            </div>
                            <div>
                                <label for="selling_price">Selling Price:</label>
                                <input type="text" id="selling_price">
                            </div>
                            <div>
                                <label for="quantity">Quantity:</label>
                                <input type="number" id="quantity">
                            </div>
                        </div>

                        <button type="button" onclick="addProduct()">Add Product</button>
                    </div>
                </div>

                <div class="form-right">
                    <div class="order-list">
                        <ul id="orderItems"></ul>
                        <div class="total">
                            <span>TOTAL</span>
                            <span id="grandTotal">0</span>
                        </div>
                        <div class="payment">
                            <label for="payment_method">Pay by:</label>
                            <select id="payment_method" onchange="toggleDebtField()">
                                <option value="cash">Cash</option>
                                <option value="debt">Debt</option>
                            </select>
                            <input type="text" id="debt_input" placeholder="Enter debt amount" style="display: none;">
                        </div> 
                        <button type="button" onclick="completeOrder()">Complete Order</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="OrderList" class="tabcontent" style="display:none;">
            
            <div class="orderListing">

                <div class="tab">
                    <button class="tablinks active" id="createOrderTab" onclick="openTab(event, 'CreateOrder')">Create New Order</button>
                    <button class="tablinks" id="orderListTab" onclick="openTab(event, 'OrderList')">Order List</button>
                </div>

                <?php
                    include 'db_connection.php';
                
                    // Assuming you have already connected to the database using $conn
                    $sql = "SELECT order_id, order_number, customer_name, order_date, status, debt_amount FROM orders";
                    $result = $conn->query($sql);

                    if ($result === false) {
                        // Query failed
                        echo "Error: " . $conn->error;
                    } else {
                        if ($result->num_rows > 0) {
                            echo "<table border='1'>
                                    <tr>
                                        <th>Order No</th>
                                        <th>Customer Name</th>
                                        <th>Order Date and Time</th>
                                        <th>Status</th>
                                        <th>Debt Amount</th>
                                        <th>orders action</th>
                                    </tr>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['order_number']}</td>
                                        <td>{$row['customer_name']}</td>
                                        <td>{$row['order_date']}</td>
                                        <td>{$row['status']}</td>
                                        <td>{$row['debt_amount']}</td>
                                        <td class='td-but'>
                                            <button onclick='editOrder({$row['order_id']})'><img src='images/brush.ico' alt='Edit'></button>
                                            <button onclick='deleteOrder({$row['order_id']})'><img src='images/trash.ico' alt='Delete'></button>
                                            <button onclick='viewOrder({$row['order_id']})'><img src='images/view.ico' alt='View'></button>
                                        </td>
                                    </tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "<tr><td colspan='6'>No orders found</td></tr>";
                        }
                    }

                    $conn->close();
                ?>
            </div>
            

        </div>

    </div>

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Default open
        document.getElementById("createOrderTab").click();
    </script>







        <!-- Add this inside the <body> tag of orders.php -->
        <div id="editOrderPopup" class="popup-form" style="display: none;">
            <div class="popup-content">
                <span class="close" onclick="closePopup()">&times;</span>
                <h3>Edit Order</h3>
                <form id="editOrderForm" onsubmit="saveOrderChanges(event)">

                <label for="order_id"></label>    
                <input type="number" name="order_id" id="order_id">

                    <div>
                        <label for="editOrderNumber">Order Number:</label>
                        <input type="text" id="editOrderNumber" name="order_number" readonly>
                    </div>
                    <div>
                        <label for="editCustomerName">Customer Name:</label>
                        <input type="text" id="editCustomerName" name="customer_name" readonly>
                    </div>
                    <div>
                        <label>Status:</label>
                        <label><input type="radio" name="status" value="delivered"> Delivered</label>
                        <label><input type="radio" name="status" value="pending"> Pending</label>
                    </div>
                    <div id="orderItemsContainer">
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Product Name</th>
                                    <th>Q</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="orderItemsTableBody">
                                <!-- Order items will be dynamically added here -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">Grand Total</td>
                                    <td id="grandTotalCell"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <button type="submit" id="submit">save changes</button>
                </form>
            </div>
        </div>

        <script>
            function editOrder(orderId) {
                console.log("Edit Order called with order id:", orderId); // Log the orderId

                // Fetch the order data via AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'get_order.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const orderData = JSON.parse(xhr.responseText);
                        console.log(orderData); // Log the response for debugging

                        if (orderData.error) {
                            console.error(orderData.error);
                            return;
                        }

                        // Populate the form fields with the data
                        document.getElementById('order_id').value = orderData.order_id;  // Set order_id here
                        document.getElementById('editOrderNumber').value = orderData.order_number;
                        document.getElementById('editCustomerName').value = orderData.customer_name;

                        // Set the correct radio button for status
                        if (orderData.status === 'delivered') {
                            document.querySelector('input[name="status"][value="delivered"]').checked = true;
                        } else if (orderData.status === 'pending') {
                            document.querySelector('input[name="status"][value="pending"]').checked = true;
                        }

                        // Populate the order items table
                        const orderItemsTableBody = document.getElementById('orderItemsTableBody');
                        orderItemsTableBody.innerHTML = '';
                        let grandTotal = 0;
                        orderData.items.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td><button type="button" onclick="removeItem(this, ${item.item_id}, ${item.total})">X</button></td>
                                <td>${item.product_name}</td>
                                <td><input type="number" name="quantity" value="${item.quantity}" onchange="updateTotal(this, ${item.price})"></td>
                                <td>${item.total}</td>
                            `;
                            grandTotal += parseFloat(item.total);
                            orderItemsTableBody.appendChild(row);
                        });
                        document.getElementById('grandTotalCell').textContent = grandTotal;

                        // Show the popup form
                        document.getElementById('editOrderPopup').style.display = 'block';
                    }
                };
                xhr.send('order_id=' + orderId);  // Send order_id instead of order_number
            }




            function closePopup() {
                document.getElementById('editOrderPopup').style.display = 'none';
            }

            function removeItem(button, itemId, itemTotal) {
                const row = button.parentElement.parentElement;
                row.remove();
                const grandTotalCell = document.getElementById('grandTotalCell');
                grandTotalCell.textContent = parseFloat(grandTotalCell.textContent) - itemTotal;
                // You can also add an array to keep track of removed items if needed
            }

            function updateTotal(input, price) {
                const row = input.parentElement.parentElement;
                const totalCell = row.querySelector('td:last-child');
                const newTotal = input.value * price;
                totalCell.textContent = newTotal;
                // Recalculate grand total
                let grandTotal = 0;
                document.querySelectorAll('#orderItemsTableBody tr').forEach(row => {
                    grandTotal += parseFloat(row.querySelector('td:last-child').textContent);
                });
                document.getElementById('grandTotalCell').textContent = grandTotal;
            }

            function saveOrderChanges(event) {
                event.preventDefault();
                const form = document.getElementById('editOrderForm');
                const formData = new FormData(form);
                formData.append('grandTotal', document.getElementById('grandTotalCell').textContent);

                // Ensure order_id is included
                const orderId = document.getElementById('order_id').value;
                formData.append('order_id', orderId);

                // Collect order items
                const orderItems = [];
                const orderItemsTableBody = document.getElementById('orderItemsTableBody');
                const rows = orderItemsTableBody.getElementsByTagName('tr');

                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const item = {
                        item_id: parseInt(row.getAttribute('data-item-id')), // Assuming each row has a data-item-id attribute
                        product_name: row.cells[1].textContent,
                        quantity: parseInt(row.cells[2].querySelector('input').value),
                        total: parseFloat(row.cells[3].textContent)
                    };
                    orderItems.push(item);
                }

                formData.append('orderItems', JSON.stringify(orderItems));

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_order.php', true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert('Order updated successfully!');
                        closePopup();
                        // window.location.reload();
                    }
                };
                xhr.send(new URLSearchParams(formData).toString());
            }

        </script>








</body>
</html>
