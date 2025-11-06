<!-- ✅ UNIVERSAL MODAL TEMPLATE -->
<div id="globalModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white w-11/12 max-w-lg rounded-2xl shadow-xl p-6 relative">
    <h2 id="modalTitle" class="text-lg font-semibold mb-4">Modal Title</h2>
    <div id="modalBody" class="text-gray-700">
      <!-- Dynamic content goes here -->
    </div>

    <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-600">
      ✕
    </button>
  </div>
</div>

<script>
function openModal(title, content) {
  document.getElementById("modalTitle").textContent = title;
  document.getElementById("modalBody").innerHTML = content;
  document.getElementById("globalModal").classList.remove("hidden");
}

function closeModal() {
  document.getElementById("globalModal").classList.add("hidden");
}
</script>

<footer class="bg-white text-center py-4 shadow-inner mt-10">
  <p class="text-gray-500 text-sm">
    © <?php echo date("Y"); ?> GadgetHub. All rights reserved.
  </p>
</footer>

