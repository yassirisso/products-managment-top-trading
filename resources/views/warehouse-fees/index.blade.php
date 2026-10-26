<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            仓库费用
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">
                仓库费用
            </h1>

            <a href="{{ route('warehouse-fees.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm text-center whitespace-nowrap">
                + 新增费用
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">日期 (Date)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">柜号 (Container No)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">客户 (Client)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">唛头 (Shipping Mark)</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">金额 (RMB)</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">操作 (Actions)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($warehouseFees as $fee)
                            <tr class="hover:bg-gray-50 text-sm">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $fee->date->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $fee->container_no }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $fee->client->name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $fee->shippingMark->name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-medium text-blue-600">
                                    {{ number_format($fee->montant_rmb, 2) }} ¥
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex justify-end items-center space-x-3">
                                        <a href="{{ route('warehouse-fees.edit', $fee) }}"
                                            class="text-indigo-600 hover:text-indigo-900" title="编辑">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('warehouse-fees.destroy', $fee) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="删除"
                                                onclick="return confirm('确定要删除这条记录吗？')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 text-sm">
                                    暂无仓库费用记录
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 px-6 pb-6">
                {{ $warehouseFees->links() }}
            </div>
        </div>
    </div>
</x-app-layout>