<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-4 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('customers.index') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Customers') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Order Console') }}</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                {{ $customer->display_name ?? $customer->first_name . ' ' . $customer->last_name }}
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Order Placement Console
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('customers.edit', $customer) }}"
               class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm
                      hover:bg-blue-700 shadow-sm">
                Edit Customer
            </a>
            <a href="{{ route('customers.index') }}"
               class="inline-flex items-center px-4 py-2 rounded-lg border text-sm
                      text-gray-700 dark:text-gray-300
                      hover:bg-gray-50 dark:hover:bg-gray-700">
                Back
            </a>
        </div>
    </div>

    <!-- MAIN CONSOLE GRID -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

        <!-- LEFT: CUSTOMER CONTEXT -->
        <aside class="xl:col-span-3 space-y-4">

            <!-- Customer Summary Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                        Customer
                    </h3>
                    <button type="button" id="toggleCustomerInfo"
                        class="text-[11px] px-2 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Hide
                    </button>
                </div>

                <div id="customerDetails" class="space-y-4 text-sm">
                    <div>
                        <div class="text-gray-500 text-xs">Customer Code</div>
                        <div class="font-semibold">{{ $customer->customer_code }}</div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <div class="text-gray-500 text-xs">Mobile</div>
                            <div class="font-medium">{{ $customer->mobile }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500 text-xs">Type</div>
                            <div class="font-medium capitalize">{{ $customer->type }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <div class="text-gray-500 text-xs">Credit Limit</div>
                            <div class="font-medium">{{ number_format($customer->credit_limit, 2) }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500 text-xs">Outstanding</div>
                            <div class="font-medium">{{ number_format($customer->outstanding_balance, 2) }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <div class="text-gray-500 text-xs">Status</div>
                            <div class="font-medium">{{ $customer->is_active ? 'Active' : 'Inactive' }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500 text-xs">Orders</div>
                            <div class="font-medium">{{ $customer->orders_count }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </aside>

        <!-- CENTER: ORDER BUILDER (Quick Order goes here in Part 2) -->
        <main class="xl:col-span-6 space-y-6">
<!-- ORDER BUILDER -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

    <!-- Header -->
    <div class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <div>
            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Order Cart</h3>
            <p class="text-[11px] text-gray-500">Build the order for this customer</p>
        </div>
        <button type="button" id="toggleQuickOrder"
            class="text-[11px] px-2 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
            Hide
        </button>
    </div>

    <div id="quickOrderSection" class="p-4 space-y-4">

        <form action="{{ route('orders.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="customer_id" value="{{ $customer->id }}">

            <!-- Items -->
            <div>
                <div id="items" class="space-y-3">

                    <div
                        class="item-row grid grid-cols-1 md:grid-cols-12 gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">

                        <!-- Product -->
                        <div class="md:col-span-5">
                            <label class="block text-[11px] text-gray-500 mb-1">Product</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <input type="text" placeholder="Search name / SKU..."
                                    class="product-search w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-xs px-2 py-1.5 focus:ring-2 focus:ring-blue-500">

                                <select name="items[0][product_id]"
                                    class="product-select w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-xs focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Product</option>
                                    @foreach ($products as $p)
                                        <option value="{{ $p->id }}"
                                            data-price="{{ $p->price ?? 0 }}"
                                            data-tax="{{ $p->gst_percent }}"
                                            data-sku="{{ $p->sku }}">
                                            {{ $p->name }} ({{ $p->sku }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="md:col-span-2">
                            <label class="block text-[11px] text-gray-500 mb-1">Price</label>
                            <input name="items[0][price]" readonly
                                class="price w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-xs font-medium px-2 py-1.5">
                        </div>

                        <!-- Qty -->
                        <div class="md:col-span-2">
                            <label class="block text-[11px] text-gray-500 mb-1">Qty</label>
                            <div class="flex items-center">
                                <button type="button"
                                    class="qty-minus px-2 py-1.5 border border-gray-300 dark:border-gray-700 rounded-l-lg bg-white dark:bg-gray-800 text-xs">
                                    −
                                </button>
                                <input name="items[0][qty]" value="1" inputmode="numeric"
                                    class="qty w-full text-center border-t border-b border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-xs">
                                <button type="button"
                                    class="qty-plus px-2 py-1.5 border border-gray-300 dark:border-gray-700 rounded-r-lg bg-white dark:bg-gray-800 text-xs">
                                    +
                                </button>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="md:col-span-2">
                            <label class="block text-[11px] text-gray-500 mb-1">Total</label>
                            <input name="items[0][total]" readonly
                                class="total w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-xs font-semibold text-blue-600 px-2 py-1.5">
                        </div>

                        <!-- Actions -->
                        <div class="md:col-span-1 flex md:flex-col items-end justify-end gap-1">
                            <button type="button" class="view-product text-[11px] text-blue-600 hover:underline">
                                View
                            </button>
                            <button type="button" class="remove-row text-[11px] text-red-600 hover:underline">
                                Remove
                            </button>
                        </div>

                        <input name="items[0][tax]" class="tax hidden">
                    </div>
                </div>

                <button type="button" id="addRow"
                    class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border text-xs font-medium
                           text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    + Add item
                </button>
            </div>

            <!-- Summary -->
            <div class="flex items-center justify-between gap-3 pt-3 border-t">
                <div class="flex items-center gap-4 text-xs">
                    <div>
                        <div class="text-gray-500">Sub</div>
                        <input id="sub_total" name="sub_total" readonly
                            class="w-20 text-right rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 px-1 py-0.5">
                    </div>
                    <div>
                        <div class="text-gray-500">Tax</div>
                        <input id="tax_amount" name="tax_amount" readonly
                            class="w-20 text-right rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 px-1 py-0.5">
                    </div>
                    <div>
                        <div class="text-gray-700 dark:text-gray-300 font-semibold">Grand</div>
                        <input id="grand_total" name="grand_total" readonly
                            class="w-24 text-right rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 font-bold text-blue-600 px-1 py-0.5">
                    </div>
                </div>

                <x-button type="primary" class="px-6 py-2 text-xs shadow-md">
                    {{ __('Place Order') }}
                </x-button>
            </div>

        </form>
    </div>
</div>
        <!-- RIGHT: PRODUCT CATALOG -->
        <aside class="xl:col-span-3 space-y-4">

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                            Products
                        </h3>
                        <p class="text-[11px] text-gray-400">Pick & add to cart</p>
                    </div>
                    <button type="button" id="toggleProducts"
                        class="text-[11px] px-2 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Hide
                    </button>
                </div>

                <div id="productsSection">
                    <div class="relative overflow-auto rounded-xl border border-gray-200 dark:border-gray-700 max-h-[600px]">
                        <table id="productsTable" class="min-w-full text-xs">
                            <thead
                                class="sticky top-0 z-10 bg-gray-100 dark:bg-gray-700/80 text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                <tr>
                                    <th class="px-2 py-2 text-left">SKU</th>
                                    <th class="px-2 py-2 text-left">Product</th>
                                    <th class="px-2 py-2 text-right">Sell</th>
                                    <th class="px-2 py-2 text-right">Qty</th>
                                    <th class="px-2 py-2 text-right">Add</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($products as $p)
                                    @php
                                        $price = $p->price ?? 0;
                                        $gst = $p->gst_percent ?? 0;
                                        $selling = $price + ($price * $gst) / 100;
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                                        <td class="px-2 py-2 text-gray-500 font-mono">
                                            {{ $p->sku }}
                                        </td>
                                        <td class="px-2 py-2">
                                            <div class="font-medium text-gray-800 dark:text-gray-100">
                                                {{ $p->name }}
                                            </div>
                                            <div class="text-[10px] text-gray-500">
                                                Stock: {{ $p->available_qty ?? 0 }}
                                            </div>
                                        </td>
                                        <td class="px-2 py-2 text-right font-semibold text-blue-600">
                                            ₹{{ number_format($selling, 2) }}
                                        </td>
                                        <td class="px-2 py-2 text-right">
                                            <div class="inline-flex items-center gap-1">
                                                <button type="button"
                                                    class="prod-minus w-6 h-6 border rounded-md text-xs">
                                                    −
                                                </button>
                                                <input type="text" value="1"
                                                    class="prod-qty w-9 text-center border rounded-md text-xs px-1 py-0.5 dark:bg-gray-800">
                                                <button type="button"
                                                    class="prod-plus w-6 h-6 border rounded-md text-xs">
                                                    +
                                                </button>
                                            </div>
                                        </td>
                                        <td class="px-2 py-2 text-right">
                                            <button type="button"
                                                class="add-product inline-flex items-center px-2 py-1 text-[11px] font-medium rounded-md
                                                       border border-blue-600 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30"
                                                data-id="{{ $p->id }}"
                                                data-name="{{ $p->name }}"
                                                data-sku="{{ $p->sku }}"
                                                data-price="{{ $price }}"
                                                data-tax="{{ $gst }}"
                                                data-available="{{ $p->available_qty ?? 0 }}">
                                                Add
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </aside>
    </div>
    <!-- FULL WIDTH: ORDER HISTORY -->
    <div class="xl:col-span-12 mt-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                    Order History
                </h3>
                <button id="toggleOrders"
                    class="text-[11px] px-2 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                    Show
                </button>
            </div>

            <div id="ordersSection" class="hidden">
                @if ($orders->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                                <tr>
                                    <th class="px-3 py-2">ID</th>
                                    <th class="px-3 py-2">Order Code</th>
                                    <th class="px-3 py-2">Date</th>
                                    <th class="px-3 py-2">Total</th>
                                    <th class="px-3 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($orders as $order)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                        <td class="px-3 py-2 text-gray-500">{{ $order->id }}</td>
                                        <td class="px-3 py-2">{{ $order->order_code }}</td>
                                        <td class="px-3 py-2">{{ $order->order_date?->format('d M Y') ?? '—' }}</td>
                                        <td class="px-3 py-2 font-semibold">{{ number_format($order->grand_total, 2) }}</td>
                                        <td class="px-3 py-2 capitalize">{{ ucfirst($order->status) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-xs text-gray-500 text-center py-6">
                        No orders found for this customer.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> <!-- end main grid -->

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
   @endpush
   @push('scripts')
   <script>
      let index = 1;

      function showToast(message) {
          const container = document.getElementById('toastContainer');
          if (!container) return;

          const toast = document.createElement('div');
          toast.className =
              'pointer-events-auto bg-green-600 text-white text-sm px-4 py-2 rounded-lg shadow-lg ' +
              'transform transition-all duration-300 opacity-0 translate-y-2';

          toast.textContent = message;
          container.appendChild(toast);

          requestAnimationFrame(() => {
              toast.classList.remove('opacity-0', 'translate-y-2');
              toast.classList.add('opacity-100', 'translate-y-0');
          });

          setTimeout(() => {
              toast.classList.remove('opacity-100', 'translate-y-0');
              toast.classList.add('opacity-0', 'translate-y-2');
              setTimeout(() => toast.remove(), 300);
          }, 2500);
      }


      function recalc() {
          let sub = 0,
              tax = 0;

          document.querySelectorAll('.item-row').forEach(row => {
              const price = parseFloat(row.querySelector('.price').value || 0);
              const qty = parseFloat(row.querySelector('.qty').value || 0);
              const taxRate = parseFloat(row.querySelector('.tax').dataset.rate || 0);

              const line = price * qty;
              const lineTax = (line * taxRate) / 100;

              row.querySelector('.total').value = (line + lineTax).toFixed(2);

              sub += line;
              tax += lineTax;
          });

          const grand = sub + tax;

          document.getElementById('sub_total').value = sub.toFixed(2);
          document.getElementById('tax_amount').value = tax.toFixed(2);
          document.getElementById('grand_total').value = grand.toFixed(2);

          const live = document.getElementById('live_total');
          if (live) {
              live.textContent = grand.toFixed(2);
          }
      }

      document.addEventListener('change', function(e) {
          if (e.target.classList.contains('product-select')) {
              const opt = e.target.selectedOptions[0];
              const row = e.target.closest('.item-row');

              row.querySelector('.price').value = opt.dataset.price || 0;

              const taxInput = row.querySelector('.tax');
              taxInput.dataset.rate = opt.dataset.tax || 0;

              recalc();
          }
      });

      document.addEventListener('input', function(e) {
          if (e.target.classList.contains('qty')) {
              recalc();
          }
      });

      document.getElementById('addRow').addEventListener('click', function() {
          const base = document.querySelector('.item-row');
          const clone = base.cloneNode(true);

          clone.querySelectorAll('input,select').forEach(el => {
              el.value = el.classList.contains('qty') ? 1 : '';
              el.name = el.name.replace(/\d+/, index);
          });

          const taxInput = clone.querySelector('.tax');
          if (taxInput) {
              taxInput.dataset.rate = 0;
          }

          document.getElementById('items').appendChild(clone);
          index++;
      });

      document.addEventListener('click', function(e) {
          if (e.target.classList.contains('qty-plus')) {
              const input = e.target.closest('.item-row').querySelector('.qty');
              input.value = (parseInt(input.value || 1) + 1);
              recalc();
          }

          if (e.target.classList.contains('qty-minus')) {
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

      // Product search
      document.addEventListener('input', function(e) {
          if (e.target.classList.contains('product-search')) {
              const term = e.target.value.toLowerCase();
              const row = e.target.closest('.item-row');
              const select = row.querySelector('.product-select');

              let firstVisible = null;

              Array.from(select.options).forEach(opt => {
                  if (!opt.value) return;

                  const name = opt.textContent.toLowerCase();
                  const sku = (opt.dataset.sku || '').toLowerCase();

                  const match = name.startsWith(term) || sku.startsWith(term);

                  opt.hidden = !match;

                  if (match && !firstVisible) {
                      firstVisible = opt;
                  }
              });

              if (firstVisible) {
                  select.value = firstVisible.value;
                  select.dispatchEvent(new Event('change', {
                      bubbles: true
                  }));
              }
          }
      });

      // Keyboard navigation for product search (↑ ↓ Enter)
      document.addEventListener('keydown', function(e) {
          if (!e.target.classList.contains('product-search')) return;

          const row = e.target.closest('.item-row');
          const select = row.querySelector('.product-select');

          const visibleOptions = Array.from(select.options)
              .filter(o => o.value && !o.hidden);

          if (!visibleOptions.length) return;

          let currentIndex = visibleOptions.findIndex(o => o.selected);

          if (e.key === 'ArrowDown') {
              e.preventDefault();
              const next = visibleOptions[currentIndex + 1] || visibleOptions[0];
              select.value = next.value;
              select.dispatchEvent(new Event('change', {
                  bubbles: true
              }));
          }

          if (e.key === 'ArrowUp') {
              e.preventDefault();
              const prev = visibleOptions[currentIndex - 1] || visibleOptions[visibleOptions.length - 1];
              select.value = prev.value;
              select.dispatchEvent(new Event('change', {
                  bubbles: true
              }));
          }

          if (e.key === 'Enter') {
              e.preventDefault();
              const chosen = visibleOptions[currentIndex] || visibleOptions[0];
              if (chosen) {
                  select.value = chosen.value;
                  select.dispatchEvent(new Event('change', {
                      bubbles: true
                  }));
                  row.querySelector('.qty').focus();
              }
          }
      });

      // View Product Modal
      document.addEventListener('click', function(e) {
          if (e.target.classList.contains('view-product')) {
              const row = e.target.closest('.item-row');
              const select = row.querySelector('.product-select');
              const opt = select.selectedOptions[0];

              if (!opt || !opt.value) return;

              document.getElementById('pm_name').textContent = opt.dataset.name || opt.textContent || '—';
              document.getElementById('pm_sku').textContent = opt.dataset.sku || '—';
              document.getElementById('pm_hsn').textContent = opt.dataset.hsn || '—';
              document.getElementById('pm_price').textContent = opt.dataset.price || '0';
              document.getElementById('pm_cost').textContent = opt.dataset.cost || '—';
              document.getElementById('pm_tax').textContent = opt.dataset.gst || '0';
              document.getElementById('pm_organic').textContent = opt.dataset.organic || '—';
              document.getElementById('pm_active').textContent = opt.dataset.active || '—';
              document.getElementById('pm_min').textContent = opt.dataset.minqty || '—';
              document.getElementById('pm_max').textContent = opt.dataset.maxqty || '—';
              document.getElementById('pm_shelf').textContent = opt.dataset.shelf ? opt.dataset.shelf + ' days' :
                  '—';
              document.getElementById('pm_short').textContent = opt.dataset.short || '—';
              document.getElementById('pm_desc').textContent = opt.dataset.desc || '—';

              openProductModal();
          }

          if (e.target.id === 'productModal' ||
              e.target.id === 'closeProductModal' ||
              e.target.id === 'closeProductModalBtn') {
              closeProductModal();
          }
      });

      function openProductModal() {
          const modal = document.getElementById('productModal');
          const card = document.getElementById('productModalCard');

          document.body.classList.add('overflow-hidden');
          modal.classList.remove('hidden');
          modal.classList.add('flex');

          requestAnimationFrame(() => {
              card.classList.remove('scale-95', 'opacity-0');
              card.classList.add('scale-100', 'opacity-100');
          });
      }

      function closeProductModal() {
          const modal = document.getElementById('productModal');
          const card = document.getElementById('productModalCard');

          card.classList.add('scale-95', 'opacity-0');

          setTimeout(() => {
              modal.classList.add('hidden');
              modal.classList.remove('flex');
              document.body.classList.remove('overflow-hidden');
          }, 150);
      }

      // ESC key support
      document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape') {
              closeProductModal();
          }
      });

      document.addEventListener('DOMContentLoaded', function() {

          // Orders Table
          if (document.getElementById('ordersTable')) {
              const ordersTable = $('#ordersTable').DataTable({
                  dom: 'lBfrtip',
                  pageLength: 5,
                  lengthMenu: [
                      [5, 10, 25, 50],
                      [5, 10, 25, 50]
                  ],
                  autoWidth: false,
                  scrollX: true,
                  responsive: false,
                  buttons: [{
                      extend: 'excelHtml5',
                      text: 'Export Orders'
                  }],
              });

              // Select All for Orders
              const selectAll = document.getElementById('selectAll');
              if (selectAll) {
                  selectAll.addEventListener('change', function() {
                      document.querySelectorAll('.row-checkbox').forEach(cb => {
                          cb.checked = this.checked;
                      });
                  });
              }
          }

          // Products Table
          if (document.getElementById('productsTable')) {
              $('#productsTable').DataTable({
                  dom: 'lfrtip',
                  pageLength: 5,
                  lengthMenu: [
                      [5, 10, 25, 50],
                      [5, 10, 25, 50]
                  ],
                  autoWidth: false,
                  scrollX: true,
                  responsive: false,
              });
          }

      });

      // Products table + / - quantity controls
      document.addEventListener('click', function(e) {
          // Plus
          if (e.target.classList.contains('prod-plus')) {
              const wrap = e.target.closest('td');
              const input = wrap.querySelector('.prod-qty');
              input.value = (parseInt(input.value || 1) + 1);
          }

          // Minus
          if (e.target.classList.contains('prod-minus')) {
              const wrap = e.target.closest('td');
              const input = wrap.querySelector('.prod-qty');
              input.value = Math.max(1, parseInt(input.value || 1) - 1);
          }
      });

      // Add product from Products table into Quick Order
      document.addEventListener('click', function(e) {
          const btn = e.target.closest('.add-product');
          if (!btn) return;

          const id = btn.dataset.id;
          const price = btn.dataset.price || 0;
          const tax = btn.dataset.tax || 0;

          // Get quantity from products table
          const wrap = btn.closest('td');
          const qtyInput = wrap.querySelector('.prod-qty');
          const qty = parseInt(qtyInput?.value || 1);

          // Find last item row
          let rows = document.querySelectorAll('.item-row');
          let row = rows[rows.length - 1];

          // If last row already has a product, clone a new row
          const select = row.querySelector('.product-select');
          if (select.value) {
              const clone = row.cloneNode(true);

              clone.querySelectorAll('input,select').forEach(el => {
                  el.value = el.classList.contains('qty') ? 1 : '';
                  el.name = el.name.replace(/\d+/, index);
              });

              const taxInput = clone.querySelector('.tax');
              if (taxInput) {
                  taxInput.dataset.rate = 0;
              }

              document.getElementById('items').appendChild(clone);
              row = clone;
              index++;
          }

          const productSelect = row.querySelector('.product-select');
          const priceInput = row.querySelector('.price');
          const taxInput = row.querySelector('.tax');
          const qtyField = row.querySelector('.qty');

          // Select matching option in dropdown
          const option = Array.from(productSelect.options).find(o => o.value == id);
          if (option) {
              productSelect.value = option.value;
          }

          priceInput.value = price;
          if (taxInput) {
              taxInput.dataset.rate = tax;
          }
          qtyField.value = qty;

          recalc();

          const name = btn.dataset.name || 'Product';
          showToast(`${name} added (Qty: ${qty})`);
      });


      function setupToggle(buttonId, sectionId, hiddenByDefault = false, showText = 'Show', hideText = 'Hide') {
          const btn = document.getElementById(buttonId);
          const section = document.getElementById(sectionId);

          if (!btn || !section) return;

          if (hiddenByDefault) {
              section.classList.add('hidden');
              btn.textContent = showText;
          } else {
              btn.textContent = hideText;
          }

          btn.addEventListener('click', () => {
              const isHidden = section.classList.toggle('hidden');
              btn.textContent = isHidden ? showText : hideText;
          });
      }

      // Customer Information (hidden by default)
      setupToggle('toggleCustomerInfo', 'customerDetails', true, 'Show Details', 'Hide Details');

      // Quick Order
      setupToggle('toggleQuickOrder', 'quickOrderSection', false, 'Show Quick Order', 'Hide Quick Order');

      // Products List
      setupToggle('toggleProducts', 'productsSection', false, 'Show Products', 'Hide Products');

      // Order History
      setupToggle('toggleOrders', 'ordersSection', true, 'Show Orders', 'Hide Orders');
   </script>
   @endpush
   <!-- Product Detail Modal -->
   <div id="productModal"
      class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm px-3">
      <div id="productModalCard"
         class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md
         h-[80vh] flex flex-col
         transform transition-all duration-200 scale-95 opacity-0">
         <!-- Header -->
         <div class="flex items-center justify-between px-4 py-2 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">
               Product Details
            </h3>
            <button id="closeProductModal"
               class="p-1 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
            ✕
            </button>
         </div>
         <!-- Body -->
         <div class="flex-1 overflow-y-auto p-3 space-y-2 text-xs">
            <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
               <div class="text-[10px] text-gray-500">Name</div>
               <div id="pm_name" class="font-medium text-gray-800 dark:text-gray-100"></div>
            </div>
            <div class="grid grid-cols-2 gap-2">
               <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                  <div class="text-[10px] text-gray-500">SKU</div>
                  <div id="pm_sku"></div>
               </div>
               <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                  <div class="text-[10px] text-gray-500">HSN</div>
                  <div id="pm_hsn"></div>
               </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
               <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                  <div class="text-[10px] text-gray-500">Price</div>
                  <div id="pm_price" class="font-semibold text-blue-600"></div>
               </div>
               <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                  <div class="text-[10px] text-gray-500">Cost</div>
                  <div id="pm_cost"></div>
               </div>
            </div>
            <div class="grid grid-cols-3 gap-2">
               <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                  <div class="text-[10px] text-gray-500">GST</div>
                  <div id="pm_tax"></div>
               </div>
               <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                  <div class="text-[10px] text-gray-500">Organic</div>
                  <div id="pm_organic"></div>
               </div>
               <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                  <div class="text-[10px] text-gray-500">Status</div>
                  <div id="pm_active"></div>
               </div>
            </div>
            <div class="grid grid-cols-3 gap-2">
               <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                  <div class="text-[10px] text-gray-500">Min</div>
                  <div id="pm_min"></div>
               </div>
               <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                  <div class="text-[10px] text-gray-500">Max</div>
                  <div id="pm_max"></div>
               </div>
               <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                  <div class="text-[10px] text-gray-500">Shelf</div>
                  <div id="pm_shelf"></div>
               </div>
            </div>
            <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
               <div class="text-[10px] text-gray-500">Short</div>
               <div id="pm_short"></div>
            </div>
            <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
               <div class="text-[10px] text-gray-500">Description</div>
               <div id="pm_desc"></div>
            </div>
         </div>
         <!-- Footer -->
         <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 flex justify-end">
            <button id="closeProductModalBtn"
               class="px-3 py-1.5 rounded-md border text-xs text-gray-700 dark:text-gray-300
               hover:bg-gray-100 dark:hover:bg-gray-700">
            Close
            </button>
         </div>
      </div>
   </div>
   <div id="toastContainer"
      class="fixed top-4 left-1/2 -translate-x-1/2 z-[9999]
      flex flex-col items-center gap-2
      w-full max-w-md px-3
      pointer-events-none">
   </div>
</x-layouts.app>
