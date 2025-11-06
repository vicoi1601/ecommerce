<?php
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);
  $price = floatval($_POST['price']);
  $stock = intval($_POST['stock']);
  $category = trim($_POST['category']);

  // Handle image upload
  $imageName = '';
  if (!empty($_FILES['image']['name'])) {
    $imageName = time() . '_' . basename($_FILES['image']['name']);
    $target = "../assets/images/" . $imageName;
    move_uploaded_file($_FILES['image']['tmp_name'], $target);
  }

  $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, category, image) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssdiss", $name, $description, $price, $stock, $category, $imageName);


  if ($stmt->execute()) {
    echo "✅ Product added successfully!";
  } else {
    echo "❌ Error saving product.";
  }

  exit;
}
?>
