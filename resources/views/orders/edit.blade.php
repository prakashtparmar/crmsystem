<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
        <span class="mx-2">›</span>
        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">Orders</a>
        <span class="mx-2">›</span>
        <span class="text-gray-500">Edit</span>
    </div>

    <form action="{{ route('orders.update', $order->id) }}" method="POST" id="orderForm" class="grid grid-cols-1 xl:grid-cols-4 gap-6">
        @csrf
        @method('PUT')

        <div class="xl:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border p-6 space-y-6">

                <!-- Customer -->
                <div>
                    <label class="block text-sm font-medium mb-1">Customer</label>
                    <select name="customer_id" id="customerSelect" class="w-full rounded-lg border">
                        @foreach ($customers as $c)
                            <option value="{{ $c->id }}" @selected($order->customer_id == $c->id)>
                                {{ $c->display_name ?? $c->first_name.' '.$c->last_name }} ({{ $c->mobile }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Addresses -->
                <div id="addressBlock" class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold mb-2">Billing Address</h4>
                        <div id="billingList" class="grid gap-2"></div>
                    </div>

                    <div>
                        <h4 class="text-sm font-semibold mb-2">Shipping Address</h4>
                        <div id="shippingList" class="grid gap-2"></div>
                    </div>

                    <input type="hidden" name="billing_address_id" id="billing_address_id" value="{{ $order->billing_address_id }}">
                    <input type="hidden" name="shipping_address_id" id="shipping_address_id" value="{{ $order->shipping_address_id }}">
                </div>

                <!-- Items -->
                <div id="itemsBlock">
                    <h3 class="text-sm font-semibold uppercase mb-3">Order Items</h3>

                    <div id="items" class="space-y-4">
                        @foreach ($order->items as $i => $item)
                            <div class="item-row flex flex-col md:flex-row gap-4 p-4 rounded-xl border bg-gray-50">
                                <div class="w-full md:w-2/5">
                                    <select name="items[{{ $i }}][product_id]" class="product-select w-full rounded-lg border">
                                        @foreach ($products as $p)
                                            <option value="{{ $p->id }}"
                                                data-price="{{ $p->price }}"
                                                data-tax="{{ $p->gst_percent }}"
                                                data-stock="{{ $p->available_qty ?? 0 }}"
                                                @selected($item->product_id == $p->id)>
                                                {{ $p->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <input class="price w-full md:w-1/6 rounded-lg border" readonly value="{{ $item->price }}">
                                <input class="available w-full md:w-1/6 rounded-lg border" readonly>
                                <input name="items[{{ $i }}][qty]" class="qty w-full md:w-1/6 rounded-lg border" value="{{ $item->qty }}">
                                <input class="total w-full md:w-1/6 rounded-lg border font-semibold" readonly>
                                <button type="button" class="remove-row text-sm text-red-600 mt-6">Remove</button>
                                <input class="tax hidden" data-rate="{{ $item->tax_rate }}">
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="addRow" class="mt-4 px-4 py-2 border rounded-lg">+ Add Item</button>
                </div>

                <div class="flex gap-3">
                    <x-button type="primary">Update Order</x-button>
                    <a href="{{ route('orders.index') }}" class="px-4 py-2 border rounded-md">Cancel</a>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="xl:col-span-1">
            <div class="sticky top-20 bg-white rounded-xl border p-6 space-y-4">
                <input id="sub_total" readonly class="w-full rounded-lg border">
                <input id="tax_amount" readonly class="w-full rounded-lg border">
                <input id="grand_total" readonly class="w-full rounded-lg border font-semibold">
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        let index = {{ $order->items->count() }};

        function recalc() {
            let sub = 0, tax = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const price = parseFloat(row.querySelector('.price').value || 0);
                const qty = parseFloat(row.querySelector('.qty').value || 0);
                const rate = parseFloat(row.querySelector('.tax').dataset.rate || 0);

                const line = price * qty;
                const lineTax = (line * rate) / 100;
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
                row.querySelector('.price').value = opt.dataset.price;
                row.querySelector('.tax').dataset.rate = opt.dataset.tax;
                recalc();
            }
        });

        document.addEventListener('input', e => {
            if (e.target.classList.contains('qty')) recalc();
        });

        document.getElementById('addRow').addEventListener('click', () => {
            const base = document.querySelector('.item-row');
            const clone = base.cloneNode(true);
            clone.querySelectorAll('input,select').forEach(el => {
                el.value = '';
                if (el.name) el.name = el.name.replace(/\d+/, index);
            });
            document.getElementById('items').appendChild(clone);
            index++;
        });

        document.addEventListener('click', e => {
            if (e.target.classList.contains('remove-row')) {
                const rows = document.querySelectorAll('.item-row');
                if (rows.length > 1) {
                    e.target.closest('.item-row').remove();
                    recalc();
                }
            }
        });

        recalc();
    </script>
    @endpush
</x-layouts.app>
