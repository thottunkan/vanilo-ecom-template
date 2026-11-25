<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#importExcelModal">
    {{ $btntext }}
</button>
<div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="importExcelModalLabel">{{ $btntext }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="excelFile" class="form-label">{{ $uploadLabel }}</label>
                        <input
                            type="file"
                            class="form-control"
                            id="excelFile"
                            name="file"
                            accept=".xlsx,.xls,.csv,.jpg,.png,.jpeg"
                            required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ $btntext }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>