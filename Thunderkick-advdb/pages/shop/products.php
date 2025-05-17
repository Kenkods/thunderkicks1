<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/Thunderkicks1/Thunderkick-advdb/public/css/Landing/style.css" rel="stylesheet">
    <title>  </title>

    <style>
        #filter-modal{
            width: 100%;
            height:auto;
            
        }
    </style>
</head>
<body class="overflow-x-hidden">
    <header>
        <?php 
            require_once __DIR__. '/../nav.php';
        ?>
    </header>
    <main>
        <div class="w-full px-11 pb-7 bg-gray-200">
            <div class="flex flex-wrap max-w-xl">
                <h1 class="text-6xl font-mono pt-7 pb-2 font-bold">Basketball</h1>
               <p class="font-medium">Unlock peak performance with shoes engineered for speed,
     support, and all-day comfort — both on and off the court.</p>
            </div>
        </div>

        <div class="w-full">
            <div class="flex justify-end px-30" >
                <div id="filter-parent" class="flex">
                    <h1 class=" py-5 text-2xl px-3 font-bold font-sans hover:cursor-pointer" id="filter">Filter</h1>
                    <img src="/thunderkicks1/thunderkick-advdb/public/imgs/setting.png" alt="filter" class="max-w-8 py-5 hover:cursor-pointer" id="filter">
                </div>
            </div>
            <div id="filter-modal" class="px-5 pb-5  pt-2 bg-gray-200 max-w-lg absolute right-0 z-50" >
                <h1 class="text-2xl font-bold">Brands</h1>
                <div data-filter="brand" class=" flex space-x-4 text-2xl font-medium">
                    <button data-value="Nike" class=" px-2 py-1 hover:cursor-pointer hover:scale-105 transition duration-300 ">Nike</button>
                    <button data-value="Adidas" class="px-2 py-1 hover:cursor-pointer hover:scale-105 transition duration-300 ">Adidas</button>
                    <button data-value="Anta" class=" px-2 py-1 hover:cursor-pointer hover:scale-105 transition duration-300 ">Anta</button>
                    <button data-value="New Balance" class=" px-2 py-1 hover:cursor-pointer hover:scale-105 transition duration-300 ">New Balance</button>
                </div>
                <h1 class="text-2xl font-bold">Sizes</h1>
                <div data-filter="size" class=" flex space-x-4 text-2xl font-medium">
                    <button data-value="8" class=" px-2 py-1 hover:cursor-pointer hover:scale-105 transition-all duration-300 ">8</button>
                    <button data-value="9" class=" px-2 py-1 hover:cursor-pointer hover:scale-105 transition-all duration-300 ">9</button>
                    <button data-value="10" class=" px-2 py-1 hover:cursor-pointer hover:scale-105 transition-all duration-300 ">10</button>
                    <button data-value="11" class=" px-2 py-1 hover:cursor-pointer hover:scale-105 transition-all duration-300 ">11</button>
                    <button data-value="12" class=" px-2 py-1 hover:cursor-pointer hover:scale-105 transition-all duration-300 ">12</button>                    
                </div>
                 <h1 class="text-2xl font-bold">Category</h1>
                <div data-filter="category" class=" flex space-x-4 text-2xl font-medium">
                    <button data-value="Men" class=" px-2 py-1 hover:cursor-pointer hover:scale-105 transition-all duration-300 ">Men</button>
                    <button data-value="Women" class=" px-2 py-1 hover:cursor-pointer hover:scale-105 transition-all duration-300 ">Women</button>
                    <button data-value="Kids" class=" px-2 py-1 hover:cursor-pointer hover:scale-105 transition-all duration-300 ">Kids</button>
                                       
                </div>
                <h1 class="text-2xl font-bold">Shoe Type</h1>
                <div data-filter="type" class=" flex space-x-4 text-2xl font-medium" >
                    <button data-value="Basketball" class=" px-2 py-1 hover:cursor-pointer hover:scale-105 transition-all duration-300 ">Basketball</button>
                    <button data-value="Running" class=" px-2 py-1 hover:cursor-pointer hover:scale-105 transition-all duration-300 ">Running</button>
                    <button data-value="Casual" class=" px-2 py-1 hover:cursor-pointer hover:scale-105 transition-all duration-300 ">Casual</button>
                                     
                </div>
                <button class="text-2xl font-bold bg-yellow-600 hover:bg-yellow-500 rounded-2xl px-5 py-1 relative left-40 mt-4 hover:cursor-pointer" id="apply-filter">Apply all</button>
            </div>
           
            <div class="w-full flex flex-wrap  justify-center items-center " id="productList">
                <?php foreach($adidascards as $card): ?>
                  <div class=" bg-white rounded-xl shadow-md overflow-hidden  hover:drop-shadow-[0px_4px_5px_rgba(77,77,92,0.8)] transition-shadow duration-300 border-1 border-gray-200  my-3 mx-4 max-w-75 min-w-75 hover:cursor-pointer">
        <img class=" h-40 w-full object-cover" src=<?=htmlspecialchars($card['shoe_img'])?> alt="Product Image">
        <div class="px-3 py-4">
          <div class=" flex flex-wrap  h-15">
          <h2 class="text-xl font-semibold font-mono text-gray-800 flex flex-wrap w-60"><?=htmlspecialchars($card['name'])?></h2>
          </div>
          <p class="mt-2 text-gray-600 text-sm font-semibold relative">Stock : <?=htmlspecialchars($card['stock'])?></p>
         
          <div class="mt-4 flex justify-between items-center">
            <span class=" font-bold text-lg">$<?=htmlspecialchars($card['price'])?></span>
            <button class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white font-bold rounded-xl hover:cursor-pointer">Buy Now</button>
          </div>
        </div>
      </div>
                 <?php endforeach;?>

            </div>
        </div>
    


    </main>
    <script src="/thunderkicks1/thunderkick-advdb/public/js/products.js"></script>
</body>
</html>