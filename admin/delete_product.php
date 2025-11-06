<?php
include '../includes/db_connect.php';

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    header("Location: products.php?msg=deleted");
    exit;
  } else {
    echo "❌ Error deleting product.";
  }
} else {
  header("Location: products.php");
  exit;
}
?>
