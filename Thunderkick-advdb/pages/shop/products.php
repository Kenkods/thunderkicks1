<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/Thunderkicks1/Thunderkick-advdb/public/css/Landing/style.css" rel="stylesheet">
    <title>  </title>
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
        <div class="w-full bg-gray-900">
            <div class="w-full flex flex-wrap  justify-center items-center ">
                <?php for($i=0;$i<8;$i++):?>
                 <div class=" bg-white rounded-xl shadow-md overflow-hidden  hover:drop-shadow-[0px_4px_5px_rgba(77,77,92,0.8)] transition-shadow duration-300 border-1 border-gray-200  my-2 mx-2 min-w-75 max-w-75 hover:cursor-pointer ">
                    <img class=" h-40 w-full object-cover" src="" alt="Product Image">
                    <div class="px-3 py-4">
                        <div class=" flex flex-wrap  h-15">
                            <h2 class="text-xl font-semibold font-mono text-gray-800 flex flex-wrap w-60">name</h2>
                        </div>
                        <p class="mt-2 text-gray-600 text-sm font-semibold relative">Stock : </p>
                    
                        <div class="mt-4 flex justify-between items-center">
                            <span class=" font-bold text-lg">$</span>
                            <button class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white font-bold rounded-xl hover:cursor-pointer">Buy Now</button>
                        </div>
                    </div>
                </div>
                <?php endfor;?>


            </div>
        </div>
    


    </main>
</body>
</html>