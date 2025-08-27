<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Category List</h3>
                <!-- Button to open Add modal -->
                <button onclick="openModal('add')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Add Category
                </button>
            </div>

            <!-- Categories Table -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <table class="min-w-full border border-gray-300">
                    <thead>
                        <tr class="bg-gray-700 text-gray-200">
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">Name</th>
                            <th class="px-4 py-2 border">Description</th>
                            <th class="px-4 py-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                <td class="px-4 py-2 border">{{ $category->id }}</td>
                                <td class="px-4 py-2 border">{{ $category->name }}</td>
                                <td class="px-4 py-2 border">{{ $category->description }}</td>
                                <td class="px-4 py-2 border flex gap-2">
                                    <!-- Edit button -->
                                    <button onclick="openModal('edit', {{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}')"
                                        class="px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded">Edit</button>

                                    <!-- Delete button -->
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-96 p-6">
            <h3 id="modalTitle" class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Add Category</h3>

            <form id="categoryForm" action="{{ route('categories.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200 mb-1">Name</label>
                    <input type="text" name="name" id="categoryName" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-gray-200" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200 mb-1">Description</label>
                    <textarea name="description" id="categoryDescription" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-gray-200"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 rounded bg-gray-400 hover:bg-gray-500 text-white">Cancel</button>
                    <button type="submit" id="modalSubmit" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(mode, id = null, name = '', description = '') {
            const modal = document.getElementById('categoryModal');
            const modalTitle = document.getElementById('modalTitle');
            const form = document.getElementById('categoryForm');
            const categoryName = document.getElementById('categoryName');
            const categoryDescription = document.getElementById('categoryDescription');
            const formMethod = document.getElementById('formMethod');

            modal.classList.remove('hidden');

            if(mode === 'add') {
                modalTitle.textContent = 'Add Category';
                form.action = "{{ route('categories.store') }}";
                formMethod.value = 'POST';
                categoryName.value = '';
                categoryDescription.value = '';
            } else if(mode === 'edit') {
                modalTitle.textContent = 'Edit Category';
                form.action = `/categories/${id}`;
                formMethod.value = 'PUT';
                categoryName.value = name;
                categoryDescription.value = description;
            }
        }

        function closeModal() {
            document.getElementById('categoryModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
