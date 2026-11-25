<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .dropdown{
        width: 100px;
        height: 31px;
    }
</style>
<select class="bulk-action-select dropdown">
    <option value="" selected>Actions</option>
    <option value="select_all">Select All</option>
    <option value="unselect_all">Unselect All</option>
</select>

&nbsp;

<button type="button" class="btn btn-sm btn-danger bulk-delete-btn">
    {{ $buttonText ?? 'Delete' }}
</button>


<script>
$(document).ready(function () {

    const selector = @json($selector ?? '');
    const route = @json($route ?? '');


    $(document).on('change', '.bulk-action-select', function () {

        const action = $(this).val();
        const checkboxes = $(selector);

        if (action === 'select_all') {
            checkboxes.prop('checked', true);
        }

        if (action === 'unselect_all') {
            checkboxes.prop('checked', false);
        }

        $(this).val('');
    });


    $(document).on('click', '.bulk-delete-btn', function () {

        const selectedIds = $(selector + ':checked')
            .map(function () {
                return $(this).val();
            })
            .get();


        if (selectedIds.length === 0) {
            alert('No items selected');
            return;
        }


        if (!confirm('Are you sure you want to delete selected items?')) {
            return;
        }


        $.ajax({
            url: route,
            type: 'DELETE',
            data: {
                ids: selectedIds,
                _token: $('meta[name="csrf-token"]').attr('content')
            },

            success: function (response) {
                alert(response.message ?? 'Deleted successfully');
                location.reload();
            },

            error: function (xhr) {
                console.error(xhr.responseText);
                alert('Something went wrong');
            }
        });

    });

});
</script>