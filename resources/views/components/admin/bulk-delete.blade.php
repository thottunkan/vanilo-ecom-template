@props([
    'route',
    'selector' => '',
    'buttonText' => 'Bulk delete'
])

<meta name="csrf-token" content="{{ csrf_token() }}">

<select name="" id="actions">
    <option value="" selected>Actions</option>
    <option value="select_all">Select All</option>
    <option value="unselect_all">Unselect All</option>
</select>

&nbsp;
<button type="button" class="btn btn-sm btn-danger bulk-delete-btn">
    {{-- {{ $buttonText }} --}}
    Delete
</button>


<script>
    $(document).ready(function() {

        $(document).on('change', '#actions', function() {
            // const unchecked = $('{{ $selector }}:not(:checked)');

            // // log values (optional)
            // console.log(unchecked.map(function() {
            //     return $(this).val();
            // }).get());

            // // make them checked
            // unchecked.prop('checked', true);

            const action = $(this).val();
            const checkboxes = $('{{ $selector }}');

            if (action === 'select_all') {
                checkboxes.prop('checked', true);
            } else if (action === 'unselect_all') {
                checkboxes.prop('checked', false);
            }

            // optional: reset dropdown back to "Actions"
            $(this).val('');
        });

        $(document).on('click', '.bulk-delete-btn', function() {

            const selectedIds = $('{{ $selector }}:checked').map(function() {
                return $(this).val();
            }).get();
            console.log('selected ids',selectedIds);
            if (selectedIds.length === 0) {
                alert('No items selected');
                return;
            }

            if (!confirm('Are you sure you want to delete selected items?')) {
                return;
            }

            $.ajax({
                url: "/{{ $route }}",
                type: 'DELETE',
                data: {
                    ids: selectedIds,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert(response.message ?? 'Deleted successfully');
                    location.reload();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Something went wrong');
                }
            });

        });

    });
</script>
