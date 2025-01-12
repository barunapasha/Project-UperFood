<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - UperFood</title>
    @vite('resources/css/app.css')
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

        .hover-transform {
            transition: all 0.3s ease;
        }

        .hover-transform:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .modal-transition {
            transition: opacity 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen fade-in">
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
                            <button type="submit" class="text-red-500 hover:text-red-700 hover-transform">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 fade-in">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 fade-in">
                {{ session('error') }}
            </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Daftar Warung</h1>
                <button onclick="openWarungModal()" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-colors duration-300">
                    + Tambah Warung
                </button>
            </div

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($warungs as $warung)
            <div class="bg-white rounded-lg shadow p-6 hover-transform">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">{{ $warung->name }}</h2>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.warung.menu', $warung->id) }}"
                            class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 hover-transform transition-colors duration-300">
                            Kelola Menu
                        </a>
                        <button onclick="deleteWarung({{ $warung->id }})"
                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 hover-transform transition-colors duration-300">
                            Hapus
                        </button>
                    </div>
                </div>
                <div class="text-gray-600">
                    <p>{{ $warung->description }}</p>
                    <p class="mt-2">Lokasi: {{ $warung->location }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    </div>

    <!-- Warung Modal -->
    <div id="warungModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden" style="z-index: 50;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg p-6 max-w-md w-full relative">
                <!-- Close button -->
                <button onclick="closeWarungModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="mb-4">
                    <h3 class="text-lg font-medium">Tambah Warung Baru</h3>
                </div>

                <form id="warungForm" method="POST" action="{{ route('admin.warung.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Warung</label>
                        <input type="text" name="name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                        <input type="text" name="location" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeWarungModal()"
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
                },
                credentials: 'same-origin'
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
            alert(error.message || 'Terjadi kesalahan saat menambahkan warung');
        }
    });

    function openWarungModal() {
        document.getElementById('warungModal').classList.remove('hidden');
    }

    function closeWarungModal() {
        document.getElementById('warungModal').classList.add('hidden');
        document.getElementById('warungForm').reset();
    }
</script>
</body>

</html>