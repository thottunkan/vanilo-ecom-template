<div class="card bg-grd-warning order-card">
    <div class="card-body">
        <h6 class="text-white">Revenue</h6>
        <h2 class="text-end text-white"><i class="feather icon-repeat float-start"></i>
            <span>&#8377;
                @if (isset($total_revenue))
                    {{ $total_revenue ?? 0 }}
                @else
                    0
                @endif
            </span>
        </h2>
        <p class="m-b-0">This Month<span class="float-end">&#8377;
                @if (isset($current_month_revenue))
                    {{ $current_month_revenue ?? 0 }}
                @else
                    0
                @endif
            </span></p>
    </div>
</div>
