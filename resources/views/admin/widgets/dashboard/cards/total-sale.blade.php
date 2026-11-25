<div class="card bg-grd-success order-card">
    <div class="card-body">
        <h6 class="text-white">Total Sales</h6>
        <h2 class="text-end text-white"><i class="feather icon-tag float-start"></i>
            &#8377;<span>
                @if (isset($total_sale))
                    {{ $total_sale ?? 0 }}
                @else
                    0
                @endif
            </span>
        </h2>
        <p class="m-b-0">This Month
            &#8377;<span class="float-end">
                @if (isset($current_month_sale))
                    {{ $current_month_sale ?? 0 }}
                @else
                    0
                @endif
            </span>
        </p>
    </div>
</div>
