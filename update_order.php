<?php
include 'db_connection.php';

// Retrieve order_id from POST request
$order_id = $_POST['order_id'];
$customer_name = $_POST['customer_name'];
$status = $_POST['status'];
$grand_total = $_POST['grandTotal'];
$order_items = json_decode($_POST['orderItems'], true);

// Update the order status
$updateOrderQuery = $conn->prepare("UPDATE orders SET status = ?, customer_name = ? WHERE order_id = ?");
$updateOrderQuery->bind_param("ssi", $status, $customer_name, $order_id);
$updateOrderQuery->execute();

// Ensure orderItems is being correctly passed and decoded
if (isset($_POST['orderItems'])) {
    $order_items = json_decode($_POST['orderItems'], true);

    foreach ($order_items as $item) {
        // Extract and update each item as before
        $item_id = $item['item_id'];
        $quantity = $item['quantity'];
        $total = $item['total'];
        
        // Update the quantity and total in the order_items table
        $updateItemQuery = $conn->prepare("UPDATE order_items SET quantity = ?, total = ? WHERE item_id = ?");
        $updateItemQuery->bind_param("idi", $quantity, $total, $item_id);
        $updateItemQuery->execute();
        
        // Adjust the product quantity in the products table
        $productName = $item['product_name'];
        $adjustQuantityQuery = $conn->prepare("UPDATE products SET quantity = quantity + (SELECT quantity FROM order_items WHERE item_id = ?) - ? WHERE product_name = ?");
        $adjustQuantityQuery->bind_param("iis", $item_id, $quantity, $productName);
        $adjustQuantityQuery->execute();
    }
} else {
    echo "Order items not received!";
}

echo "Order updated successfully!";
$conn->close();
?>
