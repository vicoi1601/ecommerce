<?php
include 'admin_header.php';
include '../includes/db_connect.php';

// ✅ Handle Add Product (form inside modal)
if (isset($_POST['name'])) {
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);
  $price = floatval($_POST['price']);
  $stock = intval($_POST['stock']);
  $category = trim($_POST['category']);

  // Image upload
  $imageName = '';
  if (!empty($_FILES['image']['name'])) {
    $imageName = time() . '_' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/" . $imageName);
  }

  $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, category, image) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssdi ss", $name, $description, $price, $stock, $category, $imageName);

  if ($stmt->execute()) {
    echo "<script>alert('✅ Product added successfully!'); window.location='products.php';</script>";
  } else {
    echo "<script>alert('❌ Failed to add product.');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products | GadgetHub Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
  <div class="flex h-screen">
    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <header class="flex justify-between items-center p-4 bg-white shadow">
        <h1 class="text-2xl font-semibold text-gray-700">Manage Products</h1>
        <div class="text-gray-600">
          👋 Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
        </div>
      </header>

      <main class="flex-1 p-8 overflow-y-auto">
        <!-- Title & Button -->
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-bold text-gray-700">Product List</h2>
          <button 
            onclick="openModal('Add Product', addProductForm)" 
            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition shadow">
            + Add Product
          </button>
        </div>

        <!-- Table Container -->
        <div class="bg-white p-6 rounded-2xl shadow-lg overflow-x-auto">
          <table class="min-w-full border border-gray-200 rounded-xl text-sm">
            <thead class="bg-gray-100 text-gray-700">
              <tr>
                <th class="text-left px-4 py-2">Image</th>
                <th class="text-left px-4 py-2">Name</th>
                <th class="text-left px-4 py-2">Price</th>
                <th class="text-left px-4 py-2">Stock</th>
                <th class="text-left px-4 py-2">Category</th>
                <th class="text-left px-4 py-2">Description</th>
                <th class="text-center px-4 py-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $products = $conn->query("SELECT * FROM products ORDER BY id DESC");
              if ($products->num_rows > 0) {
                while ($row = $products->fetch_assoc()) {
                  $imagePath = !empty($row['image']) ? "../assets/images/{$row['image']}" : "../assets/images/no-image.png";
                  echo "
                  <tr class='border-t hover:bg-gray-50 transition'>
                    <td class='px-4 py-2'>
                      <img src='{$imagePath}' alt='{$row['name']}' class='w-14 h-14 rounded-lg object-cover border'>
                    </td>
                    <td class='px-4 py-2 font-semibold'>" . htmlspecialchars($row['name']) . "</td>
                    <td class='px-4 py-2 text-green-600 font-medium'>₱" . number_format($row['price'], 2) . "</td>
                    <td class='px-4 py-2 text-center'>" . intval($row['stock']) . "</td>
                    <td class='px-4 py-2 text-gray-600'>" . htmlspecialchars($row['category']) . "</td>
                    <td class='px-4 py-2 text-gray-500 text-sm'>" . htmlspecialchars(substr($row['description'], 0, 50)) . "...</td>
                    <td class='px-4 py-2 text-center space-x-2'>
                      <a href='edit_product.php?id={$row['id']}' class='bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition'>Edit</a>
                      <a href='delete_product.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\");' class='bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition'>Delete</a>
                    </td>
                  </tr>";
                }
              } else {
                echo "<tr><td colspan='7' class='text-center text-gray-500 py-6'>No products found.</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>

  <!-- ✅ Modal -->
  <div id="modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-11/12 md:w-1/3 p-6 relative">
      <button onclick="closeModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
      <h3 id="modalTitle" class="text-xl font-bold text-gray-700 mb-4"></h3>
      <div id="modalContent"></div>
    </div>
  </div>

  <script>
  const addProductForm = `
    <form class='space-y-4' method='POST' enctype='multipart/form-data'>
      <input type='text' name='name' placeholder='Product Name' class='w-full border p-2 rounded' required>
      <textarea name='description' placeholder='Description' class='w-full border p-2 rounded' required></textarea>
      <input type='number' name='price' step='0.01' placeholder='Price' class='w-full border p-2 rounded' required>
      <input type='number' name='stock' placeholder='Stock' class='w-full border p-2 rounded' required>
      <input type='text' name='category' placeholder='Category' class='w-full border p-2 rounded' required>
      <input type='file' name='image' accept='image/*' class='w-full border p-2 rounded' required>
      <button type='submit' class='bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 w-full transition shadow'>
        Save Product
      </button>
    </form>`;

  function openModal(title, content) {
    document.getElementById("modalTitle").innerText = title;
    document.getElementById("modalContent").innerHTML = content;
    document.getElementById("modal").classList.remove("hidden");
  }

  function closeModal() {
    document.getElementById("modal").classList.add("hidden");
  }
  </script>
</body>
</html>
