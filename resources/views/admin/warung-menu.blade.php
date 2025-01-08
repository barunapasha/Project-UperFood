<!-- resources/views/admin/warung-menu.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                            <img src="{{ asset('images/logo-uperfood-blue.png') }}" alt="UperFood" class="h-12">
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

            @foreach($warung->menuCategories as $category)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">{{ $category->name }}</h2>

                <!-- Menu Items Table -->
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Nama</th>
                            <th class="text-left py-2">Deskripsi</th>
                            <th class="text-left py-2">Harga</th>
                            <th class="text-left py-2">Status</th>
                            <th class="text-left py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($category->menuItems as $item)
                        <tr class="border-b">
                            <td class="py-2">{{ $item->name }}</td>
                            <td class="py-2">{{ $item->description }}</td>
                            <td class="py-2">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="py-2">
                                @if($item->is_available)
                                <span class="text-green-500">Tersedia</span>
                                @else
                                <span class="text-red-500">Tidak Tersedia</span>
                                @endif
                            </td>
                            <td class="py-2">
                                <button onclick="openEditModal({{ $item->id }})"
                                    class="text-blue-500 hover:text-blue-700 mr-2">
                                    Edit
                                </button>
                                <button onclick="deleteMenuItem({{ $item->id }})"
                                    class="text-red-500 hover:text-red-700">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <button onclick="openAddModal({{ $category->id }})"
                    class="mt-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Tambah Menu Baru
                </button>
            </div>
            @endforeach
        </div>
    </div>
</body>

</html>