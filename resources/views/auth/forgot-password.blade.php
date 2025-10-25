<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-indigo-100 to-purple-200 flex items-center justify-center min-h-screen">

    <!-- Forgot Password Card -->
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden">



        <!-- Body -->
        <div class="p-8">
            @if (session('status'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="Enter your email"
                        class="mt-2 w-full px-4 py-2 rounded-xl border border-gray-300
               focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition duration-300"
                    style="background-color: rgb(47, 138, 233);">
                    Send Reset Link
                </button>

                <!-- Back to Login Link -->
                <p class="mt-6 text-center text-gray-600 text-sm">
                    Remember your password?
                    <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:text-indigo-800">Log In</a>
                </p>
            </form>
        </div>
    </div>

</body>

</html>
