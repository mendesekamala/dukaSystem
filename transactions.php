<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duka-system</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="transactions/styles.css">
    <script src="transactions/scripts.js"></script>
</head>
<body>
    <div>

        <div class="sidebar">
            <h3 style="text-align:center;">Dashboard</h3>
            
            <a href="stock.php">Stock</a>
            <a href="orders.php">Orders</a>
            <a href="#" class="active">Transactions</a>
            <a href="debts.html">Debts</a>
        </div>  
      
    </div>

    <div class="content">
        <div class="container">
            <div class="tabs">
                <div id="make-transactions" class="tab tablinks" onclick="openTab(event, 'purchase-form')">Make Transaction</div>
                <div id="view-transactions" class="tab tablinks"  onclick="openTab(event, 'view-stock-form')">View Transactions</div>
            </div>
    
            <div id="purchase-form" class="form-container tabcontent">
                <div class="prdct-cont">
                <label for="cash-balance"><strong>Business Cash</strong></label>
                    <input type="text" id="business-cash" readonly>
                        
                    <h3><i>Make a New Transaction</i></h3>
                    <form id="transaction-form" action="process_transaction.php" method="post">
                        <label for="transaction-type">Transaction Type</label>
                        <select name="transaction_type" id="transaction-type" required>
                            <option value="expenses">Expenses</option>
                            <option value="drawings">Drawings</option>
                            <option value="add_capital">Add Capital</option>
                        </select>
                
                        <label for="amount">Amount</label>
                        <input type="number" name="amount" id="amount" required>
                
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="3" required></textarea>
                
                        <button type="submit">Complete</button>
                    </form>
                </div>
            </div>

            <div id="view-stock-form" class="form-container tabcontent">
                <table border="1" class="table-style">
                    <thead>
                        <tr>
                            <th>Transaction Type</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="transactions-table-body">
                        <?php include 'fetch_transactions.php'; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetchCash();
    
            const form = document.getElementById('transaction-form');
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                processTransaction();
            });

            // Default to open the 'Make Transaction' tab
            document.getElementById("make-transactions").click();
        });

        function fetchCash() {
            fetch('get_cash.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('business-cash').value = formatCurrency(data.cash);
                });
        }

        function processTransaction() {
            const formData = new FormData(document.getElementById('transaction-form'));

            fetch('process_transaction.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fetchCash();
                    document.getElementById('transaction-form').reset();
                } else {
                    alert('Error processing transaction.');
                }
            });
        }

        function formatCurrency(amount) {
            return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

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
    </script>
</body>
</html>
