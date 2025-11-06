<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

if (empty($_SESSION['cart'])) {
  echo "<script>alert('Your cart is empty!'); window.location='shop.php';</script>";
  exit;
}

$user_id = $_SESSION['user_id'];
$fullname = $_SESSION['fullname'] ?? 'Unknown';
$total = 0;

foreach ($_SESSION['cart'] as $item) {
  $total += $item['price'] * $item['qty'];
}

$payment_method = $_POST['payment_method'] ?? '';
$ref_number = $_POST['ref_number'] ?? null;

// ✅ Validate
if (empty($payment_method)) {
  echo "<script>alert('Please select a payment method.'); window.location='cart.php';</script>";
  exit;
}

// ✅ For COD, clear ref number
if ($payment_method === 'COD') {
  $ref_number = null;
}

// ✅ Double-check for SQL consistency
$stmt = $conn->prepare("
  INSERT INTO orders (user_id, fullname, total, payment_method, ref_number, status)
  VALUES (?, ?, ?, ?, ?, 'Pending')
");

if (!$stmt) {
  die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("isdss", $user_id, $fullname, $total, $payment_method, $ref_number);

if ($stmt->execute()) {
  $order_id = $conn->insert_id;

  foreach ($_SESSION['cart'] as $item) {
    $product_id = $item['id'];
    $price = $item['price'];
    $qty = $item['qty'];

    $item_stmt = $conn->prepare("
      INSERT INTO order_items (order_id, product_id, price, qty)
      VALUES (?, ?, ?, ?)
    ");
    $item_stmt->bind_param("iidi", $order_id, $product_id, $price, $qty);
    $item_stmt->execute();
  }

  $_SESSION['cart'] = [];

  echo "<script>alert('✅ Order placed successfully via $payment_method!'); window.location='profile.php';</script>";
} else {
  echo "<script>alert('❌ Error inserting order: {$conn->error}'); window.location='cart.php';</script>";
}
?>
