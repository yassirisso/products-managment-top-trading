<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            编辑仓库费用
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6 max-w-3xl">
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('warehouse-fees.update', $warehouseFee) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- DATE -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            日期 (Date) *
                        </label>
                        <input type="date" name="date"
                            value="{{ old('date', $warehouseFee->date->format('Y-m-d')) }}" required
                            class="w-full border rounded-lg px-3 py-2">
                        @error('date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CONTAINER -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            柜号 (Container No) *
                        </label>
                        <select name="container_no" id="container_no" required
                            class="w-full border rounded-lg px-3 py-2 select2-container-select">
                            <option value="">搜索、选择或输入新柜号...</option>
                            @foreach ($containers as $container)
                                <option value="{{ $container }}"
                                    {{ old('container_no', $warehouseFee->container_no) == $container ? 'selected' : '' }}>
                                    {{ $container }}
                                </option>
                            @endforeach
                        </select>
                        @error('container_no')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CLIENT -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            客户 (Client) *
                        </label>
                        <select name="client_id" required class="w-full border rounded-lg px-3 py-2">
                            <option value="">选择客户</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}"
                                    {{ old('client_id', $warehouseFee->client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SHIPPING MARK -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            唛头 (Shipping Mark)
                        </label>
                        <select name="shipping_mark_id" class="w-full border rounded-lg px-3 py-2">
                            <option value="">选择唛头 (可选)</option>
                            @foreach ($shippingMarks as $mark)
                                <option value="{{ $mark->id }}"
                                    {{ old('shipping_mark_id', $warehouseFee->shipping_mark_id) == $mark->id ? 'selected' : '' }}>
                                    {{ $mark->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('shipping_mark_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- AMOUNT -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            金额 (Montant RMB) *
                        </label>
                        <input type="number" step="0.01" min="0" name="montant_rmb"
                            value="{{ old('montant_rmb', $warehouseFee->montant_rmb) }}" required
                            class="w-full border rounded-lg px-3 py-2">
                        @error('montant_rmb')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('warehouse-fees.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        取消
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded">
                        更新
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            // Check if jQuery and Select2 are actually loaded
            if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                $('.select2-container-select').select2({
                    placeholder: "搜索、选择或输入新柜号...",
                    allowClear: true,
                    tags: true,
                    width: '100%',
                    language: {
                        noResults: function() {
                            return "按回车键添加新柜号";
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
