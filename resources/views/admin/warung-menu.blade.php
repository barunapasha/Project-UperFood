<!-- resources/views/admin/warung-menu.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu {{ $warung->name }} - UperFood Admin</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Admin Navbar -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <img src="{{ asset('images/logo-uperfood-blue.png') }}" alt="UperFood" class="h-16">
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-4">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('admin.dashboard') }}" class="text-purple-500 hover:text-purple-700">
                    ← Kembali ke Dashboard
                </a>
            </div>

            <h1 class="text-2xl font-semibold mb-6">Menu {{ $warung->name }}</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @foreach($warung->menuCategories as $category)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">{{ $category->name }}</h2>
                    <button 
                        data-category-id="{{ $category->id }}"
                        onclick="openAddModal(this.dataset.categoryId)"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Tambah Menu
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($category->menuItems as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->name }}</td>
                                <td class="px-6 py-4">{{ $item->description }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->is_available)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Tersedia
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Tidak Tersedia
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button 
                                        data-item-id="{{ $item->id }}"
                                        data-item-name="{{ $item->name }}"
                                        data-item-description="{{ $item->description }}"
                                        data-item-price="{{ $item->price }}"
                                        data-item-image="{{ $item->image }}"
                                        data-item-available="{{ $item->is_available }}"
                                        onclick="openEditModal(this)"
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                        Edit
                                    </button>
                                    <button 
                                        data-item-id="{{ $item->id }}"
                                        onclick="deleteMenuItem(this.dataset.itemId)"
                                        class="text-red-600 hover:text-red-900">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Menu Modal (Add/Edit) -->
    <div id="menuModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium" id="modalTitle">Tambah Menu Baru</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="menuForm" method="POST" class="space-y-4">
                @csrf
                <div id="methodField"></div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Menu</label>
                    <input type="text" name="name" id="menuName" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="description" id="menuDescription" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga</label>
                    <input type="number" name="price" id="menuPrice" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">URL Gambar</label>
                    <input type="text" name="image" id="menuImage" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_available" id="menuAvailable"
                           class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                    <label class="ml-2 block text-sm text-gray-700">Menu Tersedia</label>
                </div>

                <div class="mt-5 sm:mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()"
                            class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Batal
                    </button>
                    <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal(categoryId) {
            document.getElementById('modalTitle').textContent = 'Tambah Menu Baru';
            document.getElementById('menuForm').action = `/admin/warung/{{ $warung->id }}/category/${categoryId}/menu`;
            document.getElementById('menuForm').method = 'POST';
            document.getElementById('methodField').innerHTML = '';
            resetForm();
            document.getElementById('menuModal').classList.remove('hidden');
        }

        function openEditModal(button) {
            const itemId = button.dataset.itemId;
            document.getElementById('modalTitle').textContent = 'Edit Menu';
            document.getElementById('menuForm').action = `/admin/menu/${itemId}`;
            document.getElementById('menuForm').method = 'POST';
            document.getElementById('methodField').innerHTML = '@method("PUT")';
            
            // Fill form with item data
            document.getElementById('menuName').value = button.dataset.itemName;
            document.getElementById('menuDescription').value = button.dataset.itemDescription;
            document.getElementById('menuPrice').value = button.dataset.itemPrice;
            document.getElementById('menuImage').value = button.dataset.itemImage;
            document.getElementById('menuAvailable').checked = button.dataset.itemAvailable === '1';
            
            document.getElementById('menuModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('menuModal').classList.add('hidden');
            resetForm();
        }

        function resetForm() {
            document.getElementById('menuName').value = '';
            document.getElementById('menuDescription').value = '';
            document.getElementById('menuPrice').value = '';
            document.getElementById('menuImage').value = '';
            document.getElementById('menuAvailable').checked = true;
        }

        async function deleteMenuItem(itemId) {
            if (confirm('Apakah Anda yakin ingin menghapus menu ini?')) {
                try {
                    const response = await fetch(`/admin/menu/${itemId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (response.ok) {
                        window.location.reload();
                    } else {
                        alert('Gagal menghapus menu');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus menu');
                }
            }
        }

        // Close modal when clicking outside
        document.getElementById('menuModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>