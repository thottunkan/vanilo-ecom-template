<div class="card bg-grd-primary order-card">
    <div class="card-body">
        <h6 class="text-white">Orders Received</h6>
        <h2 class="text-end text-white"><i class="feather icon-shopping-cart float-start"></i>
            &#8377;<span>
                @if (isset($received_orders))
                    {{ $received_orders ?? 0 }}
                @else
                    0
                @endif
            </span>
        </h2>
        <p class="m-b-0">Completed Orders
            &#8377;<span class="float-end">
                @if (isset($completed_orders))
                    {{ $completed_orders ?? 0 }}
                @else
                    0
                @endif
            </span>
        </p>
    </div>
</div>
