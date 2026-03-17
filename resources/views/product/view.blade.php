<x-app-layout>
    <div x-data="productItem({{ json_encode([
        'id' => $product->id,
        'slug' => $product->slug,
        'image' => $product->image ?: '/img/noimage.png',
        'title' => $product->title,
        'price' => $product->price,
        'quantity' => $product->quantity,
        'addToCartUrl' => route('cart.add', $product)
    ]) }})" class="container mx-auto px-6 py-8">
        <div class="grid gap-8 grid-cols-1 lg:grid-cols-5">
            <!-- Product Images -->
            <div class="lg:col-span-3">
                <div x-data="{
                    images: {{ $product->images->count() ? $product->images->map(fn($im) => $im->url) : json_encode(['/img/noimage.png']) }},
                    activeImage: null,
                    prev() {
                        let index = this.images.indexOf(this.activeImage);
                        if (index === 0) index = this.images.length;
                        this.activeImage = this.images[index - 1];
                    },
                    next() {
                        let index = this.images.indexOf(this.activeImage);
                        if (index === this.images.length - 1) index = -1;
                        this.activeImage = this.images[index + 1];
                    },
                    init() {
                        this.activeImage = this.images.length > 0 ? this.images[0] : null
                    }
                }">
                    <!-- Main Image -->
                    <div class="relative bg-candle-light rounded-lg overflow-hidden mb-4">
                        <template x-for="image in images">
                            <div x-show="activeImage === image" class="w-full aspect-square flex items-center justify-center">
                                <img :src="image" alt="" class="w-auto h-auto max-h-full mx-auto"/>
                            </div>
                        </template>
                        <!-- Navigation Arrows -->
                        <button
                            @click.prevent="prev"
                            class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button
                            @click.prevent="next"
                            class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                    <!-- Thumbnail Gallery -->
                    <div class="flex gap-2 overflow-x-auto">
                        <template x-for="image in images">
                            <button
                                @click.prevent="activeImage = image"
                                class="flex-shrink-0 w-20 h-20 border-2 rounded-lg overflow-hidden hover:border-candle-green transition-colors"
                                :class="{'border-candle-green': activeImage === image, 'border-gray-300': activeImage !== image}"
                            >
                                <img :src="image" alt="" class="w-full h-full object-cover"/>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="lg:col-span-2">
                <h1 class="font-serif text-3xl font-bold text-candle-dark mb-4">
                    {{ $product->title }}
                </h1>

                <div class="text-2xl font-semibold text-candle-dark mb-6">
                    ${{ number_format($product->price, 2) }} EGP
                </div>

                <!-- Stock Status -->
                @if ($product->quantity === 0)
                    <div class="bg-red-100 border border-red-300 text-red-700 py-3 px-4 rounded-lg mb-6">
                        The product is out of stock
                    </div>
                @else
                    <div class="text-sm text-green-600 mb-6">
                        In Stock ({{ $product->quantity }} available)
                    </div>
                @endif

                <!-- Quantity Selector -->
                <div class="flex items-center gap-4 mb-6">
                    <label for="quantity" class="font-semibold text-candle-dark">
                        Quantity:
                    </label>
                    <input
                        type="number"
                        name="quantity"
                        x-ref="quantityEl"
                        value="1"
                        min="1"
                        :max="product.quantity"
                        class="w-20 px-3 py-2 border border-gray-300 rounded text-center focus:border-candle-green focus:ring-candle-green"
                    />
                </div>

                <!-- Add to Cart Button -->
                <button
                    :disabled="product.quantity === 0"
                    @click="addToCart($refs.quantityEl.value)"
                    class="w-full py-3 px-4 bg-amber-700 text-white rounded-lg font-semibold hover:bg-opacity-90 transition-colors mb-6 flex items-center justify-center gap-2"
                    :class="product.quantity === 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Add to Cart
                </button>

                <!-- Product Description -->
                 <div class="border-t border-gray-200 pt-6" x-data="{ expanded: true }">
                    <button
                        @click="expanded = !expanded"
                        class="flex items-center justify-between w-full text-lg font-semibold text-candle-dark hover:text-candle-green transition-colors mb-4"
                    >
                        <span>Product Description</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform" :class="{'rotate-180': expanded}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7-7m0 0L5 14m7-7v12" />
                        </svg>
                   </button>
                   <div x-show="expanded" x-collapse class="text-candle-dark text-md leading-relaxed">
                        {!! $product->description !!}
                    </div>
                </div>
                <div class="border-t border-gray-200 pt-6" x-data="{ expanded: false }">
                    <button
                        @click="expanded = !expanded"
                        class="flex items-center justify-between w-full text-lg font-semibold text-candle-dark hover:text-candle-green transition-colors mb-4"
                    >
                        <span>Shipping Details</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform" :class="{'rotate-180': expanded}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7-7m0 0L5 14m7-7v12" />
                        </svg>
                   </button>
                   <div x-show="expanded" x-collapse class="text-candle-dark text-md leading-relaxed">
                        Order will be delivered within 3-5 business days.
                        <br>
                        No returns accepted.
                        <br>
                        No refunds.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
