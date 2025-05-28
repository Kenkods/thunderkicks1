// Filter Modal Toggle - Improved with ARIA
const filterModal = document.getElementById('filter-modal');
const filterParent = document.getElementById("filter-parent");

filterParent.addEventListener('click', () => {
  const isHidden = filterModal.style.display === "none";
  filterModal.style.display = isHidden ? "block" : "none";
  filterParent.setAttribute('aria-expanded', isHidden);
});

function setupFilterButtons() {
  document.querySelectorAll("[data-filter]").forEach(group => {
    group.querySelectorAll("button[data-value]").forEach(button => {
      button.addEventListener("click", () => {
        const alreadySelected = button.getAttribute("aria-pressed") === "true";

        if (alreadySelected) {
          // Deselect if already selected
          button.classList.remove("border-b-2", "border-yellow-500");
          button.setAttribute('aria-pressed', 'false');
          return;
        }

        // Deselect others in group
        group.querySelectorAll("button[data-value]").forEach(btn => {
          btn.classList.remove("border-b-2", "border-yellow-500");
          btn.setAttribute('aria-pressed', 'false');
        });

        // Select current button
        button.classList.add("border-b-2", "border-yellow-500");
        button.setAttribute('aria-pressed', 'true');
      });
    });
  });
}

// Get selected filters
function getFiltered() {
  const filtered = {};
  document.querySelectorAll("[data-filter]").forEach(group => {
    const type = group.dataset.filter;
    const selectedBtn = group.querySelector('button[aria-pressed="true"]');
    if (selectedBtn) {
      filtered[type] = selectedBtn.dataset.value;
    }
  });
  return filtered;
}

function renderProducts(products) {
  const productList = document.getElementById("productList");
  productList.innerHTML = "";

  if (!products || products.length === 0) {
    productList.innerHTML = `
      <div class="text-center py-8">
        <p class="text-gray-500">No products found matching your filters.</p>
        <button onclick="resetFilters()" class="mt-4 px-4 py-2 bg-gray-200 rounded-lg">
          Reset Filters
        </button>
      </div>
    `;
    return;
  }

  products.forEach(product => {
    const sizesButtons = product.sizes.map(sizeObj => `
      <button type="button" 
              class="size-btn hover:scale-110 border-2 px-2 hover:cursor-pointer transform transition duration-150 mx-1" 
              data-size="${sizeObj.size}"
              aria-label="Size ${sizeObj.size}">
        ${sizeObj.size}
      </button>
    `).join('');

    productList.innerHTML += `
   
        <div class="card bg-white rounded-xl shadow-md overflow-hidden hover:drop-shadow-[0px_4px_5px_rgba(77,77,92,0.8)] transition-shadow duration-300 border-1 border-gray-200 my-3 mx-4 max-w-75 min-w-75 hover:cursor-pointer">
          <input name="shoe_id" id="" type="hidden" value=<?= htmlspecialchars($card['shoe_id'])?>
        <img class="h-40 w-full object-cover" src="${product.shoe_img}" alt="${product.name}">
          <div class="px-3 py-4">
            <div class="flex flex-wrap h-15">
              <h2 class="text-xl font-semibold font-mono text-gray-800 flex flex-wrap w-60">${product.name}</h2>
            </div>
            <div class="size-buttons">${sizesButtons}</div>
            <div class="mt-4 flex justify-between items-center">
              <span class="font-bold text-lg">$${product.price}</span>
              <button id="addCartBtn" class="addCartBtn px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white font-bold rounded-xl hover:cursor-pointer"  >
                Reserve Now
              </button>
            </div>
          </div>
        </div>
      
    `;
  });
}

document.getElementById('apply-filter').addEventListener("click", () => {
  const applyBtn = document.getElementById('apply-filter');
  const originalText = applyBtn.textContent;
  const productList = document.getElementById("productList");

  applyBtn.disabled = true;
  applyBtn.innerHTML = `<span class="loading-spinner"></span> Applying...`;
  productList.innerHTML = `<div class="text-center py-8"><p>Loading products...</p></div>`;

  const filtered = getFiltered();
  console.log("Sending filters:", filtered);

  fetch("?page=filter-products", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(filtered)
  })
    .then(res => {
      if (!res.ok) throw new Error('Network response was not ok');
      return res.json();
    })
    .then(products => {
      
      renderProducts(products);
      flashMessage();
    })
    .catch(err => {
      console.error("Fetch failed:", err);
      productList.innerHTML = `
        <div class="text-center py-8 text-red-500">
          <p>Failed to load products. Please try again.</p>
          <button onclick="window.location.reload()" class="mt-4 px-4 py-2 bg-gray-200 rounded-lg">
            Reload Page
          </button>
        </div>
      `;
    })
    .finally(() => {
      applyBtn.disabled = false;
      applyBtn.textContent = originalText;
    });
});

document.addEventListener('DOMContentLoaded', () => {
  filterModal.style.display = 'none';
  setupFilterButtons();

  document.addEventListener('click', (e) => {
    if (e.target.classList.contains('size-btn')) {
      const size = e.target.dataset.size;

    }
  });

  document.getElementById('clear-filter').addEventListener('click', () => {
    resetFilters();
  });
});

window.resetFilters = function () {
  document.querySelectorAll('[data-filter] button').forEach(btn => {
    btn.classList.remove("border-b-2", "border-yellow-500");
    btn.setAttribute('aria-pressed', 'false');
  });
  document.getElementById('apply-filter').click();
};



//FlagMessage
function flashMessage(){
document.querySelectorAll(".addCartBtn").forEach(button => {
  button.addEventListener("click", () => {
    const card = button.closest(".card"); // more reliable now
    const shoeID = card.querySelector("input[name='shoe_id']").value;


    fetch("?page=addToCart", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: new URLSearchParams({ shoe_id: shoeID })
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
}
flashMessage();