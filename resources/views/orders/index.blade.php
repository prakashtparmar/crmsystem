<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Orders') }}</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Orders') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Manage all customer orders') }}
            </p>
        </div>

        @can('orders.create')
            <a href="{{ route('orders.create') }}">
                <x-button type="primary">
                    + {{ __('New Order') }}
                </x-button>
            </a>
        @endcan
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 border border-red-200">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @can('orders.view')
        @include('orders.partials.table', ['orders' => $orders])
    @else
        <div class="p-6 rounded-lg border border-yellow-200 bg-yellow-50 text-yellow-800">
            You do not have permission to view orders.
        </div>
    @endcan

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

        <script>
document.addEventListener('DOMContentLoaded', function() {
    if (!document.getElementById('ordersTable')) return;

    const table = $('#ordersTable').DataTable({
        dom: 'lBfrtip',
        pageLength: 10,
        lengthMenu: [
            [5, 10, 50, 100],
            [5, 10, 50, 100]
        ],
        autoWidth: false,
        scrollX: true,
        scrollCollapse: true,
        responsive: false,
        buttons: [{
            extend: 'excelHtml5',
            text: 'Export Excel'
        }],
        initComplete: function() {
            buildColGroup();
            buildColumnToggles(this.api());
        },
        drawCallback: function() {
            this.api().columns.adjust();
        }
    });

    $('#selectAll').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
    });

    function buildColGroup() {
        const $table = $('#ordersTable');
        const $firstRow = $table.find('tbody tr:first td');

        if ($firstRow.length) {
            let colgroup = '<colgroup>';
            $firstRow.each(function() {
                colgroup += `<col style="width:${$(this).outerWidth()}px">`;
            });
            colgroup += '</colgroup>';

            $table.find('colgroup').remove();
            $table.prepend(colgroup);
        }
    }

    function buildColumnToggles(api) {
    const $wrap = $('#columnToggles');
    $wrap.empty();

    const storageKey = 'orders_table_columns';
    const saved = JSON.parse(localStorage.getItem(storageKey) || '{}');

    // Master toggle
    const $master = $(`
        <label class="flex items-center gap-1 font-semibold mr-4">
            <input type="checkbox" id="toggleAllCols">
            <span>All</span>
        </label>
    `);

    $wrap.append($master);

    let checkboxes = [];

    api.columns().every(function(index) {
        const header = $(this.header()).text().trim();
        if (!header) return; // skip checkbox column

        const isVisible = saved[index] !== undefined ? saved[index] : true;

        // Apply saved state
        api.column(index).visible(isVisible, false);

        const $label = $(`
            <label class="flex items-center gap-1 cursor-pointer">
                <input type="checkbox" data-col="${index}">
                <span>${header}</span>
            </label>
        `);

        const $cb = $label.find('input');
        $cb.prop('checked', isVisible);

        $cb.on('change', function() {
            const idx = $(this).data('col');
            api.column(idx).visible(this.checked, false);

            saved[idx] = this.checked;
            localStorage.setItem(storageKey, JSON.stringify(saved));

            updateMaster();
            api.columns.adjust().draw(false);
        });

        checkboxes.push($cb);
        $wrap.append($label);
    });

    function updateMaster() {
        const allChecked = checkboxes.every(cb => cb.prop('checked'));
        const noneChecked = checkboxes.every(cb => !cb.prop('checked'));

        $('#toggleAllCols')
            .prop('checked', allChecked)
            .prop('indeterminate', !allChecked && !noneChecked);
    }

    // Master behavior
    $('#toggleAllCols').on('change', function() {
        const checked = this.checked;

        checkboxes.forEach($cb => {
            const idx = $cb.data('col');
            $cb.prop('checked', checked);
            api.column(idx).visible(checked, false);
            saved[idx] = checked;
        });

        localStorage.setItem(storageKey, JSON.stringify(saved));
        api.columns.adjust().draw(false);
    });

    updateMaster();
    api.columns.adjust().draw(false);
}

});
</script>


    @endpush
</x-layouts.app>
