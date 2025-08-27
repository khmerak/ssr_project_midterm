<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Product List</h3>
                <button onclick="openModal('add')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Add Product
                </button>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <div id="product-list"></div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-11/12 md:w-96 p-6">
            <h3 id="modalTitle" class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Add Product</h3>
            <form id="productForm" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" id="productId">

                <div class="col-span-1">
                    <label class="block text-gray-700 dark:text-gray-200 mb-1">Name</label>
                    <input type="text" id="name"
                        class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-gray-200" required>
                </div>

                <div class="col-span-1">
                    <label class="block text-gray-700 dark:text-gray-200 mb-1">Category</label>
                    <select id="category" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">-- Select Category --</option>
                    </select>
                </div>

                <div class="col-span-1">
                    <label class="block text-gray-700 dark:text-gray-200 mb-1">Price</label>
                    <input type="number" id="price"
                        class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-gray-200" required>
                </div>

                <div class="col-span-1">
                    <label class="block text-gray-700 dark:text-gray-200 mb-1">Discount (%)</label>
                    <input type="number" id="discount"
                        class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-gray-200" min="0"
                        max="100">
                </div>

                <div class="col-span-1">
                    <label class="block text-gray-700 dark:text-gray-200 mb-1">Quantity</label>
                    <input type="number" id="quantity"
                        class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-gray-200" required>
                </div>

                <div class="col-span-2">
                    <label class="block text-gray-700 dark:text-gray-200 mb-1">Description</label>
                    <textarea id="description" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-gray-200"></textarea>
                </div>

                <div class="col-span-2">
                    <label class="block text-gray-700 dark:text-gray-200 mb-1">Image</label>
                    <input type="file" id="image" accept="image/*" class="w-full border rounded px-3 py-2">
                </div>

                <div class="col-span-2 flex justify-end gap-2 mt-2">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 rounded bg-gray-400 hover:bg-gray-500 text-white">Cancel</button>
                    <button type="submit" id="modalSubmit"
                        class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let products = [];
        let categories = [];

        async function fetchCategories() {
            const res = await fetch("{{ route('categories.index') }}");
            categories = await res.json();

            const select = document.getElementById('category');
            select.innerHTML = '<option value="">-- Select Category --</option>';

            categories.forEach(cat => {
                const option = document.createElement('option');
                option.value = cat.id;
                option.textContent = cat.name;
                select.appendChild(option);
            });
        }

        async function fetchProducts() {
            const res = await fetch("{{ route('products.index') }}");
            products = await res.json();
            renderProducts();
        }

        function openModal(mode, product = {}) {
            const modal = document.getElementById('productModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalSubmit = document.getElementById('modalSubmit');
            const form = document.getElementById('productForm');

            modal.classList.remove('hidden');

            if (mode === 'add') {
                modalTitle.textContent = 'Add Product';
                modalSubmit.textContent = 'Add';
                form.reset();
                document.getElementById('productId').value = '';
            } else if (mode === 'edit') {
                modalTitle.textContent = 'Edit Product';
                modalSubmit.textContent = 'Update';
                document.getElementById('productId').value = product.id ?? '';
                document.getElementById('name').value = product.name ?? '';
                document.getElementById('category').value = product.category ?? '';
                document.getElementById('price').value = product.price ?? '';
                document.getElementById('discount').value = product.discount ?? '';
                document.getElementById('quantity').value = product.quantity ?? '';
                document.getElementById('description').value = product.description ?? '';
            }
        }

        function closeModal() {
            document.getElementById('productModal').classList.add('hidden');
        }

        document.getElementById('productForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const id = document.getElementById('productId').value;
            const formData = new FormData();
            ['name', 'category', 'price', 'discount', 'quantity', 'description'].forEach(f => {
                formData.append(f, document.getElementById(f).value);
            });
            const image = document.getElementById('image').files[0];
            if (image) formData.append('image', image);

            let url = "{{ route('products.store') }}";
            if (id) {
                url = `/products/${id}`;
                formData.append('_method', 'PUT');
            }

            await fetch(url, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                body: formData
            });

            closeModal();
            fetchProducts();
        });

        async function deleteProduct(id) {
            if (!confirm('Are you sure?')) return;

            await fetch(`/products/${id}`, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}", 'X-HTTP-Method-Override': 'DELETE'}
            });

            fetchProducts();
        }

        function renderProducts() {
            const container = document.getElementById('product-list');
            container.innerHTML = '';

            if (!products.length) return container.innerHTML =
                '<p class="text-gray-700 dark:text-gray-200">No products found.</p>';

            const table = document.createElement('table');
            table.className = "min-w-full border border-gray-500 text-left";

            table.innerHTML = `
                <thead>
                    <tr class="bg-gray-700 text-gray-200">
                        <th class="border px-4 py-2">Image</th>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Category</th>
                        <th class="border px-4 py-2">Price</th>
                        <th class="border px-4 py-2">Quantity</th>
                        <th class="border px-4 py-2">Discount</th>
                        <th class="border px-4 py-2">Description</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
            `;

            const tbody = document.createElement('tbody');
            products.forEach(product => {
                const imageUrl = product.image ? `{{ asset('storage') }}/${product.image}` : 'https://via.placeholder.com/150';
                const categoryName = product.category_relation?.name || '';

                const tr = document.createElement('tr');
                tr.className = "bg-gray-800 text-gray-200";
                tr.innerHTML = `
                    <td class="border px-4 py-2"><img src="${imageUrl}" class="w-20 h-20 object-cover rounded"></td>
                    <td class="border px-4 py-2">${product.name}</td>
                    <td class="border px-4 py-2">${categoryName}</td>
                    <td class="border px-4 py-2">${product.price}</td>
                    <td class="border px-4 py-2">${product.quantity}</td>
                    <td class="border px-4 py-2">${product.discount}%</td>
                    <td class="border px-4 py-2">${product.description}</td>
                    <td class="border px-4 py-2 flex gap-2">
                        <button onclick='openModal("edit", ${JSON.stringify(product)})' class="px-2 py-1 bg-blue-500 hover:bg-yellow-600 text-white rounded">Edit</button>
                        <button onclick='deleteProduct(${product.id})' class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            table.appendChild(tbody);
            container.appendChild(table);
        }

        // Initial fetch
        fetchCategories().then(fetchProducts);
    </script>
</x-app-layout>
