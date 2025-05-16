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
            <div class="flex justify-end px-30">
                <h1 class=" py-5 text-2xl px-3 font-bold font-sans hover:cursor-pointer" id="filter">Filter</h1>
                <img src="/thunderkicks1/thunderkick-advdb/public/imgs/setting.png" alt="filter" class="max-w-8 py-5 hover:cursor-pointer" id="filter">
            </div>
            <div id="filter-modal" class="px-5  pt-2 bg-gray-400" >
                <h1 class="text-2xl font-bold">Brands</h1>
                <div id="filter-brand" class="flex  space-x-5 py-2 ml-5">
                    <button class="font-semibold text-2xl border-2 px-5 py-2 rounded-2xl hover:cursor-pointer hover:scale-105 transition-all duration-300 hover:bg-gray-200">Adidas</button>
                    <button class="font-semibold text-2xl border-2 px-5 py-2 rounded-2xl hover:cursor-pointer hover:scale-105 transition-all duration-300 hover:bg-gray-200">Nike</button>
                    <button class="font-semibold text-2xl border-2 px-5 py-2 rounded-2xl hover:cursor-pointer hover:scale-105 transition-all duration-300 hover:bg-gray-200">Anta</button>
                    <button class="font-semibold text-2xl border-2 px-5 py-2 rounded-2xl hover:cursor-pointer hover:scale-105 transition-all duration-300 hover:bg-gray-200">New Balance</button>
                </div>
                <h1 class="text-3xl font-bold">Sizes</h1>
            </div>
           
            <div class="w-full flex flex-wrap  justify-center items-center ">
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