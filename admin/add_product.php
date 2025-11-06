<?php
include '../includes/db_connect.php'; // ✅ Correct DB connection
include 'admin_header.php';

// ✅ Handle form submission before HTML output
if (isset($_POST['add_product'])) {
  $name = trim($_POST['name']);
  $price = floatval($_POST['price']);
  $stock = intval($_POST['stock']);
  $desc = trim($_POST['description']);

  // ✅ Image upload
  $image = '';
  if (!empty($_FILES['image']['name'])) {
    $imageName = time() . '_' . basename($_FILES['image']['name']);
    $target = "../assets/images/" . $imageName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
      $image = $imageName;
    } else {
      echo "<script>alert('⚠️ Image upload failed.');</script>";
    }
  }

  // ✅ Insert into database
  $stmt = $conn->prepare("INSERT INTO products (name, price, stock, description, image) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sdi ss", $name, $price, $stock, $desc, $image);

  if ($stmt->execute()) {
    echo "<script>alert('✅ Product added successfully!'); window.location='products.php';</script>";
    exit;
  } else {
    echo "<script>alert('❌ Error adding product: " . addslashes($stmt->error) . "');</script>";
  }
}
?>

<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gray-100 flex flex-col">
  <header class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-2xl font-semibold text-gray-700">Add New Product</h1>
    <a href="products.php" class="text-blue-600 hover:underline">← Back to Products</a>
  </header>

  <main class="flex-grow flex items-center justify-center py-10">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-2xl border border-gray-200">
      <form action="add_product.php" method="POST" enctype="multipart/form-data" class="space-y-6">
        <div>
          <label class="block text-gray-600 font-medium mb-1">Product Name</label>
          <input type="text" name="name" required
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-gray-600 font-medium mb-1">Price (₱)</label>
            <input type="number" name="price" step="0.01" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
          </div>
          <div>
            <label class="block text-gray-600 font-medium mb-1">Stock</label>
            <input type="number" name="stock" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
          </div>
        </div>

        <div>
          <label class="block text-gray-600 font-medium mb-1">Description</label>
          <textarea name="description" rows="4" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
        </div>

        <div>
          <label class="block text-gray-600 font-medium mb-1">Upload Image</label>
          <input type="file" name="image" required
                 class="w-full text-gray-600 border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <button type="submit" name="add_product"
                class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition">
          + Add Product
        </button>
      </form>
    </div>
  </main>
</div>

<?php include 'footer.php'; ?>
