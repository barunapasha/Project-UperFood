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
    <div id="warungModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden, flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Tambah Warung Baru</h3>
                <button onclick="closeWarungModal()" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('admin.warung.store') }}" method="POST" class="space-y-4">
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

                <div>
                    <label class="block text-sm font-medium text-gray-700">URL Gambar (Opsional)</label>
                    <input type="text" name="image" placeholder="Kosongkan untuk menggunakan gambar default"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                </div>

                <div class="mt-5 sm:mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeWarungModal()"
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
        // Page load animation
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.add('fade-in');
        });

        // Modal functions
        function openWarungModal() {
            const modal = document.getElementById('warungModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.style.opacity = '1';
            }, 0);
        }

        function closeWarungModal() {
            const modal = document.getElementById('warungModal');
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.classList.add('hidden');
                // Reset form
                document.getElementById('warungForm').reset();
            }, 300);
        }

        function deleteWarung(warungId) {
            if (confirm('Apakah Anda yakin ingin menghapus warung ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/warung/${warungId}`;

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;

                form.appendChild(methodInput);
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modal when clicking outside
        document.getElementById('warungModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeWarungModal();
            }
        });

        // Form submission animation
        document.getElementById('warungForm').addEventListener('submit', function() {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<svg class="animate-spin h-5 w-5 mr-3 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Menyimpan...';
            submitButton.disabled = true;
        });

        // Success message auto-dismiss
        const successMessage = document.querySelector('.bg-green-100');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.opacity = '0';
                successMessage.style.transition = 'opacity 0.5s ease-in-out';
                setTimeout(() => {
                    successMessage.remove();
                }, 500);
            }, 3000);
        }

        // Error message auto-dismiss
        const errorMessage = document.querySelector('.bg-red-100');
        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.opacity = '0';
                errorMessage.style.transition = 'opacity 0.5s ease-in-out';
                setTimeout(() => {
                    errorMessage.remove();
                }, 500);
            }, 3000);
        }
    </script>
</body>

</html>