
  
  
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
          if(isset($_SESSION['user'])):  ?>
          <form method="POST" action="/thunderkicks1/Thunderkick-advdb/public/logout">
            <button type="submit" class="font-extrabold text-xl font-mono px-4 py-1 bg-yellow-400 rounded-2xl hover:bg-yellow-500 hover:cursor-pointer" >Logout</button>

          </form>
      <?php else: ?>
        <button type="button" class="font-extrabold text-xl font-mono px-4 py-1 bg-yellow-400 rounded-2xl hover:bg-yellow-500 hover:cursor-pointer" ><a href="/Thunderkicks1/Thunderkick-advdb/public/login">Login</a></button>
        <?php endif;?>
        </div>
    </nav>
   
  