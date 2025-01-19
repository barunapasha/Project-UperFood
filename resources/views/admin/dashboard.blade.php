<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - UperFood</title>
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/mountain-bg.jpg') }}"
            class="w-full h-full object-cover opacity-40"
            alt="Mountain Background">
    </div>
    <div class="min-h-screen fade-in relative z-10">
        <!-- Admin Navbar -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <img src="{{ asset('images/logo-uperfood-blue.png') }}" alt="UperFood" style="height: 4rem;">
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-black-800 mr-4">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium hover:underline transition-all">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r fade-in" role="alert">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r fade-in" role="alert">
                <p class="font-medium">{{ session('error') }}</p>
            </div>
            @endif

            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Daftar Warung</h1>
                <button onclick="openWarungModal()"
                    class="bg-purple-600 text-white px-6 py-3 rounded-xl hover:bg-purple-700 transform hover:scale-105 transition-all duration-300 shadow-md hover:shadow-xl flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Tambah Warung
                </button>
            </div>

            <!-- Warung Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($warungs as $warung)
                <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $warung->name }}</h2>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                    {{ $warung->location }}
                                </div>
                            </div>
                            <div class="bg-purple-100 rounded-full p-2">
                                <i class="fas fa-store text-purple-600 text-xl"></i>
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-600 mb-6 line-clamp-2">{{ $warung->description }}</p>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-xl p-3">
                                <div class="text-sm text-gray-500">Menu Items</div>
                                <div class="text-xl font-bold text-gray-800">
                                    {{ $warung->menuCategories->sum(function($category) {
                                        return $category->menuItems->count();
                                    }) }}
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3">
                                <div class="text-sm text-gray-500">Categories</div>
                                <div class="text-xl font-bold text-gray-800">
                                    {{ $warung->menuCategories->count() }}
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.warung.menu', $warung->id) }}"
                                class="flex-1 bg-purple-600 text-white py-3 rounded-xl hover:bg-purple-700 transition-colors duration-300 text-center font-medium shadow-md hover:shadow-xl">
                                <i class="fas fa-utensils mr-2"></i>
                                Kelola Menu
                            </a>
                            <button onclick="deleteWarung({{ $warung->id }})"
                                class="flex items-center justify-center px-4 bg-red-100 text-red-600 rounded-xl hover:bg-red-200 transition-colors duration-300">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Warung Modal -->
    <div id="warungModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full relative">
                <button onclick="closeWarungModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>

                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Tambah Warung Baru</h3>
                    <p class="text-gray-600">Lengkapi informasi warung di bawah ini</p>
                </div>

                <form id="warungForm" method="POST" action="{{ route('admin.warung.store') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Warung</label>
                        <input type="text" name="name" required
                            class="w-full px-4 py-3 rounded-xl border-gray-800 focus:border-purple-500 focus:ring-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3" required
                            class="w-full px-4 py-3 rounded-xl border-gray-800 focus:border-purple-500 focus:ring-purple-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                        <input type="text" name="location" required
                            class="w-full px-4 py-3 rounded-xl border-gray-800 focus:border-purple-500 focus:ring-purple-500">
                    </div>

                    <div class="flex justify-end space-x-3 pt-6">
                        <button type="button" onclick="closeWarungModal()"
                            class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors duration-300">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors duration-300">
                            Simpan Warung
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .backdrop-blur-md {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        .bg-gradient-overlay {
            background: linear-gradient(rgba(255, 255, 255, 0.1),
                    rgba(255, 255, 255, 0.2));
        }
    </style>

    <script>
        document.getElementById('warungForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    alert('Warung berhasil ditambahkan!');
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan saat menambahkan warung');
                }
            } catch (error) {
                console.error('Error:', error);
                alert(error.message);
            }
        });

        function openWarungModal() {
            document.getElementById('warungModal').classList.remove('hidden');
        }

        function closeWarungModal() {
            document.getElementById('warungModal').classList.add('hidden');
            document.getElementById('warungForm').reset();
        }

        function deleteWarung(id) {
            if (confirm('Apakah Anda yakin ingin menghapus warung ini?')) {
                // Implementation of delete functionality
                fetch(`/admin/warung/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Gagal menghapus warung');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus warung');
                    });
            }
        }
    </script>
</body>

</html>