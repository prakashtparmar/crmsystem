<!-- QUICK ORDER (Styled like Edit Order) -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-4 border-b flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                Quick Order
            </h3>
            <p class="text-xs text-gray-500 mt-1">
                Create a new order instantly for this customer
            </p>
        </div>
        <button type="button" id="toggleQuickOrder"
            class="text-xs px-3 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
            Hide
        </button>
    </div>
    <div id="quickOrderSection" class="p-6 space-y-6">
        <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
<!-- Addresses -->
<div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-xl border space-y-4">
    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
        Addresses
    </h3>

    @php
        $defaultText = trim(implode(', ', array_filter([
            $customer->address_line1,
            $customer->address_line2,
            $customer->village,
            $customer->taluka,
            $customer->district,
            $customer->state,
            $customer->pincode,
            $customer->country,
        ])));
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Billing --}}
        <div>
            <label class="block text-xs text-gray-500 mb-2">Billing Address</label>

            <div id="billingList" class="space-y-2">

                @if($defaultText)
                    <label class="flex items-start gap-3 p-3 rounded-lg border cursor-pointer bg-blue-50 dark:bg-gray-800">
                        <input type="radio"
                               name="billing_address_id"
                               value=""
                               class="mt-1 rounded"
                               checked>
                        <div class="text-sm text-gray-700 dark:text-gray-300 leading-snug">
                            <div class="font-medium">Default Address</div>
                            <div class="text-xs text-gray-500">
                                {{ $defaultText }}
                            </div>
                        </div>
                    </label>
                @endif

                @foreach ($customer->addresses as $addr)
                    <label class="flex items-start gap-3 p-3 rounded-lg border cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800">
                        <input type="radio"
                               name="billing_address_id"
                               value="{{ $addr->id }}"
                               class="mt-1 rounded">

                        <div class="text-sm text-gray-700 dark:text-gray-300 leading-snug">
                            <div class="font-medium">
                                {{ $addr->address_line1 }}
                                @if ($addr->address_line2), {{ $addr->address_line2 }} @endif
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $addr->village }}
                                @if ($addr->village && $addr->taluka), @endif
                                {{ $addr->taluka }}
                                @if ($addr->taluka && $addr->district), @endif
                                {{ $addr->district }},
                                {{ $addr->state }} - {{ $addr->pincode }}
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            <button type="button"
                class="mt-3 text-xs px-3 py-1 rounded-md border text-blue-600 hover:bg-blue-50"
                onclick="document.querySelector('.new-billing').classList.toggle('hidden')">
                + Add New Address
            </button>

            <div class="mt-3 hidden new-billing border rounded-xl p-4 bg-white dark:bg-gray-900">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium">Address Line 1</label>
                        <input name="new_billing[address_line1]" class="w-full rounded-xl border px-3 py-2">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium">Address Line 2</label>
                        <input name="new_billing[address_line2]" class="w-full rounded-xl border px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-xs font-medium">Pincode</label>
                        <input name="new_billing[pincode]" id="billing_pincode" class="w-full rounded-xl border px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-xs font-medium">Post Office</label>
                        <input name="new_billing[post_office]" id="billing_post_office" class="w-full rounded-xl border px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-xs font-medium">Village</label>
                        <input name="new_billing[village]" id="billing_village" class="w-full rounded-xl border px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-xs font-medium">Taluka</label>
                        <input name="new_billing[taluka]" id="billing_taluka" class="w-full rounded-xl border px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-xs font-medium">District</label>
                        <input name="new_billing[district]" id="billing_district" class="w-full rounded-xl border px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-xs font-medium">State</label>
                        <input name="new_billing[state]" id="billing_state" class="w-full rounded-xl border px-3 py-2">
                    </div>
                </div>

                <div class="mt-3 text-right">
                    <button type="button" data-type="billing"
                        class="save-address text-xs px-3 py-1 rounded-md border bg-blue-600 text-white">
                        Save Billing Address
                    </button>
                </div>
            </div>
        </div>

        {{-- Shipping --}}
        <div>
            <label class="block text-xs text-gray-500 mb-2">Shipping Address</label>

            <div id="shippingList" class="space-y-2">

                @if($defaultText)
                    <label class="flex items-start gap-3 p-3 rounded-lg border cursor-pointer bg-blue-50 dark:bg-gray-800">
                        <input type="radio"
                               name="shipping_address_id"
                               value=""
                               class="mt-1 rounded"
                               checked>
                        <div class="text-sm text-gray-700 dark:text-gray-300 leading-snug">
                            <div class="font-medium">Default Address</div>
                            <div class="text-xs text-gray-500">
                                {{ $defaultText }}
                            </div>
                        </div>
                    </label>
                @endif

                @foreach ($customer->addresses as $addr)
                    <label class="flex items-start gap-3 p-3 rounded-lg border cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800">
                        <input type="radio"
                               name="shipping_address_id"
                               value="{{ $addr->id }}"
                               class="mt-1 rounded">

                        <div class="text-sm text-gray-700 dark:text-gray-300 leading-snug">
                            <div class="font-medium">
                                {{ $addr->address_line1 }}
                                @if ($addr->address_line2), {{ $addr->address_line2 }} @endif
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $addr->village }}
                                @if ($addr->village && $addr->taluka), @endif
                                {{ $addr->taluka }}
                                @if ($addr->taluka && $addr->district), @endif
                                {{ $addr->district }},
                                {{ $addr->state }} - {{ $addr->pincode }}
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            <label class="inline-flex items-center mt-3 text-xs text-gray-600 dark:text-gray-400">
                <input type="checkbox" name="same_as_billing" value="1" class="mr-2 rounded">
                Same as Billing
            </label>
        </div>

    </div>
</div>


            <!-- Items -->
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300 mb-3">
                    Order Items
                </h3>
                <div id="items" class="space-y-3">
                    <div
                        class="item-row flex flex-col md:flex-row gap-4 p-4 rounded-xl border bg-white dark:bg-gray-900">
                        <!-- Product -->
                        <div class="w-full md:w-2/5">
                            <label class="block text-xs text-gray-500 mb-1">Product</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <input type="text" placeholder="Search name / SKU..."
                                    class="product-search w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-sm px-3 py-2">
                                <select name="items[0][product_id]"
                                    class="product-select w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                    <option value="">Select Product</option>
                                    @foreach ($products as $p)
                                        <option value="{{ $p->id }}" data-price="{{ $p->price ?? 0 }}"
                                            data-tax="{{ $p->gst_percent }}" data-sku="{{ $p->sku }}">
                                            {{ $p->name }} ({{ $p->sku }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Price -->
                        <div class="w-full md:w-1/6">
                            <label class="block text-xs text-gray-500 mb-1">Price</label>
                            <input name="items[0][price]" readonly
                                class="price w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                        </div>
                        <!-- Qty -->
                        <div class="w-full md:w-1/6">
                            <label class="block text-xs text-gray-500 mb-1">Qty</label>
                            <div class="flex items-center">
                                <button type="button"
                                    class="qty-minus px-2 border border-r-0 rounded-l-lg bg-gray-50 dark:bg-gray-700">−</button>
                                <input name="items[0][qty]" value="1"
                                    class="qty w-full text-center border-t border-b border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                <button type="button"
                                    class="qty-plus px-2 border border-l-0 rounded-r-lg bg-gray-50 dark:bg-gray-700">+</button>
                            </div>
                        </div>
                        <!-- Total -->
                        <div class="w-full md:w-1/6">
                            <label class="block text-xs text-gray-500 mb-1">Total</label>
                            <input name="items[0][total]" readonly
                                class="total w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 font-semibold text-blue-600">
                        </div>
                        <input name="items[0][tax]" class="tax hidden">
                        <!-- Actions -->
                        <div class="pt-5 md:pt-0">
                            <button type="button" class="remove-row text-xs text-red-600 hover:underline">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" id="addRow"
                    class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-lg border text-sm">
                    + Add Item
                </button>
            </div>
            <!-- Totals -->
            <div
                class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t bg-gray-50 dark:bg-gray-900 p-4 rounded-xl">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Sub Total</label>
                    <input id="sub_total" name="sub_total" readonly
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-right">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Tax</label>
                    <input id="tax_amount" name="tax_amount" readonly
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-right">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Discount</label>
                    <input id="discount_amount" name="discount_amount" type="number" min="0" step="0.01"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-right">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Grand Total</label>
                    <input id="grand_total" name="grand_total" readonly
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-right font-bold text-blue-600">
                </div>
            </div>
            <!-- Actions -->
            <div class="flex justify-end">
                <x-button type="primary" class="px-8 py-2">
                    {{ __('Place Order') }}
                </x-button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('change', function(e) {
        if (e.target.matches('.address-select-billing')) {
            document.querySelector('.new-billing')
                .classList.toggle('hidden', e.target.value !== 'new');
        }

        if (e.target.matches('.address-select-shipping')) {
            document.querySelector('.new-shipping')
                .classList.toggle('hidden', e.target.value !== 'new');
        }
    });
</script>
<script>
    function initAddressLookup(prefix) {
        const fields = {
            pincode: document.getElementById(prefix + '_pincode'),
            post_office: document.getElementById(prefix + '_post_office'),
            village: document.getElementById(prefix + '_village'),
            taluka: document.getElementById(prefix + '_taluka'),
            district: document.getElementById(prefix + '_district'),
            state: document.getElementById(prefix + '_state'),
        };

        if (!fields.pincode) return;

        Object.entries(fields).forEach(([k, el]) => {
            if (!el) return;
            const dl = document.createElement('datalist');
            dl.id = prefix + '_' + k + '_list';
            document.body.appendChild(dl);
            el.setAttribute('list', dl.id);
        });

        let controller;

        async function fetchData(params = {}) {
            if (controller) controller.abort();
            controller = new AbortController();
            const q = new URLSearchParams(params).toString();
            const res = await fetch(`/address-lookup?${q}`, {
                signal: controller.signal
            });
            return await res.json();
        }

        fields.pincode.addEventListener('input', async () => {
            const pin = fields.pincode.value.replace(/\D/g, '').slice(0, 6);
            fields.pincode.value = pin;
            if (pin.length < 3) return;

            const data = await fetchData({
                pincode: pin
            });

            const dl = document.getElementById(prefix + '_post_office_list');
            dl.innerHTML = '';
            [...new Set(data.map(r => r.post_so_name).filter(Boolean))]
            .forEach(v => {
                const opt = document.createElement('option');
                opt.value = v;
                dl.appendChild(opt);
            });
        });

        ['post_office', 'village', 'taluka', 'district'].forEach(name => {
            if (!fields[name]) return;

            fields[name].addEventListener('input', async () => {
                const data = await fetchData({
                    pincode: fields.pincode.value,
                    post_office: fields.post_office.value,
                    village: fields.village.value,
                    taluka: fields.taluka.value,
                    district: fields.district.value,
                });

                if (data.length === 1) {
                    const r = data[0];
                    fields.post_office.value = r.post_so_name || '';
                    fields.village.value = r.village_name || '';
                    fields.taluka.value = r.taluka_name || '';
                    fields.district.value = r.District_name || '';
                    fields.state.value = r.state_name || '';
                    fields.pincode.value = r.pincode || '';
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        initAddressLookup('billing');
        initAddressLookup('shipping');
    });
</script>
<script>
    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.save-address');
        if (!btn) return;

        e.preventDefault(); // safety

        console.log('Save clicked');

        const type = btn.dataset.type;
        const box = btn.closest(type === 'billing' ? '.new-billing' : '.new-shipping');

        if (!box) {
            console.warn('Box not found');
            return;
        }

        const data = {
            type,
            address_line1: box.querySelector('[name*="address_line1"]')?.value || '',
            address_line2: box.querySelector('[name*="address_line2"]')?.value || '',
            pincode: box.querySelector('[name*="pincode"]')?.value || '',
            post_office: box.querySelector('[name*="post_office"]')?.value || '',
            village: box.querySelector('[name*="village"]')?.value || '',
            taluka: box.querySelector('[name*="taluka"]')?.value || '',
            district: box.querySelector('[name*="district"]')?.value || '',
            state: box.querySelector('[name*="state"]')?.value || '',
        };

        console.log('Payload:', data);

        const customerId = "{{ $customer->id }}";

        let res;
        try {
            res = await fetch(`/customers/${customerId}/addresses`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin', // IMPORTANT for Laravel
                body: JSON.stringify(data)
            });

        } catch (err) {
            alert('Network error');
            console.error(err);
            return;
        }

        console.log('Response status:', res.status);

        if (!res.ok) {
            const text = await res.text();
            console.error(text);
            alert('Server error: ' + res.status);
            return;
        }

        const json = await res.json();
        console.log('Saved:', json);

        const list = document.getElementById(
    type === 'billing' ? 'billingList' : 'shippingList'
);

if (!list) {
    alert('Saved, but list not found');
    return;
}

const label = document.createElement('label');
label.className =
    'flex items-start gap-3 p-3 rounded-lg border cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800';

label.innerHTML = `
    <input type="radio"
           name="${type}_address_id"
           value="${json.id}"
           class="mt-1 rounded"
           checked>

    <div class="text-sm text-gray-700 dark:text-gray-300 leading-snug">
        <div class="font-medium">${json.text}</div>
    </div>
`;

list.appendChild(label);

// Hide form after save
box.classList.add('hidden');

    });
</script>
