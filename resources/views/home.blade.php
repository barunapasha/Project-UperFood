<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UperFood - Universitas Pertamina</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-2">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo-uperfood-blue.png') }}" alt="UperFood" class="h-20">
                </div>
                
                <div class="flex-1 mx-8">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Cari makanan atau warung" 
                               class="w-full px-4 py-2 pl-10 bg-gray-100 rounded-full">
                        <span class="absolute left-3 top-2.5 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 bg-white rounded-full px-4 py-1.5 border">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <span>Universitas Pertamina</span>
                    </div>
                    <button class="bg-purple-500 text-white rounded-full w-10 h-10">
                        HA
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="container mx-auto px-4 mt-4">
        <p class="text-sm text-gray-600">
            Main Page / Warung Universitas Pertamina
        </p>
    </div>

    <!-- Categories -->
    <div class="container mx-auto px-4 mt-6">
        <div class="grid grid-cols-4 gap-4">
            @foreach($categories as $category)
            <div class="relative rounded-lg overflow-hidden shadow-md">
                <img src="{{ asset($category['image']) }}" 
                     alt="{{ $category['name'] }}"
                     class="w-full h-48 object-cover">
                <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black">
                    <h3 class="text-white font-bold">{{ $category['name'] }}</h3>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Warung Kantin Atas -->
    <div class="container mx-auto px-4 mt-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Warung Kantin Atas</h2>
            <a href="#" class="text-purple-500">Tampilkan semua warung</a>
        </div>

        <div class="grid grid-cols-3 gap-4">
            @foreach($warungKantinAtas as $warung)
            <div class="bg-white rounded-lg overflow-hidden shadow-md">
                <img src="{{ asset($warung['image']) }}" 
                     alt="{{ $warung['name'] }}"
                     class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="font-bold text-lg">{{ $warung['name'] }}</h3>
                    <p class="text-gray-600 text-sm">{{ $warung['description'] }}</p>
                    <div class="flex items-center mt-2">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="ml-1">{{ $warung['rating'] }}</span>
                        </div>
                        <div class="flex items-center ml-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-1">{{ $warung['distance'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Warung Kantin Bawah -->
    <div class="container mx-auto px-4 mt-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Warung Kantin Bawah</h2>
            <a href="#" class="text-purple-500">Tampilkan semua warung</a>
        </div>

        <div class="grid grid-cols-3 gap-4">
            @foreach($warungKantinBawah as $warung)
            <div class="bg-white rounded-lg overflow-hidden shadow-md">
                <img src="{{ asset($warung['image']) }}" 
                     alt="{{ $warung['name'] }}"
                     class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="font-bold text-lg">{{ $warung['name'] }}</h3>
                    <p class="text-gray-600 text-sm">{{ $warung['description'] }}</p>
                    <div class="flex items-center mt-2">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="ml-1">{{ $warung['rating'] }}</span>
                        </div>
                        <div class="flex items-center ml-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-1">{{ $warung['distance'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-purple-500 text-white mt-12 py-8">
        <div class="container mx-auto px-4 grid grid-cols-3 gap-8">
            <div>
                <h3 class="font-bold mb-4">Universitas Pertamina</h3>
                <p>Jl. Teuku Nyak Arief, Simprug,<br>
                   Kec. Kby. Lama, Kota Jakarta Selatan,<br>
                   Daerah Khusus Ibukota Jakarta</p>
            </div>
            <div class="flex justify-center">
                <img src="{{ asset('images/logo-uperfood-white.png') }}" alt="UperFood" class="h-20">
            </div>
            <div class="text-right">
                <h3 class="font-bold mb-4">Makanan Lezat dan Enak di<br>
                    Lingkungan Universitas Pertamina</h3>
                <p>© UperFood 2024</p>
            </div>
        </div>
    </footer>
</body>
</html>