<div class="card statistics-card-1 bg-brand-color-1">
    <div class="card-body">
        <img src="../assets/images/widget/img-status-6.svg" alt="img" class="img-fluid img-bg" />
        <div class="d-flex align-items-center justify-content-between mb-3 drp-div">
            <h6 class="mb-0 text-white">Yearly Sales</h6>
        </div>
        <div class="d-flex align-items-center mt-3">
            <h3 class="text-white f-w-300 d-flex align-items-center m-b-0">&#8377;
                @if (isset($yearly_sale))
                    {{ $yearly_sale ?? 0 }}
                @else
                    0
                @endif
            </h3>
        </div>
        <p class="text-white text-opacity-75 mb-2 text-sm mt-3">You made an extra 35,000 this Daily</p>
        <div class="progress" style="height: 7px">
            <div class="progress-bar bg-brand-color-3" role="progressbar" style="width: 75%" aria-valuenow="75"
                aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>
