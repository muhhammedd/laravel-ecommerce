<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
$categoryList = \App\Models\Category::getActiveAsTree();
?>

<x-app-layout>
    <!-- Products Header Section -->
    <!-- Filters and Sort Bar -->
    <div class="px-6 py-6 border-b border-gray-200" x-data="{
        selectedSort: '{{ request()->get('sort', '-updated_at') }}',
        searchKeyword: '{{ request()->get('search') }}',
        updateUrl() {
            const params = new URLSearchParams(window.location.search)
            if (this.selectedSort && this.selectedSort !== '-updated_at') {
                params.set('sort', this.selectedSort)
            } else {
                params.delete('sort')
            }

            if (this.searchKeyword) {
                params.set('search', this.searchKeyword)
            } else {
                params.delete('search')
            }
            window.location.href = window.location.origin + window.location.pathname + '?'
            + params.toString();
        }
    }">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <!-- Left: Filters -->
            <div class="flex items-center gap-4">
                <span class="text-sm font-semibold text-candle-dark">Filter:</span>
                <button class="px-4 py-2 border border-gray-300 rounded text-sm text-candle-dark hover:border-candle-green transition-colors">
                    Availability
                </button>
                <button class="px-4 py-2 border border-gray-300 rounded text-sm text-candle-dark hover:border-candle-green transition-colors">
                    Price
                </button>
            </div>

            <!-- Right: Sort and Count -->
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <label for="sort" class="text-sm font-semibold text-candle-dark">Sort by:</label>
                    <select
                        id="sort"
                        x-model="selectedSort"
                        @change="updateUrl"
                        class="px-3 py-2 border border-gray-300 rounded text-sm text-candle-dark focus:border-candle-green focus:ring-candle-green"
                    >
                        <option value="-updated_at">Featured</option>
                        <option value="price">Price (Low to High)</option>
                        <option value="-price">Price (High to Low)</option>
                        <option value="title">Title (A-Z)</option>
                        <option value="-title">Title (Z-A)</option>
                    </select>
                </div>
                <span class="text-sm text-gray-600">{{ $products->count() }} products</span>
            </div>
        </div>

        <!-- Search Bar -->
        <form action="" method="GET" class="mt-6">
            <input
                type="text"
                name="search"
                placeholder="Search for the products"
                value="{{ request()->get('search') }}"
                class="w-full px-4 py-2 border border-gray-300 rounded text-candle-dark placeholder-gray-400 focus:border-candle-green focus:ring-candle-green"
            />
        </form>
    </div>

    <!-- Products Grid -->
    @if ($products->count() === 0)
        <div class="text-center text-gray-600 py-16 text-lg">
            There are no products published
        </div>
    @else
        <div class="px-2 py-2">
            <div class="grid gap-4 grid-cols-2 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($products as $product)
                    <!-- Product Card -->
                    <div
                        x-data="productItem({{ json_encode([
                            'id' => $product->id,
                            'slug' => $product->slug,
                            'image' => $product->image ?: '/img/noimage.png',
                            'title' => $product->title,
                            'price' => $product->price,
                            'addToCartUrl' => route('cart.add', $product)
                        ]) }})"
                        class="group bg-white rounded-md overflow-hidden hover:shadow-lg transition-shadow"
                    >
                        <!-- Product Image -->
                        <a href="{{ route('product.view', $product->slug) }}" class="block overflow-hidden bg-gray-100 aspect-square">
                            <img
                                :src="product.image"
                                alt="{{ $product->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            />
                        </a>

                        <!-- Product Info -->
                        <div class="p-4">
                            <h3 class="text-xl font-bold text-candle-dark mb-4 line-clamp-2">
                                <a href="{{ route('product.view', $product->slug) }}" class="hover:text-candle-primary transition-colors">
                                    {{ $product->title }}
                                </a>
                            </h3>
                            <p class="text-lg font-semibold text-candle-dark">${{ number_format($product->price, 2) }} EGP</p>
                        </div>

                        <!-- Add to Cart Button -->
                        <div class="px-4 pb-4">
                            <button
                                @click="addToCart()"
                                class="w-full px-4 py-2 bg-amber-700 text-white rounded hover:bg-opacity-90 transition-colors text-sm font-semibold"
                            >
                                Add to Cart
                            </button>
                        </div>
                    </div>
                    <!--/ Product Card -->
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->appends(['sort' => request('sort'), 'search' => request('search')])->links() }}
            </div>
        </div>
    @endif
</x-app-layout>
