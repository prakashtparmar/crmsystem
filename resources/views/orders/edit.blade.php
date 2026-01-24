<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">Orders</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500">Edit Order</span>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
        <div class="xl:col-span-3">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('orders.update', $order) }}" method="POST" class="space-y-8" id="orderForm">
                    @csrf
                    @method('PUT')

                    <!-- Customer (Locked) -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Customer</label>
                        <select disabled class="w-full rounded-lg border-gray-300 dark:bg-gray-900 bg-gray-100">
                            <option>
                                {{ $order->customer_name }}
                                ({{ $order->customer_email ?? $order->customer_phone }})
                            </option>
                        </select>
                    </div>

                    <!-- Addresses -->
                    <div id="addressBlock" class="space-y-4">

                        <!-- Billing -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="text-sm font-semibold">Billing Address</h4>
                                <button type="button" class="text-xs text-blue-600"
                                    onclick="showNewAddress('billing')">
                                    + Add New
                                </button>
                            </div>
                            <div id="billingList" class="grid gap-2"></div>
                        </div>

                        <!-- Shipping -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="text-sm font-semibold">Shipping Address</h4>
                                <button id="addShippingBtn" type="button" class="text-xs text-blue-600"
                                    onclick="showNewAddress('shipping')">
                                    + Add New
                                </button>
                            </div>

                            <label class="flex items-center gap-2 text-xs mb-2">
                                <input type="checkbox" id="sameAsBilling" name="same_as_billing" value="1">
                                Same as Billing Address
                            </label>

                            <div id="shippingWrapper">
                                <div id="shippingList" class="grid gap-2"></div>
                            </div>
                        </div>

                        <input type="hidden" name="billing_address_id" id="billing_address_id">
                        <input type="hidden" name="shipping_address_id" id="shipping_address_id">

                        <!-- New Billing -->
                        <input type="hidden" name="new_billing[line1]">
                        <input type="hidden" name="new_billing[line2]">
                        <input type="hidden" name="new_billing[city]">
                        <input type="hidden" name="new_billing[state]">
                        <input type="hidden" name="new_billing[pincode]">

                        <!-- New Shipping -->
                        <input type="hidden" name="new_shipping[line1]">
                        <input type="hidden" name="new_shipping[line2]">
                        <input type="hidden" name="new_shipping[city]">
                        <input type="hidden" name="new_shipping[state]">
                        <input type="hidden" name="new_shipping[pincode]">
                    </div>

                    <!-- Order Items -->
                    <div id="itemsBlock">
                        <h3 class="text-sm font-semibold uppercase tracking-wide mb-3">Order Items</h3>

                        <div id="items" class="space-y-4">
                            @foreach ($order->items as $i => $item)
                                <div
                                    class="item-row flex flex-col md:flex-row gap-4 p-4 rounded-xl border bg-gray-50 dark:bg-gray-900">

                                    <div class="w-full md:w-2/5">
                                        <label class="block text-xs mb-1">Product</label>
                                        <select name="items[{{ $i }}][product_id]"
                                            class="product-select w-full rounded-lg border">
                                            <option value="">Select Product</option>
                                            @foreach ($products as $p)
                                                <option value="{{ $p->id }}"
                                                    data-price="{{ $p->price }}"
                                                    data-tax="{{ $p->gst_percent }}"
                                                    data-stock="{{ $p->available_qty ?? 0 }}"
                                                    {{ $p->id == $item->product_id ? 'selected' : '' }}>
                                                    {{ $p->name }} ({{ $p->available_qty ?? 0 }} in stock)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="w-full md:w-1/6">
                                        <label class="block text-xs mb-1">Price</label>
                                        <input readonly class="price w-full rounded-lg border"
                                            value="{{ $item->price }}">
                                    </div>

                                    <div class="w-full md:w-1/6">
                                        <label class="block text-xs mb-1">Available</label>
                                        <input readonly class="available w-full rounded-lg border">
                                    </div>

                                    <div class="w-full md:w-1/6">
                                        <label class="block text-xs mb-1">Qty</label>
                                        <input name="items[{{ $i }}][qty]"
                                            class="qty w-full rounded-lg border"
                                            value="{{ $item->quantity }}">
                                    </div>

                                    <div class="w-full md:w-1/6">
                                        <label class="block text-xs mb-1">Total</label>
                                        <input readonly class="total w-full rounded-lg border font-semibold"
                                            value="{{ number_format($item->total, 2) }}">
                                    </div>

                                    <button type="button" class="remove-row text-sm text-red-600 mt-6">
                                        Remove
                                    </button>

                                    <input class="tax hidden" data-rate="{{ $item->tax_rate }}">
                                </div>
                            @endforeach
                        </div>

                        <button type="button" id="addRow" class="mt-4 px-4 py-2 border rounded-lg">
                            + Add Item
                        </button>
                    </div>

                    <div class="flex gap-3">
                        <x-button type="primary">Update Order</x-button>
                        <a href="{{ route('orders.index') }}" class="px-4 py-2 border rounded-md">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary -->
        <div class="xl:col-span-1">
            <div class="sticky top-20 bg-white dark:bg-gray-800 rounded-xl border p-6 space-y-4">
                <div>
                    <label class="block text-xs mb-1">Sub Total</label>
                    <input id="sub_total" readonly class="w-full rounded-lg border"
                        value="{{ number_format($order->sub_total, 2) }}">
                </div>
                <div>
                    <label class="block text-xs mb-1">Tax</label>
                    <input id="tax_amount" readonly class="w-full rounded-lg border"
                        value="{{ number_format($order->tax_amount, 2) }}">
                </div>
                <div>
                    <label class="block text-xs mb-1">Grand Total</label>
                    <input id="grand_total" readonly class="w-full rounded-lg border font-semibold"
                        value="{{ number_format($order->grand_total, 2) }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Address Modal (same as create) -->
    <div id="addressModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
        <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-xl p-6 space-y-4">
            <div class="flex justify-between items-center">
                <h3 id="modalTitle" class="text-lg font-semibold">Add Address</h3>
                <button type="button" onclick="closeAddressModal()" class="text-gray-500">✕</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <input id="addr_line1" placeholder="Address Line 1" class="rounded-lg border p-2">
                <input id="addr_line2" placeholder="Address Line 2" class="rounded-lg border p-2">
                <input id="addr_city" placeholder="City" class="rounded-lg border p-2">
                <input id="addr_state" placeholder="State" class="rounded-lg border p-2">
                <input id="addr_pin" placeholder="Pincode" class="rounded-lg border p-2">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeAddressModal()" class="px-4 py-2 border rounded-lg">
                    Cancel
                </button>
                <button type="button" onclick="saveAddress()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Save Address
                </button>
            </div>
        </div>
    </div>
@push('scripts')
<script>
    const canOversell = @json(auth()->user()->can('oversell-products'));
    let index = {{ $order->items->count() }};

    function recalc() {
        let sub = 0, tax = 0, error = false;

        document.querySelectorAll('.item-row').forEach(row => {
            const price = parseFloat(row.querySelector('.price').value || 0);
            const qty = parseFloat(row.querySelector('.qty').value || 0);
            const stock = parseFloat(row.querySelector('.available').value || 0);
            const taxRate = parseFloat(row.querySelector('.tax').dataset.rate || 0);

            if (!canOversell && qty > stock) {
                row.querySelector('.qty').classList.add('border-red-500');
                error = true;
            } else {
                row.querySelector('.qty').classList.remove('border-red-500');
            }

            const line = price * qty;
            const lineTax = (line * taxRate) / 100;

            row.querySelector('.total').value = (line + lineTax).toFixed(2);

            sub += line;
            tax += lineTax;
        });

        sub_total.value = sub.toFixed(2);
        tax_amount.value = tax.toFixed(2);
        grand_total.value = (sub + tax).toFixed(2);

        return !error;
    }

    document.addEventListener('change', e => {
        if (e.target.classList.contains('product-select')) {
            const opt = e.target.selectedOptions[0];
            const row = e.target.closest('.item-row');

            row.querySelector('.price').value = opt.dataset.price || 0;
            row.querySelector('.available').value = opt.dataset.stock || 0;
            row.querySelector('.tax').dataset.rate = opt.dataset.tax || 0;
            recalc();
        }

        if (e.target.classList.contains('address-check')) {
            const type = e.target.dataset.type;
            const list = type === 'billing' ? billingList : shippingList;

            list.querySelectorAll('.address-check').forEach(cb => {
                if (cb !== e.target) cb.checked = false;
            });

            const same = document.getElementById('sameAsBilling');
            if (same) same.checked = false;

            if (type === 'billing') {
                billing_address_id.value = e.target.checked ? e.target.dataset.id : '';
            } else {
                shipping_address_id.value = e.target.checked ? e.target.dataset.id : '';
            }
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

    orderForm.addEventListener('submit', e => {
        if (!recalc()) {
            e.preventDefault();
            alert('Quantity exceeds available stock.');
        }
    });

    // Address modal logic (same as create)
    let modalType = null;

    function showNewAddress(type) {
        modalType = type;
        document.getElementById('modalTitle').innerText =
            type === 'billing' ? 'Add Billing Address' : 'Add Shipping Address';
        document.getElementById('addressModal').classList.remove('hidden');
    }

    function closeAddressModal() {
        document.getElementById('addressModal').classList.add('hidden');
        ['addr_line1','addr_line2','addr_city','addr_state','addr_pin']
            .forEach(id => document.getElementById(id).value = '');
    }

    function saveAddress() {
        const formatted = [
            addr_line1.value,
            addr_line2.value,
            addr_city.value,
            addr_state.value,
            addr_pin.value
        ].filter(Boolean).join(', ');

        if (!formatted) {
            alert('Please enter address details.');
            return;
        }

        const box = document.createElement('label');
        box.className = 'flex items-start gap-2 p-3 rounded-lg border cursor-pointer';

        box.innerHTML = `
            <input type="checkbox" class="mt-1 address-check"
                data-id=""
                data-type="${modalType}" checked>
            <span class="text-sm">${formatted}</span>
        `;

        if (modalType === 'billing') {
            billingList.appendChild(box);
            billing_address_id.value = '';

            document.querySelector('[name="new_billing[line1]"]').value = addr_line1.value;
            document.querySelector('[name="new_billing[line2]"]').value = addr_line2.value;
            document.querySelector('[name="new_billing[city]"]').value = addr_city.value;
            document.querySelector('[name="new_billing[state]"]').value = addr_state.value;
            document.querySelector('[name="new_billing[pincode]"]').value = addr_pin.value;
        } else {
            shippingList.appendChild(box);
            shipping_address_id.value = '';

            document.querySelector('[name="new_shipping[line1]"]').value = addr_line1.value;
            document.querySelector('[name="new_shipping[line2]"]').value = addr_line2.value;
            document.querySelector('[name="new_shipping[city]"]').value = addr_city.value;
            document.querySelector('[name="new_shipping[state]"]').value = addr_state.value;
            document.querySelector('[name="new_shipping[pincode]"]').value = addr_pin.value;
        }

        closeAddressModal();
    }

    recalc();
</script>
@endpush

</x-layouts.app>
