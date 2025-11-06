<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>GadgetHub | Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

  <!-- Hero Section -->
  <section class="text-center py-16 bg-gradient-to-r from-gray-100 via-white to-gray-100">
    <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome to <span class="text-blue-600">GadgetHub</span></h1>
    <p class="text-gray-600 text-lg mb-6">Explore the latest and smartest gadgets designed for you.</p>
    <a href="shop.php" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition">Shop Now</a>
  </section>

  <!-- Featured Products -->
  <section class="px-8 py-12">
    <h2 class="text-2xl font-semibold text-gray-800 mb-8 text-center">Featured Gadgets</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
      <!-- Product 1 -->
      <div class="bg-white rounded-2xl shadow hover:shadow-xl transition p-4">
        <img src="../assets/images/phone.jpg" alt="Smartphone" class="rounded-xl mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Smartphone X10</h3>
        <p class="text-gray-500 text-sm mb-2">128GB | Dual Camera | Fast Charging</p>
        <p class="text-blue-600 font-bold mb-3">₱34,990</p>
        <button class="w-full bg-blue-600 text-white py-2 rounded-xl hover:bg-blue-700 transition">Add to Cart</button>
      </div>

      <!-- Product 2 -->
      <div class="bg-white rounded-2xl shadow hover:shadow-xl transition p-4">
        <img src="../assets/images/laptop.jpg" alt="Laptop" class="rounded-xl mb-4">
        <h3 class="text-lg font-semibold text-gray-800">UltraBook Pro</h3>
        <p class="text-gray-500 text-sm mb-2">16GB RAM | 512GB SSD | 14-inch</p>
        <p class="text-blue-600 font-bold mb-3">₱64,990</p>
        <button class="w-full bg-blue-600 text-white py-2 rounded-xl hover:bg-blue-700 transition">Add to Cart</button>
      </div>

      <!-- Product 3 -->
      <div class="bg-white rounded-2xl shadow hover:shadow-xl transition p-4">
        <img src="../assets/images/smartwatch.jpg" alt="Smartwatch" class="rounded-xl mb-4">
        <h3 class="text-lg font-semibold text-gray-800">SmartWatch Z</h3>
        <p class="text-gray-500 text-sm mb-2">Waterproof | 7-Day Battery | Fitness Tracker</p>
        <p class="text-blue-600 font-bold mb-3">₱4,990</p>
        <button class="w-full bg-blue-600 text-white py-2 rounded-xl hover:bg-blue-700 transition">Add to Cart</button>
      </div>

      <!-- Product 4 -->
      <div class="bg-white rounded-2xl shadow hover:shadow-xl transition p-4">
        <img src="../assets/images/headphones.jpg" alt="Headphones" class="rounded-xl mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Noise Cancelling Headphones</h3>
        <p class="text-gray-500 text-sm mb-2">Bluetooth | Deep Bass | 30h Battery</p>
        <p class="text-blue-600 font-bold mb-3">₱3,490</p>
        <button class="w-full bg-blue-600 text-white py-2 rounded-xl hover:bg-blue-700 transition">Add to Cart</button>
      </div>
    </div>
  </section>

  <?php include '../includes/footer.php'; ?>
</body>
</html>
