{{-- usage --}}
{{-- <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
    @foreach($products as $product)
        <x-product-card :product="$product" />
    @endforeach
</div> --}}

{{-- resources/views/components/product-card.blade.php --}}

<div class="group relative overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 transition hover:-translate-y-1 hover:shadow-lg">

    {{-- Product Image --}}
    <div class="relative aspect-square overflow-hidden bg-gray-100">
        <img
            src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/placeholder.jpg') }}"
            alt="{{ $product->name }}"
            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
        >

        {{-- Discount Badge --}}
        @if($product->discount)
            <span class="absolute left-3 top-3 rounded-full bg-red-500 px-3 py-1 text-xs font-semibold text-white">
                -{{ $product->discount }}%
            </span>
        @endif

        {{-- Wishlist --}}
        <button
            type="button"
            class="absolute right-3 top-3 flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-gray-600 shadow transition hover:bg-red-500 hover:text-white"
            aria-label="Add {{ $product->name }} to wishlist"
        >
            ♥
        </button>

        {{-- Quick Add --}}
        <div class="absolute inset-x-3 bottom-3 translate-y-16 opacity-0 transition duration-300 group-hover:translate-y-0 group-hover:opacity-100">
            <form action="{{ route('cart.add', $product) }}" method="POST">
                @csrf
                <button
                    type="submit"
                    class="w-full rounded-xl bg-black px-4 py-3 text-sm font-semibold text-white transition hover:bg-gray-800"
                >
                    Add to Cart
                </button>
            </form>
        </div>
    </div>

    {{-- Product Details --}}
    <div class="p-4">

        {{-- Category --}}
        @if($product->category)
            <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-400">
                {{ $product->category->name }}
            </p>
        @endif

        {{-- Product Name --}}
        <h3 class="line-clamp-2 min-h-[3rem] text-base font-semibold text-gray-900">
            <a href="{{ route('products.show', $product) }}">
                {{ $product->name }}
            </a>
        </h3>

        {{-- Rating --}}
        <div class="mt-2 flex items-center gap-1">
            <div class="text-sm text-yellow-400">
                ★★★★★
            </div>

            <span class="text-xs text-gray-400">
                ({{ $product->reviews_count ?? 0 }})
            </span>
        </div>

        {{-- Price --}}
        <div class="mt-3 flex items-center gap-2">
            <span class="text-xl font-bold text-gray-900">
                ${{ number_format($product->price, 2) }}
            </span>

            @if($product->old_price)
                <span class="text-sm text-gray-400 line-through">
                    ${{ number_format($product->old_price, 2) }}
                </span>
            @endif
        </div>

        {{-- Mobile Add to Cart --}}
        <form action="" method="POST" class="mt-4 md:hidden">
            @csrf

            <button
                type="submit"
                class="w-full rounded-xl bg-black px-4 py-3 text-sm font-semibold text-white"
            >
                Add to Cart
            </button>
        </form>

    </div>
</div>