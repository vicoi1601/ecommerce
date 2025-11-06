<?php
session_start();
?>
<nav class="bg-white shadow-md p-4 flex justify-between items-center">
  <a href="../pages/home.php" class="text-xl font-bold text-gray-800">GadgetHub</a>

  <div class="flex items-center space-x-6">
    <a href="../pages/shop.php" class="text-gray-700 hover:text-blue-600">Shop</a>
    <a href="../pages/cart.php" class="text-gray-700 hover:text-blue-600">Cart</a>
    <a href="../pages/profile.php" class="text-gray-700 hover:text-blue-600">Profile</a>

    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="../auth/logout.php" class="bg-orange-500 text-white px-4 py-1 rounded-lg hover:bg-orange-600">Logout</a>
    <?php else: ?>
      <a href="../auth/login.php" class="bg-blue-600 text-white px-4 py-1 rounded-lg hover:bg-blue-700">Login</a>
    <?php endif; ?>

    <!-- 🧪 TEST MODE SWITCH (User → Admin) -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin' && isset($_SESSION['mode']) && $_SESSION['mode'] == 'user'): ?>
      <a href="../admin/index.php?mode=admin" 
         class="bg-gray-800 hover:bg-gray-900 text-white px-3 py-1 rounded-lg shadow-md text-sm transition">
         🔄 Switch to Admin Mode
      </a>
    <?php endif; ?>
  </div>
</nav>
