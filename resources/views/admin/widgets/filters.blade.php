<!-- Filter Button -->
<button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterPanel">
    Filters
</button>


<!-- Filter Offcanvas Right Panel -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="filterPanel" aria-labelledby="filterPanelLabel">

    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="filterPanelLabel">
            Filter
        </h5>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>


    <div class="offcanvas-body">

        <form action="{{ $formUrl ?? '' }}" method="POST">
            @csrf
            <div class="row g-3">


                <!-- Search -->
                <div class="col-12">
                    <label class="form-label">Search</label>

                    <input type="text" name="search" class="form-control" placeholder="Search title or content..."
                        value="{{ request('search') }}">
                </div>

                @if (isset($is_stock))
                    <div class="col-12">
                        <label class="form-label">Stock</label>

                        <input type="text" name="stock" class="form-control" value="0" placeholder="Stock"
                            value="{{ request('stock') }}">
                    </div>
                @endif



                <!-- Created At -->
                <div class="col-12">
                    <label class="form-label">Created At</label>

                    <input type="date" name="created_at" class="form-control" value="{{ request('created_at') }}">
                </div>


                <!-- Updated At -->
                <div class="col-12">
                    <label class="form-label">Updated At</label>

                    <input type="date" name="updated_at" class="form-control" value="{{ request('updated_at') }}">
                </div>


                <!-- Date Range -->
                <div class="col-12">
                    <label class="form-label">From Date</label>

                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>


                <div class="col-12">
                    <label class="form-label">To Date</label>

                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>


                <!-- Status -->
                <div class="col-12">

                    <label class="form-label">Status</label>

                    <select name="status" class="form-select">

                        <option value="">All</option>

                        @foreach (['active', 'inactive', 'pending', 'deleted'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach

                    </select>

                </div>


                <!-- Sort -->
                <div class="col-12">

                    <label class="form-label">Sort By</label>

                    <select name="sort" class="form-select">

                        @php
                            $sortOptions = [
                                'created_at_desc' => 'Newest',
                                'created_at_asc' => 'Oldest',
                                'updated_at_desc' => 'Recently Updated',
                                'name_asc' => 'Name A-Z',
                                'name_desc' => 'Name Z-A',
                            ];
                        @endphp


                        @foreach ($sortOptions as $key => $label)
                            <option value="{{ $key }}" {{ request('sort') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach

                    </select>

                </div>


                <!-- Per Page -->
                <div class="col-12">

                    <label class="form-label">Records Per Page</label>

                    <select name="per_page" class="form-select">

                        @foreach ([10, 25, 50, 100] as $limit)
                            <option value="{{ $limit }}" {{ request('per_page') == $limit ? 'selected' : '' }}>
                                {{ $limit }}
                            </option>
                        @endforeach

                    </select>

                </div>


                <!-- Buttons -->
                <div class="col-12 mt-3">

                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-funnel"></i>
                        Apply Filters
                    </button>


                    <a href="{{ $clearUrl ?? '#' }}" class="btn btn-outline-secondary w-100">
                        Clear Filters
                    </a>

                </div>


            </div>

        </form>

    </div>

</div>
