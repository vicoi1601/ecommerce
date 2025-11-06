<?php
include '../includes/db_connect.php';
include 'admin_header.php'; 

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);


  $conn->query("DELETE FROM order_items WHERE order_id = $id");

 
  $conn->query("DELETE FROM orders WHERE id = $id");
}

header("Location: orders.php");
exit;
?>
