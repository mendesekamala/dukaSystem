<?php
include 'db_connection.php';

if (!isset($_POST['order_id'])) {
    echo json_encode(['error' => 'Order ID is not provided']);
    exit;
}

$order_id = $_POST['order_id'];

// Fetch order details using order_id
$orderQuery = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
$orderQuery->bind_param("i", $order_id);  // 'i' stands for integer
$orderQuery->execute();
$orderResult = $orderQuery->get_result();

if ($orderResult->num_rows === 0) {
    echo json_encode(['error' => 'Order not found']);
    exit;
}

$order = $orderResult->fetch_assoc();

// Ensure the order number is correctly displayed
if ($order['order_number'] === NULL || empty($order['order_number'])) {
    $order['order_number'] = str_pad($order_id, 8, "0", STR_PAD_LEFT);
}

// Fetch order items using the retrieved order_id
$itemsQuery = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$itemsQuery->bind_param("i", $order_id);  // Bind the order_id to the query
$itemsQuery->execute();
$itemsResult = $itemsQuery->get_result();

$orderItems = [];
while ($item = $itemsResult->fetch_assoc()) {
    $orderItems[] = $item;
}

echo json_encode([
    'order_id' => $order['order_id'],
    'order_number' => $order['order_number'],
    'customer_name' => $order['customer_name'],
    'status' => $order['status'],
    'order_date' => $order['order_date'],
    'items' => $orderItems
]);

$conn->close();
?>
