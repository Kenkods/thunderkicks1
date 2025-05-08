<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="/Thunderkicks1/Thunderkick-advdb/public/css/Landing/style.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">


  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <body class="w-full overflow-x-hidden caret-transparent focus:outline-none"> 
    <header class="cursor-default bg-gray-100">
        <nav class="flex flex-wrap items-center justify-between px-7 py-3">
            <div class="flex items-center space-x-4 w-full md:w-auto">
                <img src="/Thunderkicks1/Thunderkick-advdb/public/imgs/logo.png" alt="logo" class="w-24 md:w-40">
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
            <button type="button" class="font-extrabold text-xl font-mono px-4 py-1 bg-yellow-400 rounded-2xl hover:bg-yellow-500 hover:cursor-pointer" ><a href="/Thunderkicks1/Thunderkick-advdb/pages/userAuth/login.php">Login</a></button>
        </div>
        </nav>
    </header>
  </body>

    <main>
        <div class="w-full bg-gray-400 h-90">
            <!-- <img src="" alt="Hero Samba Original" class="w-full max-h-120 object-cover"> -->
             <div class="relative top-40 left-10">
                <h1 class="font-bold text-3xl px-3 py-2 ">Adidas</h1>
                <h2 class="font-medium text-2xl pl-7">SuperNova Rise 2 Running Shoes</h2>
                <h3 class="font-medium pl-14 py-2">Feel good, Run Better</h3>
            </div>
        </div>

        <div class="w-full my-5">
            <div class="w-full flex justify-center items-center font-semibold text-3xl py-4 bg-gray-300">All Products</div>
            <div class="w-90 bg-gray-200 max-h-120">
                <div class="px-5">
                    <h2 class="font-medium py-2 text-xl">Type</h2>
                        <input type="checkbox" class="ml-4">
                        <label for="Running Shoes"> Running Shoes</label>
                        <input type="checkbox" class="ml-4">
                        <label for="Basketball Shoes">Basketball Shoes</label>
                   
                </div>.
                <div>
                    
                </div>
            </div>
            

        </div>
    </main>
</head>
</html>