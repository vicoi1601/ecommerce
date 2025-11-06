<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// --- ADMIN MODE SWITCH LOGIC ---
if (isset($_GET['mode'])) {
    $_SESSION['mode'] = $_GET['mode']; // 'admin' or 'user'
    if ($_GET['mode'] === 'user') {
        header("Location: ../pages/shop.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

if (!isset($_SESSION['mode'])) {
    $_SESSION['mode'] = 'admin';
}

// detect current page
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Admin Layout -->
<div class="flex h-screen bg-gray-100 font-[Poppins]">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg border-r border-gray-200 flex flex-col">
        <div class="p-6 text-2xl font-bold text-blue-600 text-center border-b border-gray-100">
            GadgetHub Admin
        </div>

        <nav class="flex-1 p-4 space-y-2">
            <a href="index.php"
                class="flex items-center gap-3 px-4 py-2 rounded-lg transition 
                <?= $current_page == 'index.php' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700' ?>">
                🏠 <span>Dashboard</span>
            </a>

            <a href="products.php"
                class="flex items-center gap-3 px-4 py-2 rounded-lg transition 
                <?= $current_page == 'products.php' ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-green-100 hover:text-green-700' ?>">
                🛍 <span>Products</span>
            </a>

            <a href="orders.php"
                class="flex items-center gap-3 px-4 py-2 rounded-lg transition 
                <?= $current_page == 'orders.php' ? 'bg-orange-600 text-white' : 'text-gray-700 hover:bg-orange-100 hover:text-orange-700' ?>">
                📦 <span>Orders</span>
            </a>
        </nav>

        <a href="../auth/logout.php"
            class="block p-4 mt-auto bg-orange-500 text-white text-center rounded-lg hover:bg-orange-600 transition mx-4 mb-4">
            🚪 Logout
        </a>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="flex justify-between items-center p-4 bg-white shadow-sm border-b border-gray-200">
            <div>
                <h1 class="text-xl font-semibold text-gray-700">
                    <?= ucfirst(str_replace('.php', '', $current_page)) ?>
                </h1>
                <span class="text-sm text-gray-500 italic">
                    Mode: <?= ucfirst($_SESSION['mode']); ?>
                </span>
            </div>

            <div class="flex items-center space-x-4">
                <span class="text-gray-600">Hello, <?= $_SESSION['user_name']; ?></span>

                <?php if ($_SESSION['mode'] == 'admin'): ?>
                    <a href="?mode=user"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                        Switch to User Mode
                    </a>
                <?php endif; ?>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6 overflow-y-auto bg-gray-50">
