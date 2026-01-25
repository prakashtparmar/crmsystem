<x-layouts.app>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                Edit Order {{ $order->order_code }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Update items, quantities, and addresses
            </p>
        </div>
        <a href="{{ route('orders.show', $order) }}"
            class="px-4 py-2 border rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('orders.update', $order) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Addresses -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Billing Address</label>
                <textarea name="billing_address"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                    rows="3">{{ old('billing_address', $order->billing_address) }}</textarea>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Shipping Address</label>
                <textarea name="shipping_address"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                    rows="3">{{ old('shipping_address', $order->shipping_address) }}</textarea>
            </div>
        </div>

        <!-- Items -->
        <div>
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300 mb-3">
                Order Items
            </h3>

            <div id="items" class="space-y-3">
                @foreach ($order->items as $i => $item)
                    <div class="item-row flex flex-col md:flex-row gap-4 p-4 rounded-xl border">

                        <!-- Product -->
                        <div class="w-full md:w-2/5">
                            <label class="block text-xs text-gray-500 mb-1">Product</label>
                            <select name="items[{{ $i }}][product_id]"
                                class="product-select w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                <option value="">Select Product</option>
                                @foreach ($products as $p)
                                    <option value="{{ $p->id }}"
                                        data-price="{{ $p->price }}"
                                        data-tax="{{ $p->gst_percent }}"
                                        @selected($p->id == $item->product_id)>
                                        {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price -->
                        <div class="w-full md:w-1/6">
                            <label class="block text-xs text-gray-500 mb-1">Price</label>
                            <input class="price w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                                value="{{ $item->price }}" readonly>
                        </div>

                        <!-- Qty -->
                        <div class="w-full md:w-1/6">
                            <label class="block text-xs text-gray-500 mb-1">Qty</label>
                            <div class="flex items-center">
                                <button type="button"
                                    class="qty-down px-2 border border-r-0 rounded-l-lg bg-gray-50 dark:bg-gray-700">▼</button>

                                <input
                                    type="number"
                                    min="1"
                                    step="1"
                                    name="items[{{ $i }}][qty]"
                                    class="qty w-full text-center border-t border-b border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                                    value="{{ (int) $item->quantity }}">

                                <button type="button"
                                    class="qty-up px-2 border border-l-0 rounded-r-lg bg-gray-50 dark:bg-gray-700">▲</button>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="w-full md:w-1/6">
                            <label class="block text-xs text-gray-500 mb-1">Total</label>
                            <input class="total w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 font-semibold"
                                value="{{ number_format($item->total, 2) }}" readonly>
                        </div>

                        <input class="tax hidden" data-rate="{{ $item->tax_rate }}">

                        <div class="pt-5 md:pt-0">
                            <button type="button" class="remove-row text-xs text-red-600 hover:underline">
                                Remove
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="addRow"
                class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-lg border text-sm">
                + Add Item
            </button>
        </div>

        <!-- Totals -->
        <div class="flex flex-col md:flex-row gap-4 pt-4 border-t">
            <input id="sub_total" name="sub_total" readonly
                class="w-32 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-right"
                value="{{ $order->sub_total }}">
            <input id="tax_amount" name="tax_amount" readonly
                class="w-32 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-right"
                value="{{ $order->tax_amount }}">
            <input id="grand_total" name="grand_total" readonly
                class="w-40 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-right font-bold text-blue-600"
                value="{{ $order->grand_total }}">
        </div>

        <div class="flex justify-end">
            <x-button type="primary" class="px-8 py-2">
                Update Order
            </x-button>
        </div>
    </form>

    @push('scripts')
        <script>
            let index = {{ $order->items->count() }};

            function recalc() {
                let sub = 0, tax = 0;

                document.querySelectorAll('.item-row').forEach(row => {
                    const price = parseFloat(row.querySelector('.price').value || 0);
                    const qty = parseInt(row.querySelector('.qty').value || 0);
                    const taxRate = parseFloat(row.querySelector('.tax').dataset.rate || 0);

                    const line = price * qty;
                    const lineTax = (line * taxRate) / 100;

                    row.querySelector('.total').value = (line + lineTax).toFixed(2);

                    sub += line;
                    tax += lineTax;
                });

                sub_total.value = sub.toFixed(2);
                tax_amount.value = tax.toFixed(2);
                grand_total.value = (sub + tax).toFixed(2);
            }

            document.addEventListener('change', e => {
                if (e.target.classList.contains('product-select')) {
                    const opt = e.target.selectedOptions[0];
                    const row = e.target.closest('.item-row');

                    row.querySelector('.price').value = opt.dataset.price || 0;
                    row.querySelector('.tax').dataset.rate = opt.dataset.tax || 0;

                    recalc();
                }
            });

            document.addEventListener('click', e => {
                if (e.target.classList.contains('qty-up')) {
                    const input = e.target.closest('.item-row').querySelector('.qty');
                    input.value = parseInt(input.value || 1) + 1;
                    recalc();
                }

                if (e.target.classList.contains('qty-down')) {
                    const input = e.target.closest('.item-row').querySelector('.qty');
                    input.value = Math.max(1, parseInt(input.value || 1) - 1);
                    recalc();
                }

                if (e.target.classList.contains('remove-row')) {
                    const rows = document.querySelectorAll('.item-row');
                    if (rows.length > 1) {
                        e.target.closest('.item-row').remove();
                        recalc();
                    }
                }
            });

            document.addEventListener('input', e => {
                if (e.target.classList.contains('qty')) {
                    e.target.value = Math.max(1, parseInt(e.target.value || 1));
                    recalc();
                }
            });

            document.getElementById('addRow').addEventListener('click', () => {
                const base = document.querySelector('.item-row');
                const clone = base.cloneNode(true);

                clone.querySelectorAll('input,select').forEach(el => {
                    el.value = el.classList.contains('qty') ? 1 : '';
                    if (el.name) el.name = el.name.replace(/\d+/, index);
                });

                clone.querySelector('.tax').dataset.rate = 0;

                items.appendChild(clone);
                index++;
            });

            document.addEventListener('DOMContentLoaded', recalc);
        </script>
    @endpush
</x-layouts.app>
