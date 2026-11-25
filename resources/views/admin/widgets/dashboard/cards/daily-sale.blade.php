<div class="card statistics-card-1">
    <div class="card-body">
        <img src="../assets/images/widget/img-status-4.svg" alt="img" class="img-fluid img-bg" />
        <div class="d-flex align-items-center justify-content-between mb-3 drp-div">
            <h6 class="mb-0">Daily Sales</h6>
        </div>
        <div class="d-flex align-items-center mt-3">
            <h3 class="f-w-300 d-flex align-items-center m-b-0">&#8377;
                @if (isset($daily_sale))
                    {{ $daily_sale ?? 0 }}
                @else
                    0
                @endif
            </h3>
            <span class="badge bg-light-success ms-2">36%</span>
        </div>
        <p class="text-muted mb-2 text-sm mt-3">You made an extra 35,000 this daily</p>
        <div class="progress" style="height: 7px">
            <div class="progress-bar bg-brand-color-1" role="progressbar" style="width: 75%" aria-valuenow="75"
                aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>
