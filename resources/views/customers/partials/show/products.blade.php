
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
