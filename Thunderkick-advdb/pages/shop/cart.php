<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Shopping Cart</title>
</head>

<body class="w-full overflow-x-hidden caret-transparent focus:outline-none">
    <header class="cursor-default bg-gray-200">
        <nav class="flex flex-wrap items-center justify-between px-7 py-3">
            <div class="flex items-center space-x-4 w-full md:w-auto">
                <a href="/thunderkicks1/thunderkick-advdb/public/index.php?page=landing">
                    <img src="/Thunderkicks1/Thunderkick-advdb/public/imgs/logo.png" alt="logo" class="w-24 md:w-40">
                </a>
                <ul class="flex flex-wrap space-x-2 font-bold font-mono text-xl">
                    <li>
                        <select class="hover:cursor-pointer px-2 hover:border-b-yellow-500 hover:border-b-2 mt-2 ">
                            <option value="Mens">Mens</option>
                            <option value="Womens">Womens</option>
                            <option value="Kids">Kids</option>
                        </select>
                    </li>
                    <li class="hover:cursor-pointer px-2 hover:border-b-yellow-500 hover:border-b-2 mt-2">Products</li>
                    <li class="hover:cursor-pointer px-2 hover:border-b-yellow-500 hover:border-b-2 mt-2">New</li>
                    <li class="hover:cursor-pointer px-2 hover:border-b-yellow-500 hover:border-b-2 mt-2">Brands</li>
                </ul>
            </div>

            <div class="flex items-center justify-end w-full md:w-auto mt-4 md:mt-0 space-x-3">
                <div class="relative w-full max-w-xs">
                    <input type="search" placeholder="Search entire shop here" class="w-full h-8 pr-10 px-3 py-1 outline-none focus:border-yellow-500 focus:border-b-2 text-lg">
                    <img src="/Thunderkicks1/Thunderkick-advdb/public/imgs/search.png" alt="search icon" class="h-5 w-5 absolute right-2 top-1.5">
                </div>
                <img src="/Thunderkicks1/Thunderkick-advdb/public/imgs/checkout.png" alt="checkout" class="h-7 hover:cursor-pointer">
                <?php
                if (isset($_SESSION['user'])):  ?>
                    <form method="POST" action="/thunderkicks1/Thunderkick-advdb/public/logout">
                        <button type="submit" class="font-extrabold text-xl font-mono px-4 py-1 bg-yellow-400 rounded-2xl hover:bg-yellow-500 hover:cursor-pointer">Logout</button>

                    </form>
                <?php else: ?>
                    <button type="button" class="font-extrabold text-xl font-mono px-4 py-1 bg-yellow-400 rounded-2xl hover:bg-yellow-500 hover:cursor-pointer"><a href="/Thunderkicks1/Thunderkick-advdb/public/login">Login</a></button>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <div class="max-w-7xl mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6">Cart (2 items)</h2>

        <!-- CART ITEMS -->
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- LEFT CART ITEMS -->
            <div class="flex-1 space-y-6">

                <!-- SINGLE CART ITEM -->
                <div class="bg-white rounded-lg shadow p-4 flex flex-col md:flex-row gap-4">
                    <!-- Product Image -->
                    <div class="flex justify-center items-center bg-gray-100 p-4 rounded">
                        <img src="/Thunderkicks1/Thunderkick-advdb/public/imgs/img2.png" alt="cart image" class="h-40 object-contain">
                    </div>

                    <!-- Product Details -->
                    <div class="flex-1 space-y-4">
                        <div>
                            <h3 class="text-xl font-semibold">Subzone Shoes</h3>
                            <p class="text-sm text-gray-500">Brand - Adidas</p>
                            <p class="text-sm text-gray-500">COLOR: BLUE</p>
                            <p class="text-sm text-gray-500">SIZE: M</p>
                        </div>


                        <!-- Quantity and Price -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <button onclick="decreaseNumber('qty1','price1')" class="bg-gray-200 px-2 rounded">-</button>
                                <input id="qty1" type="text" value="1" class="w-10 text-center border rounded">
                                <button onclick="increaseNumber('qty1','price1')" class="bg-gray-200 px-2 rounded">+</button>
                            </div>

                            <h4 class="text-lg font-bold">$<span id="price1">3400.00</span></h4>
                        </div>

                        <div class="flex justify-between text-sm text-gray-600">
                            <button class="hover:text-red-500 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg> Remove
                            </button>
                            <button class="hover:text-pink-500 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6.01 4.01 4 6.5 4c1.74 0 3.41 1.01 4.13 2.44C11.09 5.01 12.76 4 14.5 4 16.99 4 19 6.01 19 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                </svg> Move to Wishlist
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4 flex flex-col md:flex-row gap-4">
                    <!-- Product Image -->
                    <div class="flex justify-center items-center bg-gray-100 p-4 rounded">
                        <img src="/Thunderkicks1/Thunderkick-advdb/public/imgs/img2.png" alt="cart image" class="h-40 object-contain">
                    </div>

                    <!-- Product Details -->
                    <div class="flex-1 space-y-4">
                        <div>
                            <h3 class="text-xl font-semibold">Anta KT9</h3>
                            <p class="text-sm text-gray-500">Anta</p>
                            <p class="text-sm text-gray-500">COLOR: BLUE</p>
                            <p class="text-sm text-gray-500">SIZE: M</p>
                        </div>


                        <!-- Quantity and Price -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <button onclick="decreaseNumber('qty1','price1')" class="bg-gray-200 px-2 rounded">-</button>
                                <input id="qty1" type="text" value="1" class="w-10 text-center border rounded">
                                <button onclick="increaseNumber('qty1','price1')" class="bg-gray-200 px-2 rounded">+</button>
                            </div>

                            <h4 class="text-lg font-bold">$<span id="price1">4341.00</span></h4>
                        </div>

                        <div class="flex justify-between text-sm text-gray-600">
                            <button class="hover:text-red-500 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg> Remove
                            </button>
                            <button class="hover:text-pink-500 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6.01 4.01 4 6.5 4c1.74 0 3.41 1.01 4.13 2.44C11.09 5.01 12.76 4 14.5 4 16.99 4 19 6.01 19 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                </svg> Move to Wishlist
                            </button>
                        </div>
                    </div>
                </div>

                <!-- add new item here -->

            </div>

            <!-- RIGHT CART TOTAL -->
            <div class="w-full lg:w-1/3 space-y-4">
                <div class="bg-white rounded-lg shadow p-6 space-y-4">
                    <h3 class="text-lg font-bold">The Total Amount Of</h3>
                    <div class="flex justify-between">
                        <p>Product Amount</p>
                        <p>$<span id="product_total_amt">40.00</span></p>
                    </div>
                    <div class="flex justify-between">
                        <p>Shipping</p>
                        <p>$<span id="shipping_charge">10.00</span></p>
                    </div>
                    <hr />
                    <div class="flex justify-between font-bold text-lg">
                        <p>Total (incl. VAT)</p>
                        <p>$<span id="total_cart_amt">50.00</span></p>
                    </div>
                    <button class="w-full bg-yellow-400 text-white py-2 rounded hover:bg-yellow-500">Checkout</button>
                </div>

                <!-- Discount Code -->
                <div class="bg-white p-4 rounded shadow">
                    <p class="font-semibold mb-2">Add a discount code (optional)</p>
                    <input type="text" class="w-full border px-2 py-1 rounded" placeholder="Enter code" id="discount_code">
                    <button onclick="applyDiscount()" class="mt-2 w-full bg-green-500 text-white py-1 rounded hover:bg-green-600">Apply</button>
                </div>

                <!-- Delivery Info -->
                <div class="bg-white p-4 rounded shadow">
                    <p class="font-semibold mb-2">Expected delivery date</p>
                    <p class="text-sm text-gray-600">May 27th 2025 - May 29th 2025</p>
                </div>
            </div>

        </div>
    </div>
</body>

<script>
    function increaseNumber(qtyId, priceId) {
        let qty = document.getElementById(qtyId);
        let price = document.getElementById(priceId);
        qty.value = parseInt(qty.value) + 1;
        price.innerText = (parseInt(qty.value) * 20).toFixed(2);
    }

    function decreaseNumber(qtyId, priceId) {
        let qty = document.getElementById(qtyId);
        let price = document.getElementById(priceId);
        if (parseInt(qty.value) > 1) {
            qty.value = parseInt(qty.value) - 1;
            price.innerText = (parseInt(qty.value) * 20).toFixed(2);
        }
    }

    function applyDiscount() {
        alert("Apply discount logic here.");
    }
</script>

</html>