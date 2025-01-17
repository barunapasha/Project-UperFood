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

    <!-- Footer -->
    <footer class="bg-purple-500 text-white py-8 mt-auto">
        <div class="container mx-auto grid grid-cols-3 gap-8 px-4">
            <div>
                <h3 class="font-bold mb-4">Universitas Pertamina</h3>
                <p>Jl. Teuku Nyak Arief, Simprug,<br>
                    Kec. Kby. Lama, Kota Jakarta Selatan,<br>
                    Daerah Khusus Ibukota Jakarta</p>
            </div>
            <div class="flex justify-center">
                <img src="{{ asset('images/logo-uperfood-white.png') }}" alt="UperFood" style="height: 7rem;">
            </div>
            <div class="text-right">
                <h3 class="font-bold mb-4">Makanan Lezat dan Enak di<br>
                    Lingkungan Universitas Pertamina</h3>
                <p>© UperFood 2024</p>
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