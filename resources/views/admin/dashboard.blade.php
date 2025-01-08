<!-- resources/views/admin/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - UperFood</title>
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
                        <img src="{{ asset('images/logo-uperfood-blue.png') }}" alt="UperFood" style="height:4rem">
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
            <h1 class="text-2xl font-semibold mb-6">Daftar Warung</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($warungs as $warung)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold">{{ $warung->name }}</h2>
                        <a href="{{ route('admin.warung.menu', $warung->id) }}" 
                           class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600">
                            Kelola Menu
                        </a>
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
</body>
</html>