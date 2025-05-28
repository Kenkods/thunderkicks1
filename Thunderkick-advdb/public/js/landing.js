document.getElementById("addCartBtn").addEventListener("click",() =>{
  document.getElementById("addCartBtn").addEventListener("click", () => {
    const shoeID = document.getElementById("shoe-id").value;

    fetch("?page=addToCart", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: new URLSearchParams({ shoe_id: shoeID })
    })
    .then(res => res.json())
    .then(data => {
      showFlashMessage(data.message || "Item added to cart!", "green");
    })
    .catch(() => {
      showFlashMessage("Something went wrong.", "red");
    });
  });

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
  
}); 



document.addEventListener('DOMContentLoaded', () => {


  const sizeButtons = document.querySelectorAll('.size-btn');

  sizeButtons.forEach(button => {
    button.addEventListener('click', () => {
      // If already active, remove active styles
      if (button.classList.contains('bg-yellow-500')) {
        button.classList.remove('bg-yellow-500', 'scale-110');
        return;
      }

      // Remove active styles from all buttons
      sizeButtons.forEach(btn =>
        btn.classList.remove('bg-yellow-500', 'scale-110')
      );

      // Add active styles to clicked button
      button.classList.add('bg-yellow-500', 'scale-110');
    });
  });


  

});


