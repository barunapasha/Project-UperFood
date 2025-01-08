<!DOCTYPE html>
<html lang="id" class="h-screen overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UperFood</title>
    @vite('resources/css/app.css')
</head>
<body class="h-screen bg-[#7C3AED] flex justify-center items-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-8">
        <div class="flex items-center mb-6">
            <a href="{{ route('information') }}" class="text-[#7C3AED]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <img src="{{ asset('images/logo-uperfood-blue.png') }}" alt="UperFood Logo" class="h-24 mx-auto">
        </div>

        <h1 class="text-3xl font-bold text-center text-[#7C3AED] mb-2">Daftar</h1>
        <p class="text-center text-gray-500 mb-6">Daftar menggunakan email anda</p>

        <form method="POST" action="{{ route('sign-up.store') }}">
            @csrf

            <div class="mb-4 relative">
                <input type="text" name="name" placeholder="Name" class="w-full border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#7C3AED]" required>
            </div>

            <div class="mb-4 relative">
                <input type="email" name="email" placeholder="Email Id or Username" class="w-full border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#7C3AED]" required>
            </div>

            <div class="mb-4 relative">
                <input type="password" name="password" placeholder="Password" class="w-full border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#7C3AED]" required>
                <div class="absolute inset-y-0 right-4 flex items-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825a10.05 10.05 0 01-7.963-7.963M15.225 4.775A10.05 10.05 0 0118.825 8.925M10.3 10.3A4.325 4.325 0 0012 12m0-4.325A4.325 4.325 0 0010.3 10.3m4.4 0A4.325 4.325 0 0012 12m0-4.325V12m0-4.325a4.325 4.325 0 00-1.7-.625m-3.175 8.45a4.325 4.325 0 004.875-4.875" />
                    </svg>
                </div>
            </div>

            <div class="mb-6 relative">
                <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#7C3AED]" required>
                <div class="absolute inset-y-0 right-4 flex items-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825a10.05 10.05 0 01-7.963-7.963M15.225 4.775A10.05 10.05 0 0118.825 8.925M10.3 10.3A4.325 4.325 0 0012 12m0-4.325A4.325 4.325 0 0010.3 10.3m4.4 0A4.325 4.325 0 0012 12m0-4.325V12m0-4.325a4.325 4.325 0 00-1.7-.625m-3.175 8.45a4.325 4.325 0 004.875-4.875" />
                    </svg>
                </div>
            </div>

            <div class="mb-6">
                <button type="submit" class="w-full bg-[#7C3AED] text-white px-6 py-3 rounded-full text-lg font-medium hover:bg-[#6D28D9] transition duration-300">
                    Buat Akun
                </button>
            </div>

            <div class="text-center text-gray-400 text-sm mb-4">atau</div>

            <div class="space-y-4">
                <button type="button" class="w-full flex items-center justify-center border border-gray-300 rounded-full px-4 py-2 hover:bg-gray-100 transition duration-300">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/120px-Google_%22G%22_Logo.svg.png" alt="Google" class="h-5 w-5 mr-2">
                    Lanjutkan dengan Google
                </button>

                <button type="button" class="w-full flex items-center justify-center border border-gray-300 rounded-full px-4 py-2 hover:bg-gray-100 transition duration-300">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/05/Facebook_Logo_%282019%29.png" alt="Facebook" class="h-5 w-5 mr-2">
                    Lanjutkan dengan Facebook
                </button>

                <button type="button" class="w-full flex items-center justify-center border border-gray-300 rounded-full px-4 py-2 hover:bg-gray-100 transition duration-300">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="Apple" class="h-5 w-5 mr-2">
                    Lanjutkan dengan Apple
                </button>
            </div>
        </form> 
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
