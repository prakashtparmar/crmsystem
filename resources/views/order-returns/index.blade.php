<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Order Returns') }}</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                {{ $showTrashed ? __('Deleted Order Returns') : __('Order Returns') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ $showTrashed ? __('View and restore deleted returns') : __('Manage all order returns') }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('order-returns.index', ['trash' => $showTrashed ? 0 : 1]) }}"
                class="px-3 py-2 rounded-md border text-sm">
                {{ $showTrashed ? 'View Active' : 'View Deleted' }}
            </a>
        </div>
    </div>

    <div class="p-6">
        <form id="bulkForm" action="{{ route('order-returns.bulkAction') }}" method="POST">
            @csrf

            <!-- Bulk Actions -->
            <div class="mb-3 flex items-center gap-2">
                <select name="action" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm">
                    <option value="">Bulk Action</option>

                    @if ($showTrashed)
                        <option value="restore">Restore</option>
                    @else
                        <option value="approve">Approve</option>
                        <option value="reject">Reject</option>
                        <option value="refund">Refund</option>
                        <option value="delete">Delete</option>
                    @endif
                </select>

                <button type="submit" onclick="return confirmBulkAction();"
                    class="px-3 py-1.5 rounded-md
                           border border-gray-300
                           bg-white text-gray-800 text-sm
                           hover:bg-gray-100
                           dark:bg-gray-900 dark:text-gray-100
                           dark:border-gray-700 dark:hover:bg-gray-800">
                    Apply
                </button>
            </div>

            <div
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-4 overflow-visible">
                <table id="returnsTable" class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-3 py-2 text-center w-10">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th class="px-3 py-2">ID</th>
                            <th class="px-3 py-2">Return No</th>
                            <th class="px-3 py-2">Order</th>
                            <th class="px-3 py-2">Customer</th>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2">Refund</th>
                            <th class="px-3 py-2">Items</th>
                            <th class="px-3 py-2">Reason</th>
                            <th class="px-3 py-2">Created</th>
                            <th class="px-3 py-2 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($returns as $return)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                <td class="px-3 py-2 text-center">
                                    <input type="checkbox" class="row-checkbox" name="ids[]"
                                        value="{{ $return->id }}">
                                </td>

                                <td class="px-3 py-2 text-gray-500">{{ $return->id }}</td>
                                <td class="px-3 py-2 font-medium">{{ $return->return_number }}</td>

                                <td class="px-3 py-2 text-indigo-600">
                                    #{{ $return->order_id }}
                                </td>

                                <td class="px-3 py-2">
                                    {{ $return->order->customer->name ?? '—' }}
                                </td>

                                <td class="px-3 py-2">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full
                                        {{ $return->status === 'approved'
                                            ? 'bg-green-100 text-green-700'
                                            : ($return->status === 'rejected'
                                                ? 'bg-red-100 text-red-700'
                                                : ($return->status === 'refunded'
                                                    ? 'bg-indigo-100 text-indigo-700'
                                                    : 'bg-yellow-100 text-yellow-700')) }}">

                                        {{ ucfirst($return->status) }}
                                    </span>
                                </td>

                                <td class="px-3 py-2 font-semibold">
    ₹ {{ number_format($return->refund_amount, 2) }}
</td>

<td class="px-3 py-2 text-xs text-gray-600 dark:text-gray-300">
    @if ($return->items->count())
        <ul class="space-y-1">
            @foreach ($return->items as $ri)
                <li class="flex justify-between gap-2">
                    <span class="truncate">
                        {{ $ri->orderItem->product_name ?? '—' }}
                    </span>
                    <span class="whitespace-nowrap">
                        × {{ $ri->qty }}
                    </span>
                </li>
            @endforeach
        </ul>
    @else
        —
    @endif
</td>

<td class="px-3 py-2 text-gray-500">
{{ \Illuminate\Support\Str::limit($return->reason, 40) ?? '—' }}
</td>


                                <td class="px-3 py-2 text-gray-500">
                                    {{ $return->created_at->format('d M Y') }}
                                </td>

                                <td class="px-3 py-2 text-right">
                                    @if ($showTrashed)
                                        <button type="submit"
                                            formaction="{{ route('order-returns.restore', $return->id) }}"
                                            formmethod="POST"
                                            class="text-green-600 hover:underline"
                                            onclick="return confirm('Restore this return?')">
                                            Restore
                                        </button>
                                    @else
                                        <a href="{{ route('order-returns.edit', $return) }}"
                                            class="text-indigo-600 hover:underline">Edit</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('#returnsTable').DataTable({
                    pageLength: 10,
                    scrollX: true,
                });

                $('#selectAll').on('change', function() {
                    $('.row-checkbox').prop('checked', this.checked);
                });
            });

            function confirmBulkAction() {
                const action = document.querySelector('select[name="action"]').value;
                const checked = document.querySelectorAll('.row-checkbox:checked').length;

                if (!action) {
                    alert('Please select a bulk action.');
                    return false;
                }

                if (checked === 0) {
                    alert('Please select at least one record.');
                    return false;
                }

                if (action === 'delete') {
                    return confirm('Are you sure you want to delete selected returns?');
                }

                if (action === 'restore') {
                    return confirm('Restore selected returns?');
                }

                return true;
            }
        </script>
    @endpush
</x-layouts.app>
