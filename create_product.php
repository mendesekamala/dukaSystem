<?php
include 'db_connection.php';

$product_name = $_POST['product_name'];
$product_nickname = $_POST['product_nickname'];
$product_description = $_POST['product_description'];
$buying_price = $_POST['buying_price'];
$selling_price = $_POST['selling_price'];
$quantity = $_POST['quantity'];
$expiry_date = $_POST['expiry_date'];
$date_bought = $_POST['date_bought'];
$supplier = $_POST['supplier'];

$sql = "INSERT INTO products (product_name, product_nickname, product_description, buying_price, selling_price, quantity, expiry_date, date_bought, supplier)
VALUES ('$product_name', '$product_nickname', '$product_description', '$buying_price', '$selling_price', '$quantity', '$expiry_date', '$date_bought', '$supplier')";

if ($conn->query($sql) === TRUE) {
    echo "
            <script>
                alert('product created succesfully');
                window.location.href = 'stock.html';
            </script>
        ";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
