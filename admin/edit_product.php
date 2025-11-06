<?php
include '../includes/db_connect.php';

if (!isset($_GET['id'])) {
  header("Location: products.php");
  exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
  echo "Product not found!";
  exit;
}

// ✅ Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $description = $_POST['description'];
  $category = $_POST['category'];

  $stmt = $conn->prepare("UPDATE products SET name=?, price=?, description=?, category=? WHERE id=?");
  $stmt->bind_param("sdssi", $name, $price, $description, $category, $id);

  if ($stmt->execute()) {
    header("Location: products.php?msg=updated");
    exit;
  } else {
    echo "❌ Update failed.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product | GadgetHub</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
  <div class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Product</h2>
    <form method="POST">
      <label class="block mb-2 text-sm font-medium">Product Name</label>
      <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" 
             class="w-full border px-3 py-2 rounded mb-4">

      <label class="block mb-2 text-sm font-medium">Price</label>
      <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" 
             class="w-full border px-3 py-2 rounded mb-4">

      <label class="block mb-2 text-sm font-medium">Description</label>
      <textarea name="description" rows="3" 
                class="w-full border px-3 py-2 rounded mb-4"><?php echo htmlspecialchars($product['description']); ?></textarea>

      <label class="block mb-2 text-sm font-medium">Category</label>
      <input type="text" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" 
             class="w-full border px-3 py-2 rounded mb-4">

      <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
        Update Product
      </button>
    </form>
  </div>
</body>
</html>
