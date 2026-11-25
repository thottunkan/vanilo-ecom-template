<div class="card bg-grd-danger order-card">
    <div class="card-body">
        <h6 class="text-white">Total Profit</h6>
        <h2 class="text-end text-white"><i class="feather icon-award float-start"></i><span>&#8377;
                @if (isset($total_profit))
                    {{ $total_profit ?? 0 }}
                @else
                    0
                @endif
            </span></h2>
        <p class="m-b-0">This Month<span class="float-end">&#8377;
                @if (isset($current_month_profit))
                    {{ $current_month_profit ?? 0 }}
                @else
                    0
                    @endif
            </span></p>
    </div>
</div>
