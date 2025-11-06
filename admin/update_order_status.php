<?php
include '../includes/db_connect.php';

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $conn->real_escape_string($_POST['status']);

    $query = "UPDATE orders SET status='$status' WHERE id=$id";
    if ($conn->query($query)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
