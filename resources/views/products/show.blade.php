<x-layouts.app>
    <h1 class="text-2xl font-bold mb-4">Product Details</h1>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg space-y-2">
        <p><strong>Name:</strong> {{ $product->name }}</p>
        <p><strong>Category:</strong> {{ $product->category?->name }}</p>
        <p><strong>GST:</strong> {{ $product->gst_percent }}%</p>
        <p><strong>Status:</strong> {{ $product->is_active ? 'Active' : 'Inactive' }}</p>

        <a href="{{ route('products.edit',$product) }}" class="text-blue-600">Edit</a>
    </div>
</x-layouts.app>
