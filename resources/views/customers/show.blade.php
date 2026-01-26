<x-layouts.app>
   <!-- Breadcrumbs -->
   <div class="mb-6 flex items-center text-sm">
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
      <span class="text-gray-500 dark:text-gray-400">{{ __('View') }}</span>
   </div>
   @if (session('success'))
   <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">
      {{ session('success') }}
   </div>
   @endif
   @if (session('error'))
   <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
      {{ session('error') }}
   </div>
   @endif
   @if ($errors->any())
   <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
      <ul class="list-disc list-inside text-sm">
         @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
         @endforeach
      </ul>
   </div>
   @endif


   <!-- Header -->
   <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
         <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ $customer->display_name ?? $customer->first_name . ' ' . $customer->last_name }}
         </h1>
         <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Customer Overview & Quick Order
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
   <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
   <!-- LEFT SIDE -->
   <div class="xl:col-span-3 space-y-6">
      <!-- Customer Info -->
      <div
         class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-8">
         <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
               Customer Information
            </h3>
            <button type="button" id="toggleCustomerInfo"
               class="text-xs px-3 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
            Hide Details
            </button>
         </div>
         <div id="customerDetails" class="space-y-8">
            <!-- System -->
            <div>
               <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3">System</h4>
               <div class="grid grid-cols-2 md:grid-cols-4 gap-5 text-sm">
                  <div>
                     <span class="text-gray-500">Customer Code</span>
                     <div class="font-medium">{{ $customer->customer_code }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">UUID</span>
                     <div class="font-medium break-all">{{ $customer->uuid }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Created At</span>
                     <div class="font-medium">{{ $customer->created_at->format('d M Y') }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Updated At</span>
                     <div class="font-medium">{{ $customer->updated_at->format('d M Y') }}</div>
                  </div>
               </div>
            </div>
            <!-- Identity -->
            <div>
               <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3">Identity</h4>
               <div class="grid grid-cols-2 md:grid-cols-5 gap-5 text-sm">
                  <div>
                     <span class="text-gray-500">First Name</span>
                     <div class="font-medium">{{ $customer->first_name }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Last Name</span>
                     <div class="font-medium">{{ $customer->last_name ?? '—' }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Display Name</span>
                     <div class="font-medium">{{ $customer->display_name ?? '—' }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Mobile</span>
                     <div class="font-medium">{{ $customer->mobile }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Email</span>
                     <div class="font-medium">{{ $customer->email ?? '—' }}</div>
                  </div>
               </div>
            </div>
            <!-- Classification -->
            <div>
               <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3">Classification</h4>
               <div class="grid grid-cols-2 md:grid-cols-4 gap-5 text-sm">
                  <div>
                     <span class="text-gray-500">Type</span>
                     <div class="font-medium capitalize">{{ $customer->type }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Category</span>
                     <div class="font-medium capitalize">{{ $customer->category }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Company</span>
                     <div class="font-medium">{{ $customer->company_name ?? '—' }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">GST</span>
                     <div class="font-medium">{{ $customer->gst_number ?? '—' }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">PAN</span>
                     <div class="font-medium">{{ $customer->pan_number ?? '—' }}</div>
                  </div>
               </div>
            </div>
            <!-- Address -->
            <div>
               <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3">Address</h4>
               <div class="grid grid-cols-2 md:grid-cols-5 gap-5 text-sm">
                  <div>
                     <span class="text-gray-500">Address Line 1</span>
                     <div class="font-medium">{{ $customer->address_line1 ?? '—' }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Address Line 2</span>
                     <div class="font-medium">{{ $customer->address_line2 ?? '—' }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Village</span>
                     <div class="font-medium">{{ $customer->village ?? '—' }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Taluka</span>
                     <div class="font-medium">{{ $customer->taluka ?? '—' }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">District</span>
                     <div class="font-medium">{{ $customer->district ?? '—' }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">State</span>
                     <div class="font-medium">{{ $customer->state ?? '—' }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Country</span>
                     <div class="font-medium">{{ $customer->country ?? '—' }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Pincode</span>
                     <div class="font-medium">{{ $customer->pincode ?? '—' }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Latitude</span>
                     <div class="font-medium">{{ $customer->latitude ?? '—' }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Longitude</span>
                     <div class="font-medium">{{ $customer->longitude ?? '—' }}</div>
                  </div>
               </div>
            </div>
            <!-- Agriculture -->
            <div>
               <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3">Agriculture</h4>
               <div class="grid grid-cols-2 md:grid-cols-4 gap-5 text-sm">
                  <div>
                     <span class="text-gray-500">Land Area</span>
                     <div class="font-medium">
                        {{ $customer->land_area ? $customer->land_area . ' ' . $customer->land_unit : '—' }}
                     </div>
                  </div>
                  <div>
                     <span class="text-gray-500">Primary Crops</span>
                     <div class="font-medium">
                        @if ($customer->primary_crops)
                        {{ is_array($customer->primary_crops) ? implode(', ', $customer->primary_crops) : $customer->primary_crops }}
                        @else
                        —
                        @endif
                     </div>
                  </div>
                  <div>
                     <span class="text-gray-500">Secondary Crops</span>
                     <div class="font-medium">
                        @if ($customer->secondary_crops)
                        {{ is_array($customer->secondary_crops) ? implode(', ', $customer->secondary_crops) : $customer->secondary_crops }}
                        @else
                        —
                        @endif
                     </div>
                  </div>
                  <div>
                     <span class="text-gray-500">Irrigation</span>
                     <div class="font-medium">{{ $customer->irrigation_type ?? '—' }}</div>
                  </div>
               </div>
            </div>
            <!-- Financial & Status -->
            <div>
               <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3">Financial & Status
               </h4>
               <div class="grid grid-cols-2 md:grid-cols-4 gap-5 text-sm">
                  <div>
                     <span class="text-gray-500">Credit Limit</span>
                     <div class="font-medium">{{ number_format($customer->credit_limit, 2) }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Outstanding</span>
                     <div class="font-medium">{{ number_format($customer->outstanding_balance, 2) }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Credit Valid Till</span>
                     <div class="font-medium">
                        {{ optional($customer->credit_valid_till)->format('d M Y') ?? '—' }}
                     </div>
                  </div>
                  <div>
                     <span class="text-gray-500">Orders Count</span>
                     <div class="font-medium">{{ $customer->orders_count }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Aadhaar (Last 4)</span>
                     <div class="font-medium">{{ $customer->aadhaar_last4 ?? '—' }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">KYC</span>
                     <div class="font-medium">{{ $customer->kyc_completed ? 'Verified' : 'Pending' }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">KYC Verified At</span>
                     <div class="font-medium">
                        {{ optional($customer->kyc_verified_at)->format('d M Y') ?? '—' }}
                     </div>
                  </div>
                  <div>
                     <span class="text-gray-500">First Purchase</span>
                     <div class="font-medium">
                        {{ optional($customer->first_purchase_at)->format('d M Y') ?? '—' }}
                     </div>
                  </div>
                  <div>
                     <span class="text-gray-500">Last Purchase</span>
                     <div class="font-medium">
                        {{ optional($customer->last_purchase_at)->format('d M Y') ?? '—' }}
                     </div>
                  </div>
                  <div>
                     <span class="text-gray-500">Status</span>
                     <div class="font-medium">{{ $customer->is_active ? 'Active' : 'Inactive' }}</div>
                  </div>
                  <div>
                     <span class="text-gray-500">Blacklisted</span>
                     <div class="font-medium">{{ $customer->is_blacklisted ? 'Yes' : 'No' }}</div>
                  </div>
               </div>
            </div>
            <!-- Notes -->
            <div>
               <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3">Internal Notes
               </h4>
               <div class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
                  {{ $customer->internal_notes ?? '—' }}
               </div>
            </div>
         </div>
      </div>
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

            <!-- Items -->
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300 mb-3">
                    Order Items
                </h3>

                <div id="items" class="space-y-3">
                    <div class="item-row flex flex-col md:flex-row gap-4 p-4 rounded-xl border bg-white dark:bg-gray-900">

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

            <!-- Totals (Same layout as Edit Form) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t bg-gray-50 dark:bg-gray-900 p-4 rounded-xl">
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




      <!-- Product List -->
      <div
         class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
         <div class="flex items-center justify-between mb-4">
            <div>
               <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                  Products
               </h3>
               <p class="text-xs text-gray-400 mt-0.5">Quick add to order</p>
            </div>
            <button type="button" id="toggleProducts"
               class="text-xs px-3 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
            Hide Products
            </button>
         </div>
         <div id="productsSection">
            <div
               class="relative overflow-auto rounded-xl border border-gray-200 dark:border-gray-700 max-h-[520px]">
               <table id="productsTable" class="min-w-full text-sm">
                  <thead
                     class="sticky top-0 z-10 bg-gray-100 dark:bg-gray-700/80 text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">
                     <tr>
                        <th class="px-3 py-2 text-left">ID</th>
                        <th class="px-3 py-2 text-left">SKU</th>
                        <th class="px-3 py-2 text-left">Product</th>
                        <th class="px-3 py-2 text-right">Price</th>
                        <th class="px-3 py-2 text-center">GST</th>
                        <th class="px-3 py-2 text-right">Selling</th>
                        <th class="px-3 py-2 text-right">Qty</th>
                        <th class="px-3 py-2 text-center">Status</th>
                        <th class="px-3 py-2 text-right">Action</th>
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
                        <td class="px-3 py-2 text-gray-500">{{ $p->id }}</td>
                        <td class="px-3 py-2 text-gray-500 font-mono text-[11px]">
                           {{ $p->sku }}
                        </td>
                        <td class="px-3 py-2">
                           <div class="font-medium text-gray-800 dark:text-gray-100">
                              {{ $p->name }}
                           </div>
                        </td>
                        <td class="px-3 py-2 text-right tabular-nums">
                           ₹{{ number_format($price, 2) }}
                        </td>
                        <td class="px-3 py-2 text-center">
                           <span class="text-xs text-gray-600 dark:text-gray-300">
                           {{ $gst }}%
                           </span>
                        </td>
                        <!-- Selling Price (Price + GST) -->
                        <td class="px-3 py-2 text-right tabular-nums font-semibold text-blue-600">
                           ₹{{ number_format($selling, 2) }}
                        </td>
                        <td class="px-3 py-2 text-right">
                           <span
                              class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold
                              {{ ($p->available_qty ?? 0) <= 0
                              ? 'bg-red-100 text-red-700'
                              : (($p->available_qty ?? 0) < 10
                              ? 'bg-yellow-100 text-yellow-800'
                              : 'bg-green-100 text-green-700') }}">
                           {{ $p->available_qty ?? 0 }}
                           </span>
                        </td>
                        <td class="px-3 py-2 text-center">
                           <span
                              class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded-full
                              {{ $p->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                           {{ $p->is_active ? 'Active' : 'Inactive' }}
                           </span>
                        </td>
                        <td class="px-3 py-2 text-right">
                           <div class="inline-flex items-center gap-1">
                              <button type="button"
                                 class="prod-minus w-7 h-7 border rounded-md text-xs hover:bg-gray-100 dark:hover:bg-gray-700">
                              −
                              </button>
                              <input type="text" value="1"
                                 class="prod-qty w-11 text-center border rounded-md text-xs px-1 py-1 dark:bg-gray-800">
                              <button type="button"
                                 class="prod-plus w-7 h-7 border rounded-md text-xs hover:bg-gray-100 dark:hover:bg-gray-700">
                              +
                              </button>
                              <button type="button"
                                 class="add-product ml-1 inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-md
                                 border border-blue-600 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30"
                                 data-id="{{ $p->id }}" data-name="{{ $p->name }}"
                                 data-sku="{{ $p->sku }}" data-price="{{ $price }}"
                                 data-tax="{{ $gst }}"
                                 data-available="{{ $p->available_qty ?? 0 }}">
                              Add
                              </button>
                           </div>
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <!-- Order History -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
         <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
               Order History
            </h3>
            <button id="toggleOrders"
               class="text-xs px-3 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
            Hide Orders
            </button>
         </div>
         <div id="ordersSection">
            @if ($orders->count())
            <div class="overflow-x-auto">
               <table class="w-full text-sm">
                  <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                     <tr>
                        <th class="px-3 py-2">ID</th>
                        <th class="px-3 py-2">Order Code</th>
                        <th class="px-3 py-2">Customer</th>
                        <th class="px-3 py-2">Order Date</th>
                        <th class="px-3 py-2">Grand Total</th>
                        <th class="px-3 py-2">Status</th>
                     </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                     @foreach ($orders as $order)
                     {{-- Order Row --}}
                     <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <td class="px-3 py-2 text-gray-500">{{ $order->id }}</td>
                        <td class="px-3 py-2">{{ $order->order_code }}</td>
                        <td class="px-3 py-2">{{ $order->customer_name }}</td>
                        <td class="px-3 py-2">{{ $order->order_date?->format('d M Y') ?? '—' }}</td>
                        <td class="px-3 py-2 font-semibold">{{ number_format($order->grand_total, 2) }}</td>
                        <td class="px-3 py-2 capitalize">{{ ucfirst($order->status) }}</td>
                     </tr>
                     {{-- Order Items --}}
                     <tr class="bg-gray-50 dark:bg-gray-800">
                        <td colspan="6" class="px-6 py-3">
                           @if($order->items->count())
                           <table class="w-full text-xs border rounded">
                              <thead class="bg-gray-100 dark:bg-gray-700">
                                 <tr>
                                    <th class="px-2 py-1 text-left">Product</th>
                                    <th class="px-2 py-1 text-right">Price</th>
                                    <th class="px-2 py-1 text-right">Qty</th>
                                    <th class="px-2 py-1 text-right">Tax</th>
                                    <th class="px-2 py-1 text-right">Discount</th>
                                    <th class="px-2 py-1 text-right">Total</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach($order->items as $item)
                                 <tr class="border-t">
                                    <td class="px-2 py-1">{{ $item->product_name }}</td>
                                    <td class="px-2 py-1 text-right">{{ number_format($item->price, 2) }}</td>
                                    <td class="px-2 py-1 text-right">{{ $item->quantity }}</td>
                                    <td class="px-2 py-1 text-right">{{ number_format($item->tax_amount, 2) }}</td>
                                    <td class="px-2 py-1 text-right">{{ number_format($item->discount_amount, 2) }}</td>
                                    <td class="px-2 py-1 text-right font-semibold">{{ number_format($item->total, 2) }}</td>
                                 </tr>
                                 @endforeach
                              </tbody>
                           </table>
                           @else
                           <div class="text-xs text-gray-500">No items for this order.</div>
                           @endif
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
            @else
            <div class="text-sm text-gray-500 text-center py-8">
               No orders found for this customer.
            </div>
            @endif
         </div>
      </div>
   </div>
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
    let sub = 0, tax = 0;

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

    const discountEl = document.getElementById('discount_amount');
    let discount = parseFloat(discountEl?.value || 0);
    const max = sub + tax;

    if (discount > max) {
        discount = max;
        discountEl.value = max.toFixed(2);
    }

    document.getElementById('sub_total').value = sub.toFixed(2);
    document.getElementById('tax_amount').value = tax.toFixed(2);
    document.getElementById('grand_total').value =
        Math.max(0, (sub + tax - discount)).toFixed(2);
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
    if (e.target.classList.contains('qty') || e.target.id === 'discount_amount') {
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
