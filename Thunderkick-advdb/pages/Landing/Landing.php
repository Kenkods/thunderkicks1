<?php







?>

<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="/Thunderkicks1/Thunderkick-advdb/public/css/Landing/style.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">


  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
    body,
    div,
    section,
    main {
      caret-color: transparent;
      outline: none;
    }

    @keyframes slide-in-right {
      from {
        transform: translateX(100%);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    .animate-slide-in {
      animation: slide-in-right 0.5s ease-out;
    }
  </style>
</head>

<body class="w-full overflow-x-hidden caret-transparent focus:outline-none">
  <?php if (isset($_SESSION['success'])): ?>
    <div id="flash-message" class="absolute top-4 right-4 z-50 bg-yellow-500 border border-yellow-600 text-white px-4 py-3 rounded shadow-md animate-slide-in" role="alert">
      <strong class="font-bold">Success!</strong>
      <span class="block sm:inline"><?= $_SESSION['success']; ?></span>
      <button onclick="this.parentElement.remove();" class="float-right text-white hover:text-gray-200 font-bold ml-2">&times;</button>
    </div>

    <script>
      setTimeout(() => {
        const flash = document.getElementById('flash-message');
        if (flash) flash.remove();
      }, 3000);
    </script>

    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <header class="cursor-default bg-gray-200">
    <nav class="flex flex-wrap items-center justify-between px-7 py-3">
      <div class="flex items-center space-x-4 w-full md:w-auto">
        <a href="/thunderkicks1/thunderkick-advdb/public/index.php?page=landing">
          <img src="/Thunderkicks1/Thunderkick-advdb/public/imgs/logo.png" alt="logo" class="w-24 md:w-40">
        </a>
      </div>

      <div class="flex items-center justify-end w-full md:w-auto mt-4 md:mt-0 space-x-3">
        <div class="relative w-full max-w-xs">
          <input type="search" placeholder="Search entire shop here" class="w-full h-8 pr-10 px-3 py-1 outline-none focus:border-yellow-500 focus:border-b-2 text-lg">
          <img src="/Thunderkicks1/Thunderkick-advdb/public/imgs/search.png" alt="search icon" class="h-5 w-5 absolute right-2 top-1.5">
        </div>
        <img src="/Thunderkicks1/Thunderkick-advdb/public/imgs/checkout.png" alt="checkout" class="h-7 hover:cursor-pointer" id="cart-icon">
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

  <main class="w-full caret-transparent bg-gray-10">
    <div class="w-full px-5 md:px-20 py-5">
      <div class="relative w-full cursor-default">
        <img src="/Thunderkicks1/Thunderkick-advdb/public/imgs/hero.jpg" alt="hero" class="w-full max-h-[680px] object-cover rounded-lg border-gray-300 border-2">
        <h1 class="font-bold font-serif absolute top-4/7 left-25  text-xl text-gray-600">Style That Starts from the Ground Up.</h1>
        <button type="button"
          class="absolute top-2/3 left-30 transform -translate-y-1/2 font-extrabold text-sm md:text-xl bg-slate-700 font-mono hover:bg-gray-900 text-white py-2 px-4 md:px-6 rounded-3xl hover:cursor-pointer">
          Reserve now
        </button>
      </div>
    </div>

    <div class="w-full flex flex-col md:flex-row justify-center items-center py-5 bg-gray-100 px-5 md:px-20 text-xl my-14 ">
      <h1 class="font-bold text-3xl md:text-4xl  mb-4 md:mb-0 font-['Montserrat']">Top Selling </h1>

    </div>



    <div class="w-full  ">
      <div class="w-full flex flex-wrap py-2  px-28 justify-center items-center">


        <?php foreach ($topProducts as $cards): ?>


          <div class="card bg-white rounded-xl shadow-md overflow-hidden  hover:drop-shadow-[0px_4px_5px_rgba(77,77,92,0.8)] transition-shadow duration-300 border-1 border-gray-200  my-3 mx-4 max-w-65 hover:cursor-pointer">
            <input name="shoe_id" id="" type="hidden" value=<?= htmlspecialchars($cards['shoe_id']) ?>>
            <img class=" h-40 w-full object-cover" src=<?= htmlspecialchars($cards['shoe_img']) ?> alt="Product Image">
            <input type="hidden" value="<?= htmlspecialchars($cards['shoe_id']) ?>">
            <div class="px-3 py-4">
              <div class=" flex flex-wrap  h-15">
                <h2 class="text-xl font-semibold font-mono text-gray-800 flex flex-wrap w-60"><?= htmlspecialchars($cards['name']) ?></h2>
              </div>
              <div class="flex flex-wrap px-2">







                <?php if (isset($_SESSION['user'])): ?>

                  <!-- <form method="POST" action="/Thunderkicks1/Thunderkick-advdb/public/index.php?page=addToCart"> -->

                  <!-- </form> -->
                <?php else: ?>
                  <a href="/Thunderkicks1/Thunderkick-advdb/public/login" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white font-bold rounded-xl hover:cursor-pointer">Add to Cart</a>
                <?php endif; ?>

              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <button class=" border-1 relative top-5 px-5 py-2 font-medium hover:cursor-pointer hover:scale-105 transition duration-300"> <a href="/Thunderkicks1/Thunderkick-advdb/public/products">Go To Products</a></button>

      </div>


      <div class="w-full flex flex-col md:flex-row justify-center items-center py-5 bg-gray-100 px-5 md:px-20 text-xl my-15 ">
        <h1 class="font-bold text-3xl md:text-4xl  mb-4 md:mb-0 font-['Montserrat']">Shop By Category</h1>
      </div>
      <div class="w-full">
        <div class="w-full flex flex-wrap py-1 px-20 justify-center items-center">

          <a href="/Thunderkicks1/Thunderkick-advdb/public/products=men">
            <div class="bg-white rounded-xl overflow-hidden border-0 my-3 mx-4 hover:cursor-pointer max-w-110 group transition duration-300 hover:scale-100">
              <img class="w-full object-cover h-120 transition duration-300 transform group-hover:scale-105" src="/Thunderkicks1/Thunderkick-advdb/public/imgs/shoes/mensCategory.jpg" alt="Product Image">
              <h1 class="flex justify-center items-center my-7 font-semibold text-2xl">MEN</h1>
            </div>


            <a href="/Thunderkicks1/Thunderkick-advdb/public/products=women">
              <div class="bg-white rounded-xl overflow-hidden border-0 my-3 mx-4 hover:cursor-pointer max-w-110 group transition duration-300  hover:scale-100">
                <img class="w-full object-cover h-120 transition duration-300 transform group-hover:scale-105" src="/Thunderkicks1/Thunderkick-advdb/public/imgs/shoes/womenCategory.jpg" alt="Product Image">
                <h1 class="flex justify-center items-center my-7 font-semibold text-2xl">WOMEN</h1>
              </div>


              <a href="/Thunderkicks1/Thunderkick-advdb/public/products=kids">
                <div class="bg-white rounded-xl overflow-hidden border-0 my-3 mx-4 hover:cursor-pointer max-w-80 group transition duration-300  hover:scale-100">
                  <img class="w-full object-cover h-120 transition duration-300 transform group-hover:scale-105" src="/Thunderkicks1/Thunderkick-advdb/public/imgs/shoes/kidsCategory.jpg" alt="Product Image">
                  <h1 class="flex justify-center items-center my-7 font-semibold text-2xl">KIDS</h1>
                </div>
              </a>

        </div>
      </div>


    </div>


  </main>
  <script src="/thunderkicks1/thunderkick-advdb/public/js/landing.js"></script>
</body>

</html>