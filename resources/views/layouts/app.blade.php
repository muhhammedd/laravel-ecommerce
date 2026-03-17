<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mountain Candle Co') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@400;500;600;700&family=Cormorant+Garamond:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="bg-white">
    @include('layouts.navigation')

    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-amber-700 text-white mt-16">
        <!-- Mission Section -->
        <div class="px-6 py-16 text-center border-b border-white border-opacity-20">
            <h2 class="font-serif text-2xl font-light mb-4">our mission</h2>
            <p class="max-w-2xl mx-auto text-sm leading-relaxed opacity-90">
                Self-care and mental health are so important to us here at Dreams Candles. We truly believe that in the chaos of the world, a little moment of peace and tranquility can make all the difference. Connect to the power of nature.
            </p>
            <p class="mt-4 text-sm font-semibold">CANDLE ON : WORLD OFF</p>
        </div>

        <!-- Newsletter Section -->
        <div class="px-6 py-12 text-center border-b border-white border-opacity-20">
            <h3 class="font-serif text-xl font-light mb-6">join our candle community below for exciting news on launches + exclusive offers</h3>
            <form class="max-w-md mx-auto flex gap-2">
                <input
                    type="email"
                    placeholder="Email"
                    class="flex-1 px-4 py-2 rounded text-candle-dark placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-white"
                />
                <button type="submit" class="px-6 py-2 bg-white text-candle-green rounded hover:bg-opacity-90 transition-colors font-semibold">
                    →
                </button>
            </form>
        </div>

        <!-- Bottom Section -->
        <div class="px-6 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <!-- Payment Methods -->
                <div class="flex items-center justify-center md:justify-start gap-3 flex-wrap">
                    <span class="text-xs opacity-75">Payment methods:</span>
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><rect x="1" y="5" width="22" height="14" rx="2" fill="none" stroke="currentColor" stroke-width="1"/><path d="M1 10h22" stroke="currentColor" stroke-width="1"/></svg>
                </div>

                <!-- Social Links -->
                <div class="flex items-center justify-center gap-4">
                    <a href="#" class="hover:opacity-75 transition-opacity">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22.5C6.201 22.5 1.5 17.799 1.5 12S6.201 1.5 12 1.5 22.5 6.201 22.5 12 17.799 22.5 12 22.5z"/></svg>
                    </a>
                    <a href="#" class="hover:opacity-75 transition-opacity">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22.5C6.201 22.5 1.5 17.799 1.5 12S6.201 1.5 12 1.5 22.5 6.201 22.5 12 17.799 22.5 12 22.5z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Copyright -->
            <div class="text-center text-xs opacity-75 mt-6 pt-6 border-t border-white border-opacity-20">
                <p>&copy; 2024 Mountain Candle Co. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Toast Notification -->
    <div
        x-data="toast"
        x-show="visible"
        x-transition
        x-cloak
        @notify.window="show($event.detail.message, $event.detail.type || 'success')"
        class="fixed w-[400px] left-1/2 -ml-[200px] top-16 py-2 px-4 pb-4 text-white rounded-lg shadow-lg"
        :class="type === 'success' ? 'bg-emerald-500' : 'bg-red-500'"
    >
        <div class="font-semibold" x-text="message"></div>
        <button
            @click="close"
            class="absolute flex items-center justify-center right-2 top-2 w-[30px] h-[30px] rounded-full hover:bg-black/10 transition-colors"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>
        </button>
        <!-- Progress -->
        <div>
            <div
                class="absolute left-0 bottom-0 right-0 h-[6px] bg-black/10"
                :style="{'width': `${percent}%`}"
            ></div>
        </div>
    </div>
    <!--/ Toast -->
</body>
</html>
