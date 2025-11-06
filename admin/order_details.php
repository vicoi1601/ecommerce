<?php
include 'admin_header.php';
include '../includes/db_connect.php';

if (!isset($_GET['id'])) {
  header("Location: orders.php");
  exit;
}

$order_id = intval($_GET['id']);

// ✅ Get order info + user
$order = $conn->query("
  SELECT o.*, u.name AS user_name, u.email 
  FROM orders o
  JOIN users u ON o.user_id = u.id
  WHERE o.id = $order_id
")->fetch_assoc();

if (!$order) {
  echo "<div class='p-10 text-center text-red-600 text-lg font-semibold'>Order not found.</div>";
  exit;
}

// ✅ Get order items
$items = $conn->query("
  SELECT oi.*, p.name AS product_name, p.image
  FROM order_items oi
  JOIN products p ON oi.product_id = p.id
  WHERE oi.order_id = $order_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Details | GadgetHub Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="flex h-screen">
  <div class="flex-1 flex flex-col">

    <!-- Header -->
    <header class="flex justify-between items-center p-4 bg-white shadow">
      <h1 class="text-2xl font-semibold text-gray-700">
        Order #<?php echo $order['id']; ?> Details
      </h1>
      <a href="orders.php" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800 transition">
        ← Back to Orders
      </a>
    </header>

    <main class="flex-1 p-8 overflow-y-auto space-y-8">

      <!-- Order Info -->
      <div class="bg-white p-6 rounded-2xl shadow">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Order Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
          <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['user_name']); ?></p>
          <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
          <p><strong>Reference:</strong> <?php echo htmlspecialchars($order['ref_number']); ?></p>
          <p><strong>Status:</strong> 
            <span class="px-3 py-1 rounded-full text-sm 
              <?php
                echo match(strtolower($order['status'])) {
                  'pending' => 'bg-yellow-100 text-yellow-700',
                  'processing' => 'bg-blue-100 text-blue-700',
                  'completed' => 'bg-green-100 text-green-700',
                  'cancelled' => 'bg-red-100 text-red-700',
                  default => 'bg-gray-100 text-gray-700'
                };
              ?>">
              <?php echo ucfirst($order['status']); ?>
            </span>
          </p>
          <p><strong>Payment Method:</strong> <?php echo strtoupper($order['payment_method']); ?></p>
          <p><strong>Date Ordered:</strong> <?php echo date('M d, Y h:i A', strtotime($order['created_at'])); ?></p>
          <p><strong>Total Amount:</strong> ₱<?php echo number_format($order['total'], 2); ?></p>
        </div>
      </div>

      <!-- Order Items -->
      <div class="bg-white p-6 rounded-2xl shadow">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Order Items</h2>
        <table class="min-w-full border border-gray-200 rounded-xl text-sm">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="text-left px-4 py-2">Product</th>
              <th class="text-left px-4 py-2">Price</th>
              <th class="text-left px-4 py-2">Quantity</th>
              <th class="text-left px-4 py-2">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php while($item = $items->fetch_assoc()): ?>
              <tr class="border-t hover:bg-gray-50 transition">
                <td class="flex items-center space-x-3 px-4 py-2">
                  <img src="../assets/images/<?php echo $item['image'] ?: 'no-image.png'; ?>" 
                       class="w-12 h-12 rounded-lg border object-cover">
                  <span><?php echo htmlspecialchars($item['product_name']); ?></span>
                </td>
                <td class="px-4 py-2">₱<?php echo number_format($item['price'], 2); ?></td>
                <td class="px-4 py-2"><?php echo $item['qty']; ?></td>
                <td class="px-4 py-2 text-green-600 font-medium">
                  ₱<?php echo number_format($item['price'] * $item['qty'], 2); ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

</body>
</html>
