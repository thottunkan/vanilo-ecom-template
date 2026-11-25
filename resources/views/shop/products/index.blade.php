@extends('shop.layout.shop-layout')
@section('title', 'Shop Products')
@section('content')

    <div class="container py-5">
        <form method="GET">
            {{-- <input class="form-control mb-3" name="search" value="{{ request('search') }}" placeholder="Search"> --}}

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="section-title mb-1">Products</h1>
                    <div class="text-muted">{{ $products->total() }} products</div>
                </div>

                <select name="sort" class="form-select" style="width: auto;" onchange="this.form.submit()">
                    <option value="">Sort by</option>
                    <option value="price_low_high" @selected(request('sort') === 'price_low_high')>Price: Low to high</option>
                    <option value="price_high_low" @selected(request('sort') === 'price_high_low')>Price: High to low</option>
                    <option value="a_to_z" @selected(request('sort') === 'a_to_z')>Name A-Z</option>
                    <option value="z_to_a" @selected(request('sort') === 'z_to_a')>Name Z-A</option>
                </select>
            </div>

            <div class="row g-4">
                <aside class="col-lg-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5>Filters</h5>
                            <label class="form-label">Category</label>
                            {{-- @foreach ($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]"
                                    value="{{ $category->slug }}" @checked(in_array($category->slug, (array)
                                request('categories', [])))>
                                <label class="form-check-label">{{ $category->name }}</label>
                            </div>
                            @endforeach --}}
                            {{-- tree --}}


                            @include('shop.widgets.category-filter', ['categories' => $categories])


                            <input type="checkbox" name="colors[]" value="blue" id="">Blue
                            <div class="row g-2 mt-3">
                                <div class="col">
                                    <input class="form-control" type="number" name="min_price"
                                        value="{{ request('min_price') }}" placeholder="Min">
                                </div>
                                <div class="col">
                                    <input class="form-control" type="number" name="max_price"
                                        value="{{ request('max_price') }}" placeholder="Max">
                                </div>
                            </div>

                            <button class="btn btn-dark w-100 mt-3">Apply filters</button>
                        </div>
                    </div>
                </aside>

                <main class="col-lg-9">
                    <div class="row g-4">
                        @include('shop.widgets.productloop',['products'=>$products])
                    </div>
                    <div class="mt-5">
                        {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </main>
            </div>
        </form>
    </div>
@endsection
