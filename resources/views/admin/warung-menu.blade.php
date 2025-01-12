<!-- resources/views/admin/warung-menu.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu {{ $warung->name }} - UperFood Admin</title>
    @vite('resources/css/app.css')
    <style>
        #menuModal {
            transition: opacity 0.3s ease-in-out;
        }

        #menuModal.hidden {
            opacity: 0;
            pointer-events: none;
        }

        #menuModal:not(.hidden) {
            opacity: 1;
        }
    </style>
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
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-purple-600 hover:text-purple-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Kembali ke Dashboard
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

            <!-- Tambahkan section ini untuk kategori default jika belum ada kategori -->
            @if($warung->menuCategories->isEmpty())
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V7a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada kategori menu</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat kategori menu baru.</p>
                    <div class="mt-6">
                        <button onclick="openCategoryModal()"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Tambah Kategori Menu
                        </button>
                    </div>
                </div>
            </div>
            @else
            @foreach($warung->menuCategories as $category)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center space-x-4">
                        <h2 class="text-xl font-semibold">{{ $category->name }}</h2>
                        <!-- Tambah button hapus kategori -->
                        <button
                            onclick="deleteCategory('{{ $category->id }}', '{{ $category->name }}')"
                            class="text-red-500 hover:text-red-700 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <button
                        data-category-id="{{ $category->id }}"
                        onclick="openAddModal(this.dataset.categoryId)"
                        class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 transition duration-300 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Menu
                    </button>
                </div>

                @if($category->menuItems->isEmpty())
                <div class="text-center py-6">
                    <p class="text-gray-500">Belum ada menu dalam kategori ini.</p>
                </div>
                @else
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
                @endif
            </div>
            @endforeach

            <!-- Button untuk menambah kategori baru -->
            <div class="mt-4">
                <button onclick="openCategoryModal()"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah Kategori Menu
                </button>
            </div>
            @endif
        </div>

        <!-- Menu Modal (Add/Edit) -->
        <div id="menuModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden" style="z-index: 50;">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg p-6 max-w-md w-full relative">
                    <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="mb-4">
                        <h3 class="text-lg font-medium" id="modalTitle">Tambah Menu Baru</h3>
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
        </div>

        <!-- Category Modal -->
        <div id="categoryModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden" style="z-index: 50;">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg p-6 max-w-md w-full relative">
                    <button onclick="closeCategoryModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="mb-4">
                        <h3 class="text-lg font-medium">Tambah Kategori Menu</h3>
                    </div>

                    <form id="categoryForm" action="{{ route('admin.category.store', $warung->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                            <input type="text" name="name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeCategoryModal()"
                                class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script>
            // Menu Modal Functions
            function openAddModal(categoryId) {
                document.getElementById('modalTitle').textContent = 'Tambah Menu Baru';
                const form = document.getElementById('menuForm');
                form.action = `/admin/warung/{{ $warung->id }}/category/${categoryId}/menu`;
                form.method = 'POST';
                document.getElementById('methodField').innerHTML = '';
                resetForm();
                showModal();
            }

            function openEditModal(button) {
                const itemId = button.dataset.itemId;
                document.getElementById('modalTitle').textContent = 'Edit Menu';
                const form = document.getElementById('menuForm');
                form.action = `/admin/menu/${itemId}`;
                form.method = 'POST';
                document.getElementById('methodField').innerHTML = '@method("PUT")';

                document.getElementById('menuName').value = button.dataset.itemName;
                document.getElementById('menuDescription').value = button.dataset.itemDescription;
                document.getElementById('menuPrice').value = button.dataset.itemPrice;
                document.getElementById('menuAvailable').checked = button.dataset.itemAvailable === '1';

                showModal();
            }

            function showModal() {
                const modal = document.getElementById('menuModal');
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                const modal = document.getElementById('menuModal');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                resetForm();
            }

            function resetForm() {
                const form = document.getElementById('menuForm');
                form.reset();
                document.getElementById('menuName').value = '';
                document.getElementById('menuDescription').value = '';
                document.getElementById('menuPrice').value = '';
                document.getElementById('menuAvailable').checked = true;
            }

            // Category Modal Functions
            function openCategoryModal() {
                const modal = document.getElementById('categoryModal');
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeCategoryModal() {
                const modal = document.getElementById('categoryModal');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                document.getElementById('categoryForm').reset();
            }

            // Event Listeners
            document.addEventListener('DOMContentLoaded', function() {
                // Menu Form Submission
                document.getElementById('menuForm').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    try {
                        const response = await fetch(this.action, {
                            method: this.method,
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            alert('Menu berhasil disimpan!');
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan saat menyimpan menu');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert(error.message);
                    }
                });

                // Category Form Submission
                document.getElementById('categoryForm').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            alert('Kategori berhasil ditambahkan!');
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan saat menambahkan kategori');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert(error.message);
                    }
                });

                // Close Modals on Outside Click
                [document.getElementById('menuModal'), document.getElementById('categoryModal')].forEach(modal => {
                    modal?.addEventListener('click', function(e) {
                        if (e.target === this) {
                            if (this.id === 'menuModal') closeModal();
                            else closeCategoryModal();
                        }
                    });
                });

                // Close Modals with Escape Key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        if (!document.getElementById('menuModal')?.classList.contains('hidden')) {
                            closeModal();
                        }
                        if (!document.getElementById('categoryModal')?.classList.contains('hidden')) {
                            closeCategoryModal();
                        }
                    }
                });
            });

            // Delete Menu Function
            async function deleteMenuItem(itemId) {
                if (confirm('Apakah Anda yakin ingin menghapus menu ini?')) {
                    try {
                        const response = await fetch(`/admin/menu/${itemId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            alert('Menu berhasil dihapus!');
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan saat menghapus menu');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert(error.message);
                    }
                }
            }

            async function deleteCategory(categoryId, categoryName) {
                if (confirm(`Apakah Anda yakin ingin menghapus kategori "${categoryName}"?\nSemua menu dalam kategori ini juga akan dihapus.`)) {
                    try {
                        const response = await fetch(`/admin/category/${categoryId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin'
                        });

                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }

                        const data = await response.json();
                        if (data.success) {
                            alert('Kategori berhasil dihapus!');
                            window.location.reload();
                        } else {
                            throw new Error(data.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert(error.message || 'Terjadi kesalahan saat menghapus kategori');
                    }
                }
            }

            // Menu Form Submission
            document.getElementById('menuForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                formData.set('is_available', document.getElementById('menuAvailable').checked ? '1' : '0');

                try {
                    const response = await fetch(this.action, {
                        method: this.method,
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });

                    const data = await response.json();

                    if (response.ok) {
                        alert('Menu berhasil disimpan!');
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan saat menyimpan menu');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert(error.message);
                }
            });
        </script>
</body>

</html>