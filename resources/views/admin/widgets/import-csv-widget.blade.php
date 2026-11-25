<a class="btn btn-primary btn-sm" data-bs-toggle="modal" href="#csvmodaltoggle" role="button">Import CSV</a>

<div class="modal fade" id="csvmodaltoggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="csvmodaltoggle">Import file</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="{{ route('admin.import-from-csv.import-from-csv') }}" method="POST">
                @csrf
                <input type="file" name="import_form_csv" class="form-control" id="">
                <br>
                <button type="submit" class="btn btn-sm btn-primary form-control">Upload</button>
              </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal"
                    data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

