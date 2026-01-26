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
