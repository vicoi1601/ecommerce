<?php
session_start();
include '../includes/db_connect.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($pass, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_name'] = $user['name'];

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: ../admin/index.php");
            } else {
                header("Location: ../pages/home.php");
            }
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | GadgetHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
    <h1 class="text-2xl font-semibold text-center mb-6 text-gray-700">Login</h1>

    <?php if(isset($error)): ?>
        <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-2 border rounded-xl focus:ring focus:ring-blue-300">
        <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-2 border rounded-xl focus:ring focus:ring-blue-300">
        <button type="submit" name="login" class="w-full bg-blue-600 text-white py-2 rounded-xl hover:bg-blue-700 transition">Login</button>
    </form>

    <p class="text-center text-sm mt-4 text-gray-500">
        Don't have an account? <a href="register.php" class="text-blue-600 hover:underline">Sign Up</a>
    </p>
</div>

</body>
</html>
