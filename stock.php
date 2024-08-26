<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duka-system</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="stock/style.css">
    <script src="stock/scripts.js"></script>

    <style>
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
        .table-style th{
            padding: 10px 20px;
        }
    </style>
    

</head>
<body>
    <div>

        <div class="sidebar">
            <h3 style="text-align:center;">Dashboard</h3>
            
            <a href="#" class="active">Stock</a>
            <a href="orders.php">Orders</a>
            <a href="transactions.php">Transactions</a>
            <a href="debts.html">Debts</a>
        </div>  
      
    </div>

    <div class="content">
        <div class="container">
            <div class="tabs">
                <div id="purchase-tab" class="tab" onclick="showForm('purchase')">Purchase Product</div>
                <div id="view-stock-tab" class="tab" onclick="showForm('view-stock')">View Stock</div>
            </div>
    
            <form method="POST" action="purchase_product.php">
                <div id="purchase-form" class="form-container">
                    <table>
                        <tr>
                            <td colspan="6" class="form-group">
                                <label for="search" style="text-align: center;">Search by product name:</label>
                                <p style="text-align: center;"> <input type="text" id="search" onkeyup="liveSearch()" style="width: 60%;"> </p>
                                <p style="text-align: center;"> <div id="suggestions" class="suggestions"></div> </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="form-group">
                                <label for="product_name">Product Name:</label>
                                <input type="text" id="product_name" name="product_name">
                            </td>
                            <td colspan="2" class="form-group">
                                <label for="product_nickname">Product Nickname:</label>
                                <input type="text" id="product_nickname" name="product_nickname">
                            </td>
                            <td colspan="2" class="form-group">
                                <label for="product_description">Product Description:</label>
                                <input type="text" id="product_description" name="product_description">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="form-group">
                                <label for="buying_price">Buying Price:</label>
                                <input type="text" id="buying_price" name="buying_price">
                            </td>
                            <td colspan="2" class="form-group">
                                <label for="selling_price">Selling Price:</label>
                                <input type="text" id="selling_price" name="selling_price">
                            </td>
                            <td colspan="2" class="form-group">
                                <label for="quantity">Quantity:</label>
                                <input type="text" id="quantity" name="quantity">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="form-group">
                                <label for="expiry_date">Expiry Date:</label>
                                <input type="date" id="expiry_date" name="expiry_date">
                            </td>
                            <td colspan="2" class="form-group">
                                <label for="date_bought">Date Bought:</label>
                                <input type="date" id="date_bought" name="date_bought">
                            </td>
                            <td colspan="2" class="form-group">
                                <label for="supplier">Supplier:</label>
                                <input type="text" id="supplier" name="supplier">
                            </td>
                        </tr>
                    </table>
                    <div class="form-group">
                        <button type="button" onclick="purchaseProduct()">Purchase</button>
                    </div>
                </div>
            </form>


            <div id="view-stock-form" class="form-container">
                <table border="1" class="table-style">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Date Bought</th>
                            <th>Buying Price</th>
                            <th>Selling Price</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody id="stock-table-body">
                        <?php include 'fetch_stock.php'; ?>
                    </tbody>
                </table>
            </div>
    </div>
    <script>
        

        function liveSearch() {
            const searchQuery = document.getElementById('search').value;
            if (searchQuery.length === 0) {
                document.getElementById('suggestions').innerHTML = '';
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'search_product.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const products = JSON.parse(xhr.responseText);
                    let suggestionsHTML = '';
                    products.forEach(product => {
                        suggestionsHTML += `<div class="suggestion-item" onclick="selectProduct(${product.id})">${product.product_name}</div>`;
                    });
                    document.getElementById('suggestions').innerHTML = suggestionsHTML;
                }
            };
            xhr.send('search_query=' + encodeURIComponent(searchQuery));
        }

        function selectProduct(productId) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'get_product.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const product = JSON.parse(xhr.responseText);
                    document.getElementById('product_name').value = product.product_name;
                    document.getElementById('product_nickname').value = product.product_nickname;
                    document.getElementById('product_description').value = product.product_description;
                    document.getElementById('buying_price').value = product.buying_price;
                    document.getElementById('selling_price').value = product.selling_price;
                    document.getElementById('quantity').value = product.quantity;
                    document.getElementById('expiry_date').value = product.expiry_date;
                    document.getElementById('date_bought').value = product.date_bought;
                    document.getElementById('supplier').value = product.supplier;
                    document.getElementById('suggestions').innerHTML = '';
                    document.getElementById('search').value = product.product_name;
                }
            };
            xhr.send('product_id=' + productId);
        }





        function purchaseProduct() {
            const productName = document.getElementById('product_name').value;
            const productNickname = document.getElementById('product_nickname').value;
            const productDescription = document.getElementById('product_description').value;
            const buyingPrice = document.getElementById('buying_price').value;
            const sellingPrice = document.getElementById('selling_price').value;
            const quantity = document.getElementById('quantity').value;
            const expiryDate = document.getElementById('expiry_date').value;
            const dateBought = document.getElementById('date_bought').value;
            const supplier = document.getElementById('supplier').value;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'purchase_product.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText);
                }
            };
            const data = `product_name=${encodeURIComponent(productName)}&product_nickname=${encodeURIComponent(productNickname)}&product_description=${encodeURIComponent(productDescription)}&buying_price=${encodeURIComponent(buyingPrice)}&selling_price=${encodeURIComponent(sellingPrice)}&quantity=${encodeURIComponent(quantity)}&expiry_date=${encodeURIComponent(expiryDate)}&date_bought=${encodeURIComponent(dateBought)}&supplier=${encodeURIComponent(supplier)}`;
            xhr.send(data);
        }





        function showForm(formType) {
            const purchaseTab = document.getElementById('purchase-tab');
            const viewStockTab = document.getElementById('view-stock-tab');
            const purchaseForm = document.getElementById('purchase-form');
            const viewStockForm = document.getElementById('view-stock-form');

            if (formType === 'purchase') {
                purchaseTab.classList.add('active-tab');
                viewStockTab.classList.remove('active-tab');
                purchaseForm.classList.add('active-form');
                viewStockForm.classList.remove('active-form');
            } else {
                viewStockTab.classList.add('active-tab');
                purchaseTab.classList.remove('active-tab');
                viewStockForm.classList.add('active-form');
                purchaseForm.classList.remove('active-form');
            }
        }

        // Default to showing the purchase form on page load
            showForm('purchase');
    </script>
</body>
</html>