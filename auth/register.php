<?php
include '../includes/db_connect.php';
session_start();

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        echo "<script>alert('Email already registered');</script>";
    } else {
        $conn->query("INSERT INTO users (name, email, password, role) VALUES ('$name','$email','$password','user')");
        echo "<script>alert('Account created! You can now login'); window.location='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | GadgetHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-semibold text-center mb-6 text-gray-700">Create Account</h1>
        <form method="POST" class="space-y-4">
            <input type="text" name="name" placeholder="Full Name" required class="w-full px-4 py-2 border rounded-xl focus:ring focus:ring-blue-300">
            <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-2 border rounded-xl focus:ring focus:ring-blue-300">
            <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-2 border rounded-xl focus:ring focus:ring-blue-300">
            <button type="submit" name="register" class="w-full bg-blue-600 text-white py-2 rounded-xl hover:bg-blue-700 transition">Sign Up</button>
        </form>
        <p class="text-center text-sm mt-4 text-gray-500">
            Already have an account? <a href="login.php" class="text-blue-
