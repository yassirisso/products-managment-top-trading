<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            新增唛头
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6 max-w-2xl">
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('shipping-marks.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        唛头名称 (Shipping Mark) *
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full border rounded-lg px-3 py-2" placeholder="例如: ABC / NY-123">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('shipping-marks.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        取消
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded">
                        保存
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>