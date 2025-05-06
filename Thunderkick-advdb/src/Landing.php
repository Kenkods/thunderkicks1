<?php
    $name=["Ken","Hello","Hahaha","Gago","Tanga","ke","ds","kes"];
    include_once(__DIR__ . '/../../backend/config/db.php');
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="./style.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">


  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  
  <style>
    
    body, div, section, main {
      caret-color: transparent;
      outline: none;
    }
    
   
  </style>
</head>
<body class="w-full overflow-x-hidden caret-transparent focus:outline-none"> 
  <header class="cursor-default bg-gray-100">
    <nav class="flex flex-wrap items-center justify-between px-7 py-3">
      <div class="flex items-center space-x-4 w-full md:w-auto">
        <img src="logo.png" alt="logo" class="w-24 md:w-40">
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
          <img src="search.png" alt="search icon" class="h-5 w-5 absolute right-2 top-1.5">
        </div>
        <img src="/Thunderkicks1/Thunderkick-advdb/public/imgs/checkout.png" alt="checkout" class="h-7 hover:cursor-pointer">
        <button type="button" class="font-extrabold text-xl font-mono px-4 py-1 bg-yellow-400 rounded-2xl hover:bg-yellow-500 hover:cursor-pointer" ><a href="/Thunderkicks1/Thunderkick-advdb/src/userAuth/login.php">Login</a></button>
      </div>
    </nav>
  </header>

  <main class="w-full caret-transparent"> 
    <div class="w-full px-5 md:px-20 py-5">
      <div class="relative w-full cursor-default">
        <img src="hero.jpg" alt="hero" class="w-full max-h-[680px] object-cover rounded-lg border-gray-300 border-2">
        <h1 class="font-bold font-serif absolute top-4/7 left-25  text-xl text-gray-600">Style That Starts from the Ground Up.</h1>
        <button type="button"
          class="absolute top-2/3 left-30 transform -translate-y-1/2 font-extrabold text-sm md:text-xl bg-slate-700 font-mono hover:bg-gray-900 text-white py-2 px-4 md:px-6 rounded-3xl hover:cursor-pointer">
          Reserve now
        </button>
      </div>
    </div>

    <div class="w-full flex flex-col md:flex-row justify-between items-center py-5 bg-gray-100 px-5 md:px-20 text-xl">
      <h1 class="font-bold text-3xl md:text-4xl  mb-4 md:mb-0 font-['Montserrat']">New Arrival</h1>
      <div class="flex items-center space-x-4">
        <h3 class="font-medium cursor-default border-b-2">All products</h3>
        <img src="setting.png" alt="setting" class="h-6 hover:cursor-pointer hover:bg-gray-200">
      </div>
    </div>
    


    <div class="w-full  ">
      <div class="w-full flex flex-wrap py-2  px-30">
      <?php for ($i = 0; $i <= 3; $i++): ?>
      <div class=" bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-gray-300 border-2 my-3 mx-2 max-w-xs">
        <img class="w-full h-40 object-cover" src="hero.jpg" alt="Product Image <?= $i ?>">
        <div class="p-4">
          <h2 class="text-xl font-semibold font-mono text-gray-800">Sneaker <?=$name[$i] ?></h2>
          <p class="mt-2 text-gray-600 text-sm">Stylish sneaker number <?= $i ?>, perfect for daily wear.</p>
          <div class="mt-4 flex justify-between items-center">
            <span class="text-yellow-600 font-bold text-lg">$<?= 79 + $i ?>.99</span>
            <button class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white font-bold rounded-xl hover:cursor-pointer">Buy Now</button>
          </div>
        </div>
      </div>
    <?php endfor; ?>

      </div>
      
    </div>

   
  </main>
</body>
</html>
