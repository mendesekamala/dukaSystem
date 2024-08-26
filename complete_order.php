<?php
include 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);
$customer_name = $data['customerName'];
$payment_method = $data['paymentMethod'];
$debt_amount = $data['debtAmount'];
$order_items = $data['orderItems'];

// Generate order number
$date_prefix = date('dm'); // Format as 'ddmm'

// Find the last order number for today
$sql = "SELECT order_number FROM orders WHERE order_number LIKE '$date_prefix-%' ORDER BY order_id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $last_order = $result->fetch_assoc();
    $last_number = explode('-', $last_order['order_number'])[1];
    $order_number = $date_prefix . '-' . str_pad($last_number + 1, 2, '0', STR_PAD_LEFT);
} else {
    $order_number = $date_prefix . '-01'; // Start with '01' if no orders exist for today
}

$query = $conn->prepare("INSERT INTO orders (order_number, customer_name, payment_method, debt_amount) VALUES (?, ?, ?, ?)");
$query->bind_param("ssss", $order_number, $customer_name, $payment_method, $debt_amount);
$query->execute();

$order_id = $conn->insert_id;

foreach ($order_items as $item) {
    $query = $conn->prepare("INSERT INTO order_items (order_id, product_name, quantity, price, total) VALUES (?, ?, ?, ?, ?)");
    $query->bind_param("isidd", $order_id, $item['productName'], $item['quantity'], $item['sellingPrice'], $item['total']);
    $query->execute();
}

// Reduce quantity of sold products
foreach ($order_items as $item) {
    $product_name = $item['productName'];
    $ordered_quantity = $item['quantity'];

    $query = $conn->prepare("SELECT quantity FROM products WHERE product_name = ?");
    $query->bind_param("s", $product_name);
    $query->execute();
    $result = $query->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_quantity = $row['quantity'];
        
        $new_quantity = $current_quantity - $ordered_quantity;

        $update_query = $conn->prepare("UPDATE products SET quantity = ? WHERE product_name = ?");
        $update_query->bind_param("is", $new_quantity, $product_name);
        $update_query->execute();
    }
}

// Update cash in the liquidity table
if ($payment_method === 'cash') {
    // Calculate the total amount for the order
    $total_order_amount = array_reduce($order_items, function($carry, $item) {
        return $carry + $item['total'];
    }, 0);

    // Update the cash column in the liquidity table
    $cash_update_query = $conn->prepare("UPDATE liquidity SET cash = cash + ? WHERE id = 1"); // Assuming 'id = 1' for a single entry
    $cash_update_query->bind_param("d", $total_order_amount);
    $cash_update_query->execute();
}

echo json_encode(['message' => 'Order completed successfully', 'order_number' => $order_number]);
?>
