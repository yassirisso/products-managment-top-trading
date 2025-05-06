<!-- resources/views/purchases/_form.blade.php -->
<form action="{{ route('clients.purchases.store', $client) }}" method="POST">
    @csrf
    
    <!-- As a hidden field if you have a single product -->
    <input type="hidden" name="product_id" value="{{ $product->id }}">

    <!-- OR as a select field for multiple products -->
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Product *</label>
        <select name="product_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
            @foreach($products as $product)
                <option value="{{ $product->id }}">
                    {{ $product->reference }} - ${{ number_format($product->price, 2) }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Rest of your form fields -->
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Price *</label>
        <input type="number" step="0.01" name="price" required
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
</form>
