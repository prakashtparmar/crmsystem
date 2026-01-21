<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#productsTable').DataTable({
            dom: 'lBfrtip',
            pageLength: 10,
            lengthMenu: [
                [5, 10, 50, 100],
                [5, 10, 50, 100]
            ],
            autoWidth: false,
            scrollX: true,
            responsive: false,
            buttons: [{
                extend: 'excelHtml5',
                text: 'Export Excel'
            }],
        });

        $('#selectAll').on('change', function() {
            $('.row-checkbox').prop('checked', this.checked);
        });
    });
</script>
