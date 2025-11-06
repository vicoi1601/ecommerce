<?php
session_start();
include '../includes/db_connect.php';

$user_id = $_SESSION['user_id'];
$fullname = $_SESSION['fullname'];
$total = $_SESSION['total'];
$payment_method = $_POST['payment_method']; // COD or GCash
$ref_number = $_POST['ref_number'] ?? null; // optional, depende kung GCash

// ✅ Check kung may payment method
if (empty($payment_method)) {
  echo "<script>alert('Please select a payment method.'); window.location='checkout.php';</script>";
  exit;
}

// ✅ Kung COD, walang ref_number
if ($payment_method === 'COD') {
  $ref_number = null;
}

// ✅ Insert order
$order_sql = $conn->prepare("
  INSERT INTO orders (user_id, fullname, total, payment_method, ref_number, status)
  VALUES (?, ?, ?, ?, ?, 'Pending')
");
$order_sql->bind_param("isdss", $user_id, $fullname, $total, $payment_method, $ref_number);

if ($order_sql->execute()) {
  $order_id = $conn->insert_id;

  // Insert order items
  foreach ($_SESSION['cart'] as $item) {
    $product_id = $item['id'];
    $price = $item['price'];
    $qty = $item['qty'];
    $conn->query("
      INSERT INTO order_items (order_id, product_id, price, qty)
      VALUES ('$order_id', '$product_id', '$price', '$qty')
    ");
  }

  $_SESSION['cart'] = [];
  echo "<script>alert('✅ Order placed successfully using $payment_method!'); window.location='profile.php';</script>";
} else {
  echo "<script>alert('❌ Error placing order: {$conn->error}'); window.location='cart.php';</script>";
}
?>
