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
