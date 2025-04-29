<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="./style.css" rel="stylesheet">
  <style>
    
    body, div, section, main {
      caret-color: transparent;
      outline: none;
    }
  </style>
</head>
<body class="w-full overflow-x-hidden caret-transparent focus:outline-none"> 
  <header class="cursor-default bg-gray-100">
    <nav class="flex flex-wrap items-center justify-between px-7 py-2">
      <div class="flex items-center space-x-4 w-full md:w-auto">
        <img src="logo.png" alt="logo" class="w-24 md:w-30">
        <ul class="flex flex-wrap space-x-2 font-bold font-mono">
          <li>
            <select class="hover:cursor-pointer px-2 hover:border-b-yellow-500 hover:border-b-2 mt-2">
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
          <input type="search" placeholder="Search entire shop here" class="w-full h-8 pr-10 px-2 py-1 outline-none focus:border-yellow-500 focus:border-b-2">
          <img src="search.png" alt="search icon" class="h-5 w-5 absolute right-2 top-1.5">
        </div>
        <img src="/public/imgs/checkout.png" alt="checkout" class="h-7 hover:cursor-pointer">
        <button type="button" class="font-black font-mono px-4 py-1 bg-yellow-400 rounded-2xl hover:bg-yellow-500 hover:cursor-pointer">Login</button>
      </div>
    </nav>
  </header>

  <main class="w-full caret-transparent"> 
    <div class="w-full px-5 md:px-20 py-10">
      <div class="relative w-full cursor-default">
        <img src="hero.jpg" alt="hero" class="w-full max-h-[520px] object-cover rounded-lg border-gray-300 border-2">
        <button type="button"
          class="absolute top-2/3 left-15 transform -translate-y-1/2 font-extrabold text-sm md:text-xl bg-slate-800 font-mono hover:bg-gray-900 text-white py-2 px-4 md:px-6 rounded-3xl hover:cursor-pointer">
          Reserve now
        </button>
      </div>
    </div>

    <div class="w-full flex flex-col md:flex-row justify-between items-center py-5 bg-gray-100 px-5 md:px-20">
      <h1 class="font-bold text-3xl md:text-4xl font-mono mb-4 md:mb-0">New Arrival</h1>
      <div class="flex items-center space-x-4">
        <h3 class="font-medium cursor-default border-b-2">All products</h3>
        <img src="setting.png" alt="setting" class="h-6 hover:cursor-pointer hover:bg-gray-200">
      </div>
    </div>
    <div class="w-full">
      <div class="w-full flex space-x-4 px-10 py-3 ">
      <?php for ($i = 1; $i <= 4; $i++): ?>
      <div class="max-w-sm bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-gray-300 border-2  ">
        <img class="w-full h-48 object-cover" src="hero.jpg" alt="Product Image <?= $i ?>">
        <div class="p-4">
          <h2 class="text-xl font-semibold font-mono text-gray-800">Sneaker <?= $i ?></h2>
          <p class="mt-2 text-gray-600 text-sm">Stylish sneaker number <?= $i ?>, perfect for daily wear.</p>
          <div class="mt-4 flex justify-between items-center">
            <span class="text-yellow-600 font-bold text-lg">$<?= 79 + $i ?>.99</span>
            <button class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white font-bold rounded-xl">Buy Now</button>
          </div>
        </div>
      </div>
    <?php endfor; ?>

      </div>
    </div>

   
  </main>
</body>
</html>
