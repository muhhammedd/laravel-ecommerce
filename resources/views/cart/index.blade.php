<x-app-layout>
    <div class="container mx-auto px-6 py-8">
        <h1 class="font-serif text-4xl font-light text-candle-dark mb-8">Your Cart</h1>

        <div x-data="{
            cartItems: {{
                json_encode(
                    $products->map(fn($product) => [
                        'id' => $product->id,
                        'slug' => $product->slug,
                        'image' => $product->image ?: '/img/noimage.png',
                        'title' => $product->title,
                        'price' => $product->price,
                        'quantity' => $cartItems[$product->id]['quantity'],
                        'href' => route('product.view', $product->slug),
                        'removeUrl' => route('cart.remove', $product),
                        'updateQuantityUrl' => route('cart.update-quantity', $product)
                    ])
                )
            }},
            get cartTotal() {
                return this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0).toFixed(2)
            },
        }" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <template x-if="cartItems.length">
                    <div class="space-y-4">
                        <!-- Product Item -->
                        <template x-for="product of cartItems" :key="product.id">
                            <div x-data="productItem(product)" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex gap-4">
                                    <!-- Product Image -->
                                    <a :href="product.href" class="flex-shrink-0 w-24 h-24 bg-candle-light rounded-lg overflow-hidden">
                                        <img :src="product.image" class="w-full h-full object-cover" alt=""/>
                                    </a>

                                    <!-- Product Info -->
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="font-semibold text-candle-dark">
                                                <a :href="product.href" class="hover:text-candle-green transition-colors" x-text="product.title"></a>
                                            </h3>
                                            <span class="text-lg font-semibold text-candle-dark">
                                                $<span x-text="(product.price * product.quantity).toFixed(2)"></span>
                                            </span>
                                        </div>

                                        <!-- Quantity and Remove -->
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <label class="text-sm text-gray-600">Qty:</label>
                                                <input
                                                    type="number"
                                                    min="1"
                                                    x-model="product.quantity"
                                                    @change="changeQuantity()"
                                                    class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:border-candle-green focus:ring-candle-green"
                                                />
                                            </div>
                                            <button
                                                @click.prevent="removeItemFromCart()"
                                                class="text-sm text-red-600 hover:text-red-700 transition-colors"
                                            >
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="!cartItems.length">
                    <div class="bg-candle-light rounded-lg p-12 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p class="text-gray-600 text-lg">Your cart is empty</p>
                        <a href="{{ route('product.index') }}" class="mt-4 inline-block text-candle-green hover:underline">
                            Continue Shopping
                        </a>
                    </div>
                </template>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <template x-if="cartItems.length">
                    <div class="bg-candle-light rounded-lg p-6 sticky top-20 h-fit">
                        <h2 class="font-semibold text-candle-dark text-lg mb-6">Order Summary</h2>

                        <div class="space-y-3 mb-6 pb-6 border-b border-gray-300">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-candle-dark font-semibold">$<span x-text="cartTotal"></span></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span class="text-candle-dark font-semibold">Calculated at checkout</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax</span>
                                <span class="text-candle-dark font-semibold">Calculated at checkout</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-6">
                            <span class="font-semibold text-candle-dark">Total</span>
                            <span class="text-2xl font-bold text-candle-dark">$<span x-text="cartTotal"></span></span>
                        </div>

                        <form action="{{ route('cart.checkout') }}" method="post">
                            @csrf
                            <button type="submit" class="w-full py-3 px-4 bg-candle-green text-white rounded-lg font-semibold hover:bg-opacity-90 transition-colors">
                                Proceed to Checkout
                            </button>
                        </form>

                        <a href="{{ route('product.index') }}" class="block text-center mt-4 text-candle-green hover:underline text-sm">
                            Continue Shopping
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-app-layout>
