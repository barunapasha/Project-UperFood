<!DOCTYPE html>
<html lang="id" class="h-screen overflow-hidden">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UperFood - Informasi</title>
    @vite('resources/css/app.css')
</head>

<body class="h-screen overflow-hidden">
    <div class="fixed inset-0 w-full h-full bg-[#7C3AED]">
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
                    <img src="{{ asset('images/aset-icon1.png') }}" alt="Delivery Illustration" class="w-96 mx-auto mb-6">
                    <h1 class="text-3xl font-bold mb-3">
                        Halo, Selamat Datang di UPerFood!
                    </h1>
                    <h2 class="text-xl mb-8 max-w-xl mx-auto">
                        Website yang membantu anda mencari makanan di kantin Univeristas Pertamina.
                    </h2>
                    <div class="space-y-4">
                        <a href="{{ route('login') }}" class="block bg-white text-[#7C3AED] px-12 py-3 rounded-full text-xl font-medium hover:bg-gray-100 transition duration-300">
                            Masuk
                        </a>
                        <a href="{{ route('sign-up') }}" class="block text-white hover:underline">
                            Belum ada Akun? Daftarlah
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        // Fade in animation for page load
        document.addEventListener('DOMContentLoaded', function() {
            document.body.style.opacity = '0';
            setTimeout(() => {
                document.body.style.transition = 'opacity 0.8s ease-in';
                document.body.style.opacity = '2';
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