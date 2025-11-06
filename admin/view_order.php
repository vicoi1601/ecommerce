<?php
include 'admin_header.php';
include '../includes/db_connect.php';

if (!isset($_GET['id'])) {
  die("<div class='text-center text-red-600 font-semibold mt-20'>Invalid Order ID</div>");
}

$id = intval($_GET['id']);
$order = $conn->query("SELECT * FROM orders WHERE id = $id")->fetch_assoc();

if (!$order) {
  die("<div class='text-center text-red-600 font-semibold mt-20'>Order not found.</div>");
}

// ✅ Fetch order items + product details
$items = $conn->query("
  SELECT 
    oi.*, 
    p.name AS product_name, 
    p.image AS product_image, 
    p.description AS product_description
  FROM order_items oi
  LEFT JOIN products p ON oi.product_id = p.id
  WHERE oi.order_id = $id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order #<?php echo $id; ?> | GadgetHub Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function showProductModal(name, image, description, price) {
      document.getElementById('modal-name').textContent = name;
      document.getElementById('modal-image').src = "../assets/images/" + image;
      document.getElementById('modal-desc').textContent = description;
      document.getElementById('modal-price').textContent = "₱" + parseFloat(price).toLocaleString();
      document.getElementById('productModal').classList.remove('hidden');
    }
    function closeModal() {
      document.getElementById('productModal').classList.add('hidden');
    }
  </script>
</head>
<body class="bg-gray-100">
  <div class="min-h-screen flex flex-col">

    <header class="bg-white shadow p-4 flex justify-between items-center">
      <h1 class="text-2xl font-semibold text-gray-700">Order Details</h1>
      <a href="orders.php" class="text-blue-600 hover:underline">← Back to Orders</a>
    </header>

    <main class="flex-grow p-8 max-w-5xl mx-auto">
      <!-- Order Summary -->
      <div class="bg-white p-6 rounded-2xl shadow mb-8 border border-gray-200">
        <h2 class="text-xl font-bold text-gray-700 mb-4">Order #<?php echo $order['id']; ?></h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <p><span class="font-semibold text-gray-700">Customer:</span> <?php echo htmlspecialchars($order['user']); ?></p>
            <p><span class="font-semibold text-gray-700">Payment Method:</span> <?php echo htmlspecialchars($order['payment']); ?></p>
          </div>
          <div>
            <p><span class="font-semibold text-gray-700">Status:</span> 
              <span class="px-3 py-1 text-sm rounded-full 
                <?php echo $order['status'] == 'Completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; ?>">
                <?php echo htmlspecialchars($order['status']); ?>
              </span>
            </p>
            <p><span class="font-semibold text-gray-700">Date:</span> <?php echo htmlspecialchars($order['created_at']); ?></p>
          </div>
        </div>
      </div>

      <!-- ✅ Order Progress Tracker -->
      <div class="bg-white p-6 rounded-2xl shadow mb-8 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Progress</h3>
        <?php
          $status = $order['status'];
          $steps = ["Pending", "Processing", "Shipped", "Completed"];
          $currentStep = array_search($status, $steps);
        ?>
        <div class="flex items-center justify-between relative">
          <?php foreach ($steps as $index => $step): 
            $active = $index <= $currentStep;
          ?>
            <div class="flex flex-col items-center w-1/4 relative z-10">
              <div class="w-10 h-10 flex items-center justify-center rounded-full border-2 
                <?php echo $active ? 'bg-blue-600 border-blue-600 text-white' : 'bg-gray-200 border-gray-300 text-gray-500'; ?>">
                <?php echo $index + 1; ?>
              </div>
              <span class="mt-2 text-sm font-medium <?php echo $active ? 'text-blue-600' : 'text-gray-400'; ?>">
                <?php echo $step; ?>
              </span>
            </div>
            <?php if ($index < count($steps) - 1): ?>
              <div class="flex-1 h-1 <?php echo $index < $currentStep ? 'bg-blue-600' : 'bg-gray-300'; ?>"></div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Order Items -->
      <div class="bg-white p-6 rounded-2xl shadow border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Items in this Order</h3>
        <?php if ($items && $items->num_rows > 0): ?>
          <table class="min-w-full text-sm text-left text-gray-600 border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-50 text-gray-700 font-semibold uppercase">
              <tr>
                <th class="px-6 py-3">Product</th>
                <th class="px-6 py-3">Price</th>
                <th class="px-6 py-3">Qty</th>
                <th class="px-6 py-3 text-right">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $total = 0;
              while ($item = $items->fetch_assoc()):
                $subtotal = $item['price'] * $item['qty'];
                $total += $subtotal;
              ?>
              <tr class="border-b hover:bg-gray-50 transition">
                <td class="px-6 py-3 flex items-center space-x-3">
                  <img src="../assets/images/<?php echo htmlspecialchars($item['product_image']); ?>" 
                       alt="Product Image" class="w-12 h-12 rounded-md border object-cover">
                  <button 
                    onclick="showProductModal('<?php echo htmlspecialchars($item['product_name']); ?>', '<?php echo htmlspecialchars($item['product_image']); ?>', '<?php echo htmlspecialchars($item['product_description']); ?>', '<?php echo $item['price']; ?>')" 
                    class="font-medium text-blue-600 hover:underline">
                    <?php echo htmlspecialchars($item['product_name']); ?>
                  </button>
                </td>
                <td class="px-6 py-3">₱<?php echo number_format($item['price'], 2); ?></td>
                <td class="px-6 py-3"><?php echo $item['qty']; ?></td>
                <td class="px-6 py-3 text-right font-semibold text-green-600">₱<?php echo number_format($subtotal, 2); ?></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>

          <div class="text-right mt-6">
            <p class="text-xl font-semibold text-gray-700">Grand Total: ₱<?php echo number_format($total, 2); ?></p>
          </div>
        <?php else: ?>
          <p class="text-gray-500 text-center py-6">No items found for this order.</p>
        <?php endif; ?>
      </div>
    </main>
  </div>

  <!-- 🪄 Product Preview Modal -->
  <div id="productModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 max-w-md w-full relative">
      <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-xl">&times;</button>
      <img id="modal-image" src="" alt="" class="w-40 h-40 mx-auto rounded-lg object-cover mb-4 border">
      <h2 id="modal-name" class="text-lg font-semibold text-gray-800 text-center mb-2"></h2>
      <p id="modal-desc" class="text-gray-600 text-sm mb-3 text-center"></p>
      <p id="modal-price" class="text-green-600 font-bold text-center text-lg"></p>
    </div>
  </div>
</body>
</html>
