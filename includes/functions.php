<?php
function formatPrice($amount) {
  return '₱' . number_format($amount, 2);
}

function getStatusBadge($status) {
  $status = strtolower($status);
  switch ($status) {
    case 'completed':
      return "<span class='bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium'>Completed</span>";
    case 'processing':
      return "<span class='bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium'>Processing</span>";
    case 'pending':
      return "<span class='bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium'>Pending</span>";
    case 'cancelled':
      return "<span class='bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium'>Cancelled</span>";
    default:
      return "<span class='bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium'>$status</span>";
  }
}
?>
