// for open / close modal filter
const filterModal=document.getElementById('filter-modal');
 filterModal.style.display="none";
document.getElementById("filter-parent").addEventListener('click',()=>{
    
    if(filterModal.style.display=="none"){
    filterModal.style.display="block";
}
    else{
        filterModal.style.display="none";
        
    };

});


//for filter modal script for data-filter
document.querySelectorAll("[data-filter]").forEach(group => {
  group.querySelectorAll("button[data-value]").forEach(button => {
    button.addEventListener("click", () => {
      group.querySelectorAll("button[data-value]").forEach(btn => {
        btn.classList.remove("border-b-2", "border-yellow-500");
      });

     
      button.classList.add("border-b-2", "border-yellow-500");
    });
  });
});

function getFiltered(){
    const filtered={};

    document.querySelectorAll("[data-filter]").forEach(group=>{
        const type=group.dataset.filter;
        const selectedbtn=group.querySelector('button.border-b-2');
        if(selectedbtn){
            filtered[type]=selectedbtn.dataset.value;
        }
    });
    return filtered;
}
document.getElementById('apply-filter').addEventListener("click",()=>{
    const filtered=getFiltered();
    console.log("Sending filters:", filtered);

    fetch("?page=filter-products",{
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(filtered)

    })
    .then(res=>res.json())
    .then(products=>{
        const productList = document.getElementById("productList");
    productList.innerHTML = "";

    if (products.length === 0) {
        productList.innerHTML = "<p>No products found.</p>";
        return;
    }

    products.forEach(product => {
        productList.innerHTML += `
            <div class=" bg-white rounded-xl shadow-md overflow-hidden  hover:drop-shadow-[0px_4px_5px_rgba(77,77,92,0.8)] transition-shadow duration-300 border-1 border-gray-200  my-3 mx-4 max-w-75 min-w-75 hover:cursor-pointer">
                <img class=" h-40 w-full object-cover" src='${product.shoe_img}' alt="Product Image">
                <div class="px-3 py-4">
                <div class=" flex flex-wrap  h-15">
                <h2 class="text-xl font-semibold font-mono text-gray-800 flex flex-wrap w-60">${product.name}</h2>
                </div>
                <p class="mt-2 text-gray-600 text-sm font-semibold relative">Size : ${product.size}</p>
                <p class="mt-2 text-gray-600 text-sm font-semibold relative">Stock : ${product.stock}</p>
                <div class="mt-4 flex justify-between items-center">
                <span class=" font-bold text-lg">$${product.price}</span>
                 <button class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white font-bold rounded-xl hover:cursor-pointer">Buy Now</button>
                </div>
            </div>
            </div>

        `;


   
    
    });
    

    })
    .catch(err => {
    console.error("Fetch failed:", err);
    document.getElementById("productList").innerHTML = "<p class='text-red-500'>Failed to load products.</p>";
  });
    



})



