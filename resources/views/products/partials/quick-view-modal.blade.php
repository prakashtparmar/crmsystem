<!-- Product Quick View Modal -->
<div id="productModal"
     class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm px-3 overflow-y-auto">

    <div class="min-h-screen flex items-start justify-center py-6">
        <div id="productModalCard"
             class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md
                    max-h-[85vh] flex flex-col
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

                <!-- Image -->
                <div class="flex justify-center">
                    <div class="w-28 h-28 rounded-lg overflow-hidden border bg-gray-100">
                        <img id="pm_image"
                             src=""
                             alt="Product Image"
                             class="w-full h-full object-cover hidden">
                        <div id="pm_no_image"
                             class="w-full h-full flex items-center justify-center text-gray-400 text-[11px]">
                            No Image
                        </div>
                    </div>
                </div>

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

                <!-- Relations -->
                <div class="grid grid-cols-2 gap-2">
                    <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                        <div class="text-[10px] text-gray-500">Category</div>
                        <div id="pm_category"></div>
                    </div>

                    <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                        <div class="text-[10px] text-gray-500">Subcategory</div>
                        <div id="pm_subcategory"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                        <div class="text-[10px] text-gray-500">Brand</div>
                        <div id="pm_brand"></div>
                    </div>

                    <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                        <div class="text-[10px] text-gray-500">Unit</div>
                        <div id="pm_unit"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                        <div class="text-[10px] text-gray-500">Crop</div>
                        <div id="pm_crop"></div>
                    </div>

                    <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                        <div class="text-[10px] text-gray-500">Season</div>
                        <div id="pm_season"></div>
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
                    <div id="pm_desc" class="whitespace-pre-line"></div>
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
</div>
