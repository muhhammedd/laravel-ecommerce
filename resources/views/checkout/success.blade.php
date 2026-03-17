<x-app-layout>
    <div class="container mx-auto px-6 py-16 text-center max-w-2xl">
        <div class="w-16 h-16 bg-candle-light text-candle-green rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        
        <h1 class="font-serif text-4xl font-light text-candle-dark mb-4">Thank you for your order!</h1>
        
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8 text-left">
            @if(session('success'))
                <p class="text-green-700 font-medium mb-2">{{ session('success') }}</p>
            @endif
            <p class="text-gray-600 mb-4">Your order has been successfully placed. We will process it shortly.</p>
            <p class="text-gray-800 font-semibold bg-gray-50 p-3 rounded">
                Payment Method: <span class="text-candle-green">Cash on Delivery</span>
            </p>
        </div>
        
        <a href="{{ route('home') }}" class="inline-block px-8 py-3 bg-amber-700 text-white rounded-lg font-semibold hover:bg-opacity-90 transition-colors">
            Continue Shopping
        </a>
    </div>
</x-app-layout>
