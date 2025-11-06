<?php
include 'admin_header.php';
include '../includes/db_connect.php';

// ✅ Handle status update (AJAX)
if (isset($_POST['order_id']) && isset($_POST['status'])) {
  $id = intval($_POST['order_id']);
  $status = $_POST['status'];
  $conn->query("UPDATE orders SET status='$status' WHERE id=$id");
  echo "✅ Status updated to " . ucfirst($status);
  exit;
}

// ✅ Fetch all orders with user name
$orders = $conn->query("
  SELECT o.*, u.name AS user_name 
  FROM orders o
  JOIN users u ON o.user_id = u.id
  ORDER BY o.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Orders | GadgetHub Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
  <div class="flex h-screen">
    <div class="flex-1 flex flex-col">
      
      <!-- Header -->
      <header class="flex justify-between items-center p-4 bg-white shadow">
        <h1 class="text-2xl font-semibold text-gray-700">Manage Orders</h1>
        <div class="text-gray-600">
          👋 Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
        </div>
      </header>

      <!-- Main -->
      <main class="flex-1 p-8 overflow-y-auto">
        <h2 class="text-xl font-bold text-gray-700 mb-6">Order List</h2>

        <div class="bg-white p-6 rounded-2xl shadow-lg overflow-x-auto">
          <table class="min-w-full border border-gray-200 rounded-xl text-sm">
            <thead class="bg-gray-100 text-gray-700">
              <tr>
                <th class="text-left px-4 py-2">Order ID</th>
                <th class="text-left px-4 py-2">Customer</th>
                <th class="text-left px-4 py-2">Reference</th>
                <th class="text-left px-4 py-2">Total</th>
                <th class="text-left px-4 py-2">Payment</th>
                <th class="text-left px-4 py-2">Status</th>
                <th class="text-left px-4 py-2">Date</th>
                <th class="text-center px-4 py-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders->num_rows > 0): ?>
                <?php while ($row = $orders->fetch_assoc()): ?>
                  <?php
                      $statusClass = match(strtolower($row['status'])) {
                      'pending' => 'bg-yellow-100 text-yellow-700',
                      'processing' => 'bg-blue-100 text-blue-700',
                      'shipped' => 'bg-indigo-100 text-indigo-700',
                      'completed' => 'bg-green-100 text-green-700',
                      'cancelled' => 'bg-red-100 text-red-700',
                      default => 'bg-gray-100 text-gray-700'
                    };

                  ?>
                  <tr class="border-t hover:bg-gray-50 transition">
                    <td class="px-4 py-2 font-semibold">#<?php echo $row['id']; ?></td>
                    <td class="px-4 py-2"><?php echo htmlspecialchars($row['user_name']); ?></td>
                    <td class="px-4 py-2 text-gray-600"><?php echo htmlspecialchars($row['ref_number'] ?? '—'); ?></td>
                    <td class="px-4 py-2 text-green-600 font-medium">₱<?php echo number_format($row['total'], 2); ?></td>
                    <td class="px-4 py-2"><?php echo strtoupper($row['payment_method']); ?></td>
                    <td class="px-4 py-2">
                      <select 
                      class="border rounded-lg px-2 py-1 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-400"
                      onchange="updateStatus(<?php echo $row['id']; ?>, this.value)"
                    >
                      <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>🕓 Pending</option>
                      <option value="Processing" <?php if($row['status']=='Processing') echo 'selected'; ?>>🔄 Processing</option>
                      <option value="Shipped" <?php if($row['status']=='Shipped') echo 'selected'; ?>>📦 Shipped</option>
                      <option value="Completed" <?php if($row['status']=='Completed') echo 'selected'; ?>>✅ Completed</option>
                      <option value="Cancelled" <?php if($row['status']=='Cancelled') echo 'selected'; ?>>❌ Cancelled</option>
                    </select>

                    </td>
                    <td class="px-4 py-2 text-gray-500 text-sm">
                      <?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?>
                    </td>
                    <td class="px-4 py-2 text-center space-x-2">
                      <a href="order_details.php?id=<?php echo $row['id']; ?>"
                        class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition">
                        View
                      </a>
                      <a href="delete_order.php?id=<?php echo $row['id']; ?>"
                        onclick="return confirm('Are you sure you want to delete this order?');"
                        class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition">
                        Delete
                      </a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="8" class="text-center text-gray-500 py-6">No orders found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>

 <script>
async function updateStatus(orderId, status) {
  try {
    const formData = new FormData();
    formData.append("order_id", orderId);
    formData.append("status", status);

    await fetch("orders.php", {
      method: "POST",
      body: formData
    });

    // ✅ Tahimik na refresh, walang popup
    location.reload();
  } catch (error) {
    console.error(error);
  }
}
</script>

</body>
</html>
