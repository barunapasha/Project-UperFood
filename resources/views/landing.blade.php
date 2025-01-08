<!DOCTYPE html>
<html lang="id" class="h-screen overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UperFood - Solusi Makan yang Super!</title>
    @vite('resources/css/app.css')
</head>
<body class="h-screen overflow-hidden">
    <div class="fixed inset-0 w-full h-full">
        <img src="{{ asset('images/background-sign.png') }}" alt="Background" class="w-full h-full object-cover">
    </div>

    <div class="relative z-10 h-full flex flex-col">
        <nav class="w-full bg-white shadow-sm">
            <div class="max-w-6xl mx-auto px-4 py-1">
                <div class="flex justify-between items-center">
                    <div class="w-36">
                        <img src="{{ asset('images/logo-uperfood-blue.png') }}" alt="UperFood Logo" class="w-full">
                    </div>
                    <div>
                        <a href="{{ route('login') }}" class="bg-[#7C3AED] text-white px-10 py-2.5 rounded-full text-lg font-medium hover:bg-[#6D28D9] transition duration-300">
                            Masuk/Daftar
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1 flex items-center justify-center">
            <div class="max-w-3xl mx-auto px-4">
                <div class="text-center text-white">
                    <img src="{{ asset('images/logo-uperfood-white.png') }}" alt="UperFood" class="w-64 mx-auto mb-6">
                    <h1 class="text-4xl font-bold mb-3">
                        Lapar di UPer? UPerFood, solusi makan yang Super!
                    </h1>
                    <h2 class="text-3xl font-light mb-6">
                        Ayok Mulai
                    </h2>
                    <a href="{{ route('information') }}" class="inline-flex items-center bg-white text-[#7C3AED] px-12 py-3 rounded-full text-xl font-medium hover:bg-gray-100 transition duration-300">
                        Lanjutkan
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </main>
    </div>
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