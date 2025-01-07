<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css') 
</head>
<body class="bg-[#7C3AED] flex justify-center items-center h-screen">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/logo-uperfood-blue.png') }}" alt="UperFood Logo" class="h-20">
        </div>
        <h2 class="text-2xl font-bold text-purple-700 text-center mb-4">Masuk</h2>
        
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" 
                    value="{{ old('email') }}" 
                    required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" 
                    required>
            </div>
            <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition duration-300">
                Masuk 
            </button>
        </form>
        <p class="text-center text-gray-600 mt-6">
            Belum punya akun? <a href="{{ route('sign-up') }}" class="text-purple-600 font-semibold hover:underline">Daftar</a>
        </p>
    </div>
</body>
</html>