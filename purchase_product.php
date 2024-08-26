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

$sql = "SELECT * FROM products WHERE product_name = '$product_name'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $new_quantity = $row['quantity'] + $quantity;
    
    // Update quantity, buying_price, and selling_price in a single query
    $sql = "UPDATE products 
            SET quantity = '$new_quantity', 
                buying_price = '$buying_price', 
                selling_price = '$selling_price' 
            WHERE product_name = '$product_name'";

    if ($conn->query($sql) === TRUE) {
        echo "Product updated successfully";
    } else {
        echo "Error updating product: " . $conn->error;
    }
} else {
    $sql = "INSERT INTO products (product_name, product_nickname, product_description, buying_price, selling_price, quantity, expiry_date, date_bought, supplier)
    VALUES ('$product_name', '$product_nickname', '$product_description', '$buying_price', '$selling_price', '$quantity', '$expiry_date', '$date_bought', '$supplier')";
    if ($conn->query($sql) === TRUE) {
        echo "New product created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
