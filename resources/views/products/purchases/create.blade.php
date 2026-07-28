<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800">

            Add Purchase - {{ $product->reference }}

        </h2>

    </x-slot>


    <div class="py-6">

        <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow">


            <form action="{{ route('products.purchases.store', $product->id) }}" method="POST">

                @csrf


                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">


                    <!-- Supplier -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Supplier
                        </label>


                        <div class="flex gap-2">


                            <select id="supplier_id" name="supplier_id" class="w-full rounded-lg border-gray-300"
                                required>


                                <option value="">
                                    Select Supplier
                                </option>


                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach


                            </select>



                            <button type="button" onclick="openSupplierModal()"
                                class="bg-blue-600 text-white px-4 rounded-lg">

                                +

                            </button>


                        </div>


                    </div>



                    <!-- Quantity -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Quantity
                        </label>

                        <input type="number" step="0.01" id="quantity" name="quantity"
                            class="w-full rounded-lg border-gray-300" required>

                    </div>



                    <!-- Unit Price -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Unit Price
                        </label>

                        <input type="number" step="0.01" id="unit_price" name="unit_price"
                            class="w-full rounded-lg border-gray-300" required>

                    </div>



                    <!-- Total Amount -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Total Amount
                        </label>

                        <input type="number" step="0.01" id="total_amount" name="total_amount" readonly
                            class="w-full rounded-lg border-gray-300 bg-gray-100">

                    </div>



                    <!-- Currency -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Currency
                        </label>

                        <select name="currency" class="w-full rounded-lg border-gray-300">

                            <option value="RMB">
                                RMB
                            </option>

                            <option value="USD">
                                USD
                            </option>

                        </select>

                    </div>



                    <!-- Payment Status -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Payment Status
                        </label>


                        <select name="payment_status" class="w-full rounded-lg border-gray-300">


                            <option value="unpaid">
                                Unpaid
                            </option>


                            <option value="partial">
                                Partial
                            </option>


                            <option value="paid">
                                Paid
                            </option>


                        </select>

                    </div>



                    <!-- Purchase Date -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Purchase Date
                        </label>


                        <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}"
                            class="w-full rounded-lg border-gray-300" required>

                    </div>



                </div>



                <!-- Note -->
                <div class="mt-6">

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Note
                    </label>


                    <textarea name="note" rows="3" class="w-full rounded-lg border-gray-300"></textarea>

                </div>



                <div class="mt-8 flex justify-end">

                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">

                        Save Purchase

                    </button>

                </div>



            </form>


        </div>

    </div>



    <script>
        function calculateTotal() {
            let quantity = document.getElementById('quantity').value || 0;

            let price = document.getElementById('unit_price').value || 0;


            document.getElementById('total_amount').value =
                (quantity * price).toFixed(2);
        }


        document.getElementById('quantity')
            .addEventListener('input', calculateTotal);


        document.getElementById('unit_price')
            .addEventListener('input', calculateTotal);
    </script>

    <div id="supplierModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">


        <div class="bg-white rounded-lg p-6 w-96">


            <h2 class="text-lg font-semibold mb-4">
                Add New Supplier
            </h2>


            <form id="supplierForm">
                @csrf

                <input type="text" id="supplier_name" name="name" placeholder="Supplier Name"
                    class="w-full mb-3 rounded-lg border-gray-300" required>

                <input type="text" id="supplier_phone" name="phone" placeholder="Phone"
                    class="w-full mb-3 rounded-lg border-gray-300">

                <input type="email" id="supplier_email" name="email" placeholder="Email"
                    class="w-full mb-3 rounded-lg border-gray-300">


                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">

                    Save

                </button>

            </form>


        </div>


    </div>

    <script>
        function openSupplierModal() {
            document.getElementById('supplierModal')
                .classList.remove('hidden');

            document.getElementById('supplierModal')
                .classList.add('flex');
        }


        function closeSupplierModal() {
            document.getElementById('supplierModal')
                .classList.add('hidden');
        }
        document.getElementById('supplierForm')
            .addEventListener('submit', function(e) {

                e.preventDefault();


                fetch("{{ route('suppliers.quick-create') }}", {

                        method: "POST",

                        headers: {

                            "Content-Type": "application/json",

                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value

                        },

                        body: JSON.stringify({

                            name: document.getElementById('supplier_name').value,

                            phone: document.getElementById('supplier_phone').value,

                            email: document.getElementById('supplier_email').value,

                        })

                    })


                    .then(response => response.json())


                    .then(supplier => {


                        // add supplier to dropdown

                        let select =
                            document.getElementById('supplier_id');


                        let option =
                            document.createElement('option');


                        option.value = supplier.id;

                        option.text =
                            supplier.name;


                        option.selected = true;


                        select.appendChild(option);



                        closeSupplierModal();


                        document.getElementById('supplierForm').reset();


                    });


            });
    </script>


</x-app-layout>
