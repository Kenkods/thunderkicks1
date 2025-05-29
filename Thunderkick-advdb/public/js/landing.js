document.addEventListener('DOMContentLoaded', () => {

  // Handle size button click
  const sizeButtons = document.querySelectorAll('.size-btn');
  sizeButtons.forEach(button => {
    button.addEventListener('click', () => {
      const card = button.closest('.card');

      // Remove active styles from all size buttons in this card only
      card.querySelectorAll('.size-btn').forEach(btn =>
        btn.classList.remove('bg-yellow-500', 'scale-110')
      );

      // Add active style to the clicked one
      button.classList.add('bg-yellow-500', 'scale-110');

      // Save selected size to this card
      const selectedSize = button.getAttribute('data-size');
      card.dataset.selectedSize = selectedSize;

      console.log('Selected size:', selectedSize);
    });
  });

  // Handle cart icon click
  document.getElementById('cart-icon').addEventListener("click", () => {
    window.location.href = "page=cart";
  });
});

// Handle Add to Cart button
document.querySelectorAll(".addCartBtn").forEach(button => {
  button.addEventListener("click", () => {
    const card = button.closest(".card");
    const shoeID = card.querySelector("input[name='shoe_id']").value;
    const selectedSize = card.dataset.selectedSize;

    if (!selectedSize) {
      showFlashMessage("Please select a size first.", "red");
      return;
    }

    console.log("Adding to cart:", shoeID, "Size:", selectedSize);

    fetch("?page=addToCart", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: new URLSearchParams({ shoe_id: shoeID, size: selectedSize })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        showFlashMessage("Added to cart!", "green");
      } else {
        showFlashMessage("Failed to add to cart.", "red");
      }
    })
    .catch(() => {
      showFlashMessage("Something went wrong.", "red");
    });
  });
});

// Flash message helper
function showFlashMessage(message, color) {
  const old = document.getElementById("flash-message");
  if (old) old.remove();

  const flash = document.createElement("div");
  flash.id = "flash-message";
  flash.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded shadow-md border animate-slide-in ${
    color === "green" ? "bg-green-500 border-green-600 text-white" : "bg-red-500 border-red-600 text-white"
  }`;

  flash.innerHTML = `
    <strong class="font-bold">${color === "green" ? "Success!" : "Error!"}</strong>
    <span class="block sm:inline">${message}</span>
    <button onclick="this.parentElement.remove();" class="float-right text-white hover:text-gray-200 font-bold ml-2">&times;</button>
  `;

  document.body.appendChild(flash);
  setTimeout(() => flash.remove(), 3000);
}
