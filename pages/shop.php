<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Search feature
$search = "";
if (isset($_GET['search'])) {
  $search = $_GET['search'];
  $sql = "SELECT * FROM products WHERE name LIKE '%$search%' OR category LIKE '%$search%'";
} else {
  $sql = "SELECT * FROM products";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop | GadgetHub</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

  <!-- Search Bar -->
  <div class="bg-white p-4 shadow-md flex justify-center">
    <form method="GET" class="flex w-full max-w-lg">
      <input type="text" name="search" placeholder="Search for gadgets..."
        value="<?php echo htmlspecialchars($search); ?>"
        class="w-full px-4 py-2 border rounded-l-xl focus:ring focus:ring-blue-300">
      <button type="submit" class="bg-blue-600 text-white px-6 rounded-r-xl hover:bg-blue-700">Search</button>
    </form>
  </div>

  <!-- Product Grid -->
  <section class="px-8 py-12">
    <h2 class="text-2xl font-semibold text-gray-800 mb-8 text-center">Shop All Gadgets</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
      <?php
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          
          echo '
          <div class="bg-white rounded-2xl shadow hover:shadow-xl transition p-4">
            <img src="../assets/images/' . $row['image'] . '" alt="' . $row['name'] . '" class="rounded-xl mb-4 h-48 w-full object-cover">
            <h3 class="text-lg font-semibold text-gray-800">' . $row['name'] . '</h3>
            <p class="text-gray-500 text-sm mb-2">' . $row['description'] . '</p>
            <p class="text-blue-600 font-bold mb-3">₱' . number_format($row['price'], 2) . '</p>
      <form method="POST" action="cart.php">
  <input type="hidden" name="product_id" value="' . $row['id'] . '">
  <input type="hidden" name="name" value="' . $row['name'] . '">
  <input type="hidden" name="price" value="' . $row['price'] . '">
  <input type="hidden" name="image" value="' . $row['image'] . '">
  <button type="submit" name="add_to_cart" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
    Add to Cart
  </button>
</form>



          </div>';
        }
      } else {
        echo "<p class='text-center text-gray-500'>No products found.</p>";
      }
      ?>
    </div>
  </section>

  <?php include '../includes/footer.php'; ?>
</body>
</html>
