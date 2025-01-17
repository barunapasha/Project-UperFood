<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Kantin Bawah - UperFood</title>
    @vite('resources/css/app.css')
</head>

<body class="flex flex-col min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-2">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="hover:opacity-80 transition">
                        <img src="{{ asset('images/logo-uperfood-blue.png') }}" alt="UperFood" style="height: 9rem;">
                    </a>
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
                    <a href="{{ route('profile') }}" class="bg-purple-500 text-white rounded-full w-10 h-10 flex items-center justify-center">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center text-purple-600 hover:text-purple-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali ke Beranda
            </a>
        </div>

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Warung Kantin Bawah</h1>
            <p class="text-gray-600">Temukan berbagai pilihan makanan di Kantin Bawah</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($warungKantinBawah as $warung)
            <a href="{{ route('warung.detail', ['slug' => $warung['slug']]) }}" class="block hover:transform hover:scale-105 transition duration-300">
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ asset($warung['image']) }}"
                            alt="{{ $warung['name'] }}"
                            class="w-full h-48 object-cover transition duration-300">
                    </div>
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
            </a>
            @endforeach
        </div>
    </main>

    <!-- Footer yang diperbarui -->
    <footer class="bg-purple-500 text-white py-12 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-3 gap-12">
                <!-- Kolom 1: Informasi Alamat -->
                <div class="space-y-4">
                    <h3 class="text-xl font-bold mb-6">Universitas Pertamina</h3>
                    <div class="flex items-start space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <p class="leading-relaxed">
                            Jl. Teuku Nyak Arief, Simprug,<br>
                            Kec. Kby. Lama, Kota Jakarta Selatan,<br>
                            Daerah Khusus Ibukota Jakarta
                        </p>
                    </div>
                </div>

                <!-- Kolom 2: Logo -->
                <div class="flex flex-col items-center justify-center">
                    <img src="{{ asset('images/logo-uperfood-white.png') }}"
                        alt="UperFood"
                        class="mb-4 transform hover:scale-105 transition-transform duration-300"
                        style="height:9rem">
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="hover:text-purple-200 transition-colors">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#" class="hover:text-purple-200 transition-colors">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="#" class="hover:text-purple-200 transition-colors">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.897 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.897-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Kolom 3: Informasi Tambahan -->
                <div class="text-right">
                    <h3 class="text-xl font-bold mb-6">Makanan Lezat dan Enak di<br>
                        Lingkungan Universitas Pertamina</h3>
                    <div class="mt-4 space-y-2">
                        <p class="text-purple-200">Jam Operasional:</p>
                        <p>Senin - Jumat: 08:00 - 17:00</p>
                        <p>Sabtu: 08:00 - 15:00</p>
                    </div>
                    <p class="mt-8">© UperFood 2024</p>
                </div>
            </div>
        </div>
    </footer>
    <script>
        // Fade in animation for page load
        document.addEventListener('DOMContentLoaded', function() {
            document.body.style.opacity = '0';
            setTimeout(() => {
                document.body.style.transition = 'opacity 0.5s ease-in';
                document.body.style.opacity = '1';
            }, 0);
        });

        // Smooth hover animations for cards
        const cards = document.querySelectorAll('.bg-white.rounded-lg');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.transition = 'all 0.3s ease';
                this.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '';
            });
        });

        // Modal animations
        function openWarungModal() {
            const modal = document.getElementById('warungModal');
            modal.classList.remove('hidden');
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.style.transition = 'opacity 0.3s ease-in';
                modal.style.opacity = '1';
            }, 0);
        }

        function closeWarungModal() {
            const modal = document.getElementById('warungModal');
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function openCategoryModal() {
            const modal = document.getElementById('categoryModal');
            modal.classList.remove('hidden');
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.style.transition = 'opacity 0.3s ease-in';
                modal.style.opacity = '1';
            }, 0);
        }

        function closeCategoryModal() {
            const modal = document.getElementById('categoryModal');
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function openAddModal(categoryId) {
            const modal = document.getElementById('menuModal');
            modal.classList.remove('hidden');
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.style.transition = 'opacity 0.3s ease-in';
                modal.style.opacity = '1';
            }, 0);
            // ... rest of the existing openAddModal logic ...
        }

        function closeModal() {
            const modal = document.getElementById('menuModal');
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
            resetForm();
        }

        // Button hover animations
        const buttons = document.querySelectorAll('button');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
                this.style.transition = 'all 0.2s ease';
            });

            button.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });

        // Smooth scroll for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add loading animation for form submissions
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitButton = this.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.innerHTML = '<span class="animate-spin">↻</span> Loading...';
                    submitButton.disabled = true;
                }
            });
        });
    </script>
</body>

</html>