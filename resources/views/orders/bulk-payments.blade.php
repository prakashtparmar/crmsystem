<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
        <span class="mx-2">›</span>
        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">Orders</a>
        <span class="mx-2">›</span>
        <span>Bulk Payments</span>
    </div>

    <div class="max-w-4xl bg-white dark:bg-gray-800 border rounded-xl p-6 space-y-4">
        <h1 class="text-lg font-bold">Bulk Payment Upload</h1>

        <p class="text-sm text-gray-500">
            Columns:
            <code class="text-xs">order_code | amount | method | paid_at | reference</code>
        </p>

        <!-- Download Sample -->
        <div class="p-3 rounded-lg border bg-gray-50 dark:bg-gray-900">
            <a href="{{ asset('samples/bulk_payment_sample.csv') }}" download
               class="inline-block px-3 py-2 text-xs rounded-md border bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700">
                Download Sample File
            </a>
        </div>

        @if (session('success'))
            <div class="p-2 rounded bg-green-100 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-2 rounded bg-red-100 text-red-700 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form id="bulkForm"
              action="{{ route('orders.payments.bulk.process') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-3">
            @csrf

            <input type="file" id="csvFile" name="file"
                   class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900"
                   accept=".csv" required>

            <!-- Preview Table -->
            <div id="previewWrap" class="hidden">
                <div class="overflow-x-auto border rounded-lg mt-4">
                    <table class="min-w-full text-xs">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-2 py-1">Use</th>
                                <th class="px-2 py-1">Order Code</th>
                                <th class="px-2 py-1">Amount</th>
                                <th class="px-2 py-1">Method</th>
                                <th class="px-2 py-1">Paid At</th>
                                <th class="px-2 py-1">Reference</th>
                            </tr>
                        </thead>
                        <tbody id="previewBody" class="divide-y"></tbody>
                    </table>
                </div>

                <x-button type="primary" class="mt-3">
                    Confirm & Process Selected Rows
                </x-button>
            </div>
        </form>
    </div>

    <script>
    const fileInput = document.getElementById('csvFile');
    const wrap = document.getElementById('previewWrap');
    const body = document.getElementById('previewBody');

    function parseCSVLine(line) {
        const result = [];
        let current = '';
        let inQuotes = false;

        for (let i = 0; i < line.length; i++) {
            const char = line[i];

            if (char === '"' && line[i + 1] === '"') {
                current += '"';
                i++;
            } else if (char === '"') {
                inQuotes = !inQuotes;
            } else if (char === ',' && !inQuotes) {
                result.push(current);
                current = '';
            } else {
                current += char;
            }
        }

        result.push(current);
        return result.map(v => v.trim());
    }

    function normalizeAmount(val) {
        if (!val) return '';
        return val.replace(/,/g, '').trim(); // 2,457.00 -> 2457.00
    }

    fileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const lines = e.target.result.split(/\r?\n/).filter(l => l.trim() !== '');
            body.innerHTML = '';

            let rowIndex = 0;

            lines.slice(1).forEach((line) => {
                const cols = parseCSVLine(line);

                const order = cols[0] ?? '';
                const amountRaw = cols[1] ?? '';
                const method = cols[2] ?? '';
                const paidAt = cols[3] ?? '';
                const ref = cols[4] ?? '';

                if (!order && !amountRaw) return;

                const amount = normalizeAmount(amountRaw);

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-2 py-1 text-center">
                        <input type="checkbox" name="rows[${rowIndex}][use]" value="1" checked>
                    </td>
                    <td class="px-2 py-1">
                        ${order}
                        <input type="hidden" name="rows[${rowIndex}][order_code]" value="${order}">
                    </td>
                    <td class="px-2 py-1 text-right">
                        ${amount}
                        <input type="hidden" name="rows[${rowIndex}][amount]" value="${amount}">
                    </td>
                    <td class="px-2 py-1">
                        ${method}
                        <input type="hidden" name="rows[${rowIndex}][method]" value="${method}">
                    </td>
                    <td class="px-2 py-1">
                        ${paidAt}
                        <input type="hidden" name="rows[${rowIndex}][paid_at]" value="${paidAt}">
                    </td>
                    <td class="px-2 py-1">
                        ${ref}
                        <input type="hidden" name="rows[${rowIndex}][reference]" value="${ref}">
                    </td>
                `;

                body.appendChild(tr);
                rowIndex++;
            });

            if (rowIndex > 0) {
                wrap.classList.remove('hidden');
            }
        };

        reader.readAsText(file);
    });
</script>

</x-layouts.app>
