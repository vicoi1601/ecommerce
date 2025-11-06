<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

<style>
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #f9fafb;
  }
</style>

<?php
include '../includes/db_connect.php';

$order_id = $_GET['id'] ?? 0;
$order = $conn->query("SELECT * FROM orders WHERE id = '$order_id'")->fetch_assoc();

if (!$order) {
  echo "<p class='text-red-500 text-center mt-10'>❌ Order not found.</p>";
  exit;
}

$items = $conn->query("
  SELECT oi.*, p.name, p.image 
  FROM order_items oi
  LEFT JOIN products p ON oi.product_id = p.id
  WHERE oi.order_id = '$order_id'
");
?>

<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-2xl p-8 mt-10 border border-gray-100">
  <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
    <span>📦 Order Details</span>
  </h2>

  <div class="grid md:grid-cols-2 gap-6 mb-6">
    <div>
      <p><strong>Order ID:</strong> #<?= $order['id'] ?></p>
      <p><strong>Date:</strong> <?= date('F d, Y h:i A', strtotime($order['date'])) ?></p>
      <p><strong>Status:</strong> 
        <span class="px-3 py-1 text-sm rounded-full
          <?= $order['status'] == 'Completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
          <?= htmlspecialchars($order['status']) ?>
        </span>
      </p>
    </div>
    <div>
      <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
      <p><strong>Reference No.:</strong> <?= $order['ref_number'] ?: 'N/A' ?></p>
      <p><strong>Total:</strong> 
        <span class="text-green-600 font-semibold">₱<?= number_format($order['total'], 2) ?></span>
      </p>
    </div>
  </div>

  <hr class="my-6 border-gray-200">

  <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
    🛍 Ordered Items
  </h3>

  <div class="space-y-4">
    <?php while ($item = $items->fetch_assoc()): ?>
      <div class="flex items-center gap-5 bg-gray-50 hover:bg-gray-100 transition rounded-xl p-4 border border-gray-200 shadow-sm">
        <img src="../assets/images/<?= htmlspecialchars($item['image']) ?>" 
             alt="<?= htmlspecialchars($item['name']) ?>" 
             class="w-20 h-20 rounded-lg object-cover border">
        <div class="flex-1">
          <p class="font-medium text-gray-800"><?= htmlspecialchars($item['name']) ?></p>
          <p class="text-sm text-gray-500">
            ₱<?= number_format($item['price'], 2) ?> × <?= $item['qty'] ?>
          </p>
        </div>
        <span class="font-semibold text-green-600">
          ₱<?= number_format($item['price'] * $item['qty'], 2) ?>
        </span>
      </div>
    <?php endwhile; ?>
  </div>

  <div class="text-right mt-8 text-lg font-semibold text-gray-800">
    Grand Total: <span class="text-green-600">₱<?= number_format($order['total'], 2) ?></span>
  </div>

  <div class="text-center mt-8">
    <a href="profile.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl transition shadow-md">
      ← Back to My Orders
    </a>
  </div>
</div>
