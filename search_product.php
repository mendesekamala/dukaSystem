<?php
include 'db_connection.php';

$search_query = $_POST['search_query'];
$sql = "SELECT id, product_name FROM products WHERE product_name LIKE '%$search_query%'";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode($products);

$conn->close();
?>

