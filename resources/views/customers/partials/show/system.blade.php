<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 space-y-8">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
            Customer Information
        </h3>
        <button type="button" id="toggleCustomerInfo"
            class="text-xs px-3 py-1 rounded-md border">
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
    </div>
</div>
