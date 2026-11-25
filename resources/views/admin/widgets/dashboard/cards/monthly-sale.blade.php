
<div class="card statistics-card-1">
    <div class="card-body">
        <img src="../assets/images/widget/img-status-5.svg" alt="img" class="img-fluid img-bg" />
        <div class="d-flex align-items-center justify-content-between mb-3 drp-div">
            <h6 class="mb-0">Monthly Sales</h6>
        </div>
        <div class="d-flex align-items-center mt-3">
            <h3 class="f-w-300 d-flex align-items-center m-b-0">&#8377;
                @if (isset($monthly_sale))
                    {{ $monthly_sale }}
                @else
                    0
                @endif
            </h3>
            <span class="badge bg-light-primary ms-2">20%</span>
        </div>
        <p class="text-muted mb-2 text-sm mt-3">You made an extra 35,000 this Monthly</p>
        <div class="progress" style="height: 7px">
            <div class="progress-bar bg-brand-color-3" role="progressbar" style="width: 75%" aria-valuenow="75"
                aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>
