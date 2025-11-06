<?php
include 'admin_header.php';
include '../includes/db_connect.php';
// Dashboard stats
$total_products = $conn->query("SELECT COUNT(*) AS cnt FROM products")->fetch_assoc()['cnt'];
$total_orders   = $conn->query("SELECT COUNT(*) AS cnt FROM orders")->fetch_assoc()['cnt'];
$total_users    = $conn->query("SELECT COUNT(*) AS cnt FROM users WHERE role='user'")->fetch_assoc()['cnt'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GadgetHub Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="flex h-screen">

        

        <!-- Main Content -->
        

            <!-- Dashboard -->
            <main class="flex-1 p-8 overflow-y-auto">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-blue-600 text-white p-6 rounded-2xl shadow hover:shadow-lg transition transform hover:-translate-y-1">
                        <h2 class="text-3xl font-bold"><?php echo $total_products; ?></h2>
                        <p class="text-sm opacity-90 mt-1">Total Products</p>
                    </div>

                    <div class="bg-green-600 text-white p-6 rounded-2xl shadow hover:shadow-lg transition transform hover:-translate-y-1">
                        <h2 class="text-3xl font-bold"><?php echo $total_orders; ?></h2>
                        <p class="text-sm opacity-90 mt-1">Total Orders</p>
                    </div>

                    <div class="bg-purple-600 text-white p-6 rounded-2xl shadow hover:shadow-lg transition transform hover:-translate-y-1">
                        <h2 class="text-3xl font-bold"><?php echo $total_users; ?></h2>
                        <p class="text-sm opacity-90 mt-1">Total Users</p>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white p-6 rounded-2xl shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Recent Orders</h2>
                        <a href="orders.php" class="text-blue-600 text-sm hover:underline">View All</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded-xl">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-left px-4 py-2">Order ID</th>
                                    <th class="text-left px-4 py-2">User</th>
                                    <th class="text-left px-4 py-2">Total</th>
                                    <th class="text-left px-4 py-2">Payment</th>
                                    <th class="text-left px-4 py-2">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $orders = $conn->query("
                                    SELECT o.id, u.name AS user_name, o.total, o.payment_method, o.created_at
                                    FROM orders o
                                    JOIN users u ON o.user_id = u.id
                                    ORDER BY o.created_at DESC
                                    LIMIT 5
                                ");
                                while($row = $orders->fetch_assoc()):
                                ?>
                                <tr class="border-t hover:bg-gray-50 transition">
                                    <td class="px-4 py-2 font-semibold">#<?php echo $row['id']; ?></td>
                                    <td class="px-4 py-2"><?php echo $row['user_name']; ?></td>
                                    <td class="px-4 py-2 text-green-600 font-medium">₱<?php echo number_format($row['total'],2); ?></td>
                                    <td class="px-4 py-2"><?php echo strtoupper($row['payment_method']); ?></td>
                                    <td class="px-4 py-2 text-gray-500 text-sm"><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
