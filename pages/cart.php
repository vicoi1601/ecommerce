<?php
session_start();
include '../includes/db_connect.php';

// ✅ Initialize cart
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// ✅ Add to cart
if (isset($_POST['add_to_cart'])) {
  $product_id = intval($_POST['product_id']);
  $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id = ?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $product = $result->fetch_assoc();

  if ($product) {
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
      if ($item['id'] == $product_id) {
        $item['qty']++;
        $found = true;
        break;
      }
    }

    if (!$found) {
      $_SESSION['cart'][] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'image' => $product['image'],
        'qty' => 1
      ];
    }
  }
  header("Location: cart.php");
  exit;
}

// ✅ Remove from cart
if (isset($_GET['remove'])) {
  $id = intval($_GET['remove']);
  foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['id'] == $id) unset($_SESSION['cart'][$key]);
  }
  header("Location: cart.php");
  exit;
}

// ✅ Update quantity
if (isset($_POST['update_qty'])) {
  $id = intval($_POST['id']);
  $action = $_POST['action'];

  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $id) {
      if ($action === 'increase') $item['qty']++;
      if ($action === 'decrease' && $item['qty'] > 1) $item['qty']--;
      break;
    }
  }
  header("Location: cart.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Cart - GadgetHub</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

  <!-- Header -->
  <header class="bg-white shadow p-4 flex justify-between items-center sticky top-0 z-10">
    <h1 class="text-2xl font-bold text-gray-700">🛒 My Cart</h1>
    <div>
      <a href="shop.php" class="text-blue-600 hover:underline mr-4">← Continue Shopping</a>
      <a href="../auth/logout.php" class="text-orange-600 font-semibold hover:underline">Logout</a>
    </div>
  </header>

  <!-- Main -->
  <main class="flex-grow p-6 max-w-6xl mx-auto">
    <?php if (empty($_SESSION['cart'])): ?>
      <div class="text-center bg-white py-20 rounded-2xl shadow-md">
        <p class="text-gray-500 text-lg">Your cart is empty 😢</p>
        <a href="shop.php" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
          Go Shopping
        </a>
      </div>
    <?php else: ?>

      <!-- Cart Items -->
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <?php $grand_total = 0; ?>
        <?php foreach ($_SESSION['cart'] as $item): 
              $total = $item['price'] * $item['qty'];
              $grand_total += $total;
        ?>
        <div class="bg-white shadow-md rounded-2xl overflow-hidden hover:shadow-lg transition">
          <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>" 
               alt="<?php echo htmlspecialchars($item['name']); ?>" 
               class="w-full h-48 object-cover">
          <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-1"><?php echo htmlspecialchars($item['name']); ?></h3>
            <p class="text-gray-500 mb-2">₱<?php echo number_format($item['price'], 2); ?></p>

            <!-- Quantity Controls -->
            <form action="cart.php" method="post" class="flex items-center gap-2 mb-3">
              <input type="hidden" name="id" value="<?php echo $item['id']; ?>">

              <button type="submit" name="update_qty" value="1" 
                      class="bg-gray-200 px-2 py-1 rounded hover:bg-gray-300"
                      onclick="this.form.action.value='decrease';">
                −
              </button>

              <span class="px-3 py-1 bg-gray-100 rounded text-gray-700 font-medium">
                <?php echo $item['qty']; ?>
              </span>

              <button type="submit" name="update_qty" value="1" 
                      class="bg-gray-200 px-2 py-1 rounded hover:bg-gray-300"
                      onclick="this.form.action.value='increase';">
                +
              </button>

              <input type="hidden" name="action" value="">
            </form>

            <div class="flex justify-between items-center">
              <span class="text-green-600 font-semibold">₱<?php echo number_format($total, 2); ?></span>
              <a href="cart.php?remove=<?php echo $item['id']; ?>" 
                 class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Checkout Summary -->
      <div class="bg-white p-6 shadow-md rounded-2xl max-w-lg ml-auto">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Order Summary</h3>
        <div class="flex justify-between text-gray-700 mb-2">
          <span>Subtotal</span>
          <span>₱<?php echo number_format($grand_total, 2); ?></span>
        </div>
        <div class="flex justify-between text-gray-700 mb-2">
          <span>Shipping Fee</span>
          <span>₱<?php echo number_format($grand_total > 0 ? 150 : 0, 2); ?></span>
        </div>
        <hr class="my-2">
        <div class="flex justify-between font-semibold text-lg text-gray-800">
          <span>Total</span>
          <span class="text-green-700">₱<?php echo number_format($grand_total + 150, 2); ?></span>
        </div>

        <!-- ✅ Store total to send -->
        <input type="hidden" id="orderTotal" value="<?php echo $grand_total + 150; ?>">

        <!-- ✅ Checkout Modal Trigger -->
        <button onclick="openModal()" 
                class="mt-5 w-full bg-green-600 text-white font-semibold py-2 rounded-lg hover:bg-green-700 transition">
          Proceed to Checkout
        </button>
      </div>
    <?php endif; ?>
  </main>

<!-- ✅ Checkout Payment Modal -->
<div id="checkoutModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white w-11/12 max-w-md rounded-2xl shadow-lg p-6 relative">
    <h2 class="text-xl font-semibold text-gray-800 mb-3">Select Payment Method</h2>

    <form id="paymentForm" action="checkout_page.php" method="POST" class="space-y-4">
      <div>
        <label class="flex items-center space-x-2">
          <input type="radio" name="payment_method" value="COD" checked>
          <span>Cash on Delivery</span>
        </label>
        <label class="flex items-center space-x-2 mt-2">
          <input type="radio" name="payment_method" value="GCash">
          <span>GCash</span>
        </label>
      </div>

      <!-- ✅ GCash details -->
      <div id="gcashDetails" class="hidden mt-4 bg-gray-50 p-3 rounded-lg border">
        <p class="text-sm text-gray-700 mb-2 font-medium">Scan this QR or enter your reference number:</p>
        <img src="../assets/images/qr.jpg" alt="GCash QR" class="w-40 mx-auto mb-2 rounded-lg border">
        <input type="text" name="ref_number" id="ref_number" placeholder="Enter Reference Number"
               class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:ring-blue-300">
      </div>

      <div class="flex justify-end gap-3 mt-4">
        <button type="button" onclick="closeModal()"
                class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 text-gray-800 font-medium">
          Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
          Confirm Checkout
        </button>
      </div>
    </form>

    <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-xl">×</button>
  </div>
</div>

<script>
  // ✅ Show/hide GCash details
  const radios = document.querySelectorAll('input[name="payment_method"]');
  const gcashDetails = document.getElementById('gcashDetails');

  radios.forEach(radio => {
    radio.addEventListener('change', () => {
      gcashDetails.classList.toggle('hidden', radio.value !== 'GCash');
    });
  });

  // ✅ Always include ref_number even if empty
  document.getElementById('paymentForm').addEventListener('submit', e => {
    const selected = document.querySelector('input[name="payment_method"]:checked');
    if (!selected) {
      alert('Please select a payment method.');
      e.preventDefault();
    }

    if (selected.value === 'GCash') {
      const ref = document.getElementById('ref_number').value.trim();
      if (!ref) {
        alert('Please enter your GCash reference number.');
        e.preventDefault();
      }
    }
  });

  function openModal() {
    document.getElementById("checkoutModal").classList.remove("hidden");
  }
  function closeModal() {
    document.getElementById("checkoutModal").classList.add("hidden");
  }
</script>


</body>
</html>
