<a class="btn btn-primary btn-sm" data-bs-toggle="modal" href="#excelmodaltoggle" role="button">Import Excel</a>

<div class="modal fade" id="excelmodaltoggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="excelmodaltoggle">Import file</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.import-from-excel.import-from-excel') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <input type="file" name="import_form_excel" class="form-control" accept=".xlsx,.xls,.csv"
                            required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Upload
                    </button>
                </form>

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal"
                    data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
