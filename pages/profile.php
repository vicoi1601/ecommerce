<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: ../auth/login.php');
  exit;
}

$user_id = $_SESSION['user_id'];
$user_query = $conn->query("SELECT * FROM users WHERE id = '$user_id'");
$user = $user_query->fetch_assoc();
$order_query = $conn->query("SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile - GadgetHub</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #f3f4f6, #e0e7ff);
      color: #1f2937;
    }
    .card {
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 1rem;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      transition: all 0.2s ease-in-out;
    }
    .card:hover {
      box-shadow: 0 4px 14px rgba(0,0,0,0.08);
      transform: translateY(-2px);
    }
  </style>
</head>
<body class="min-h-screen flex flex-col">

  <!-- HEADER -->
  <header class="bg-white shadow p-5 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">👤 My Profile</h1>
    <div>
      <a href="shop.php" class="text-blue-600 hover:text-blue-800 font-medium mr-5">Shop</a>
      <a href="../auth/logout.php" class="text-orange-600 font-semibold hover:text-orange-700">Logout</a>
    </div>
  </header>

  <!-- MAIN -->
  <main class="flex-grow py-10 px-6 md:px-12 max-w-7xl mx-auto space-y-10">
    
    <!-- 👤 Profile Card -->
    <section class="card p-6 flex flex-col md:flex-row justify-between items-start md:items-center">
      <div>
        <h2 class="text-2xl font-semibold text-gray-900 mb-1">
          Hi, <?= htmlspecialchars($user['name']); ?> 👋
        </h2>
        <p class="text-gray-600">Email: <?= htmlspecialchars($user['email']); ?></p>
        <p class="text-gray-400 text-sm mt-1">
          Member since <?= date('F d, Y', strtotime($user['created_at'])); ?>
        </p>
      </div>
      <div class="mt-4 md:mt-0">
        <a href="shop.php" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition font-medium">
          🛍️ Shop Now
        </a>
      </div>
    </section>

    <!-- 📦 Orders Section -->
    <section class="card p-6">
      <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
        <span class="text-green-600 text-2xl">📦</span> My Orders
      </h2>

      <?php if ($order_query->num_rows > 0): ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          <?php while ($row = $order_query->fetch_assoc()): ?>
            <?php
              $status = strtolower(trim($row['status'])); // ✅ normalize case
              $badgeColor = match($status) {
                'pending' => 'bg-yellow-100 text-yellow-700',
                'processing' => 'bg-blue-100 text-blue-700',
                'completed', 'delivered' => 'bg-green-100 text-green-700',
                'cancelled' => 'bg-red-100 text-red-700',
                default => 'bg-gray-100 text-gray-700'
              };
            ?>
            <div class="p-5 bg-gray-50 rounded-xl border border-gray-200 shadow-sm hover:bg-white hover:shadow-md transition">
              <div class="flex justify-between items-center mb-3">
                <span class="text-sm text-gray-500">Order #<?= $row['id']; ?></span>
                <span class="px-3 py-1 text-xs rounded-full font-medium <?= $badgeColor ?>">
                  <?= ucfirst($status); ?>
                </span>
              </div>

              <p class="text-lg font-semibold text-gray-800 mb-1">₱<?= number_format($row['total'], 2); ?></p>
              <p class="text-sm text-gray-600"><strong>Payment:</strong> <?= htmlspecialchars($row['payment_method']); ?></p>
              <p class="text-sm text-gray-600"><strong>Ref #:</strong> <?= $row['ref_number'] ?: 'N/A'; ?></p>
              <p class="text-xs text-gray-500 mt-2"><?= date('F d, Y h:i A', strtotime($row['date'])); ?></p>

              <a href="order_tracking.php?id=<?= $row['id']; ?>"
                 class="mt-4 inline-block w-full text-center bg-blue-600 text-white text-sm font-medium py-2.5 rounded-lg hover:bg-blue-700 transition">
                 View Details →
              </a>
            </div>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <div class="text-center py-10 text-gray-500">
          <p>No orders found 😢</p>
          <a href="shop.php" class="mt-3 inline-block bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition">
            Shop Now
          </a>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <!-- FOOTER -->
  <footer class="bg-white text-center py-5 text-gray-500 border-t">
    © <?= date("Y"); ?> <span class="font-semibold text-gray-700">GadgetHub</span>. All rights reserved.
  </footer>
</body>
</html>
