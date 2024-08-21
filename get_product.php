<?php
include 'db_connection.php';

$product_id = $_POST['product_id'];
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    echo json_encode($product);
} else {
    echo json_encode([]);
}

$conn->close();
?>
