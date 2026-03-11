<header
    x-data="{
        mobileMenuOpen: false,
        cartItemsCount: {{ \App\Helpers\Cart::getCartItemsCount() }},
    }"
    @cart-change.window="cartItemsCount = $event.detail.count"
    class="bg-white"
>
    <!-- Announcement Bar -->
    <div class="bg-candle-green text-white text-center py-2 text-sm">
        Hand poured artisan coconut soy wax candles Tamborine Mountain QLD
    </div>

    <!-- Main Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex-shrink-0">
            <div class="font-serif text-2xl font-light tracking-wide text-candle-dark">
                MOUNTAINCANDLECO
            </div>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-8 flex-1 justify-center">
            <a href="{{ route('home') }}" class="text-candle-dark hover:text-candle-green transition-colors">Home</a>
            <a href="{{ route('product.index') }}" class="text-candle-dark hover:text-candle-green transition-colors">Shop</a>
            <a href="#" class="text-candle-dark hover:text-candle-green transition-colors">Our Story</a>
            <a href="#" class="text-candle-dark hover:text-candle-green transition-colors">Markets</a>
            <a href="#" class="text-candle-dark hover:text-candle-green transition-colors">Wholesale</a>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-candle-dark hover:text-candle-green transition-colors flex items-center gap-1">
                    FAQ
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" @click.outside="open = false" class="absolute top-full right-0 mt-2 bg-white border border-gray-200 rounded shadow-lg py-2 min-w-max">
                    <a href="#" class="block px-4 py-2 text-candle-dark hover:bg-candle-light">Frequently Asked Questions</a>
                </div>
            </div>
        </nav>

        <!-- Right Icons -->
        <div class="flex items-center gap-6">
            <!-- Search -->
            <button class="text-candle-dark hover:text-candle-green transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>

            <!-- User Account -->
            @if (!Auth::guest())
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="text-candle-dark hover:text-candle-green transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" class="absolute top-full right-0 mt-2 bg-white border border-gray-200 rounded shadow-lg py-2 min-w-max">
                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-candle-dark hover:bg-candle-light">My Profile</a>
                        <a href="{{ route('order.index') }}" class="block px-4 py-2 text-candle-dark hover:bg-candle-light">My Orders</a>
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-candle-dark hover:bg-candle-light">Log Out</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-candle-dark hover:text-candle-green transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </a>
            @endif

            <!-- Wishlist -->
            <button class="text-candle-dark hover:text-candle-green transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>

            <!-- Cart -->
            <a href="{{ route('cart.index') }}" class="relative text-candle-dark hover:text-candle-green transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <small
                    x-show="cartItemsCount"
                    x-transition
                    x-cloak
                    x-text="cartItemsCount"
                    class="absolute -top-2 -right-2 py-[2px] px-[6px] rounded-full bg-red-500 text-white text-xs font-semibold"
                ></small>
            </a>

            <!-- Mobile Menu Toggle -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-candle-dark hover:text-candle-green transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div
        class="fixed z-10 top-0 bottom-0 left-0 w-64 bg-white shadow-lg transition-transform transform"
        :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        <div class="p-6">
            <button @click="mobileMenuOpen = false" class="absolute top-4 right-4 text-candle-dark">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <nav class="mt-8 flex flex-col gap-4">
                <a href="{{ route('home') }}" class="text-candle-dark hover:text-candle-green transition-colors">Home</a>
                <a href="{{ route('product.index') }}" class="text-candle-dark hover:text-candle-green transition-colors">Shop</a>
                <a href="#" class="text-candle-dark hover:text-candle-green transition-colors">Our Story</a>
                <a href="#" class="text-candle-dark hover:text-candle-green transition-colors">Markets</a>
                <a href="#" class="text-candle-dark hover:text-candle-green transition-colors">Wholesale</a>
                <a href="#" class="text-candle-dark hover:text-candle-green transition-colors">FAQ</a>
                <hr class="my-4">
                <a href="{{ route('cart.index') }}" class="text-candle-dark hover:text-candle-green transition-colors">Cart</a>
                @if (!Auth::guest())
                    <a href="{{ route('profile') }}" class="text-candle-dark hover:text-candle-green transition-colors">My Profile</a>
                    <a href="{{ route('order.index') }}" class="text-candle-dark hover:text-candle-green transition-colors">My Orders</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-candle-dark hover:text-candle-green transition-colors">Log Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-candle-dark hover:text-candle-green transition-colors">Log In</a>
                    <a href="{{ route('register') }}" class="text-candle-dark hover:text-candle-green transition-colors">Register</a>
                @endif
            </nav>
        </div>
    </div>
</header>
