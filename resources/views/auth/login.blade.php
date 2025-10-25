<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-to-br from-indigo-100 to-purple-200 flex items-center justify-center min-h-screen">

    <!-- Login Card -->
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden">

        <!-- Header -->
        <div class="p-6 flex flex-col items-center" style="background-color: rgb(47, 138, 233);">
            <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxIHEhIQEhIVEhUWEBIVERYVERAQERAXGRYWFxYRFRMYHSkgGBoqGxMXITIhJSkrOi46FyAzODMsNygtLisBCgoKBQUFDgUFDisZExkrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrK//AABEIAOEA4QMBIgACEQEDEQH/xAAbAAEAAgMBAQAAAAAAAAAAAAAABQYCBAcBA//EAD4QAAIBAQMHCQYEBQUAAAAAAAABAgMEBREGEiEiMVFxFDJBUmGBkaHBE0KCkrHRFWJyoiMzQ8LxJFNjc+L/xAAUAQEAAAAAAAAAAAAAAAAAAAAA/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8AlQAAAAAAAAAAAAAAAAbVlu6ta+ZTnJb1F5vzPQb8Ml7VP3FHjOHo2BDAmp5LWqPuRfCcfXA0rTdVey8+lNLpeGdFd6xQGkAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFiyayf5fhVqrCmnqrY6n/n6gaNz3HVvTStWGOmbWj4V7zLld2T1Cw4PNz5daes+5bESkIKmkkkklgklgktyRkAAAAAAR143JQvDHOglLrR1Z8cenvxKdfOTlS7cZx/iU+slrR/VH1+h0IAcjBbMpsnVTTr0Vo21ILo3yivQqYAAAAAAAAAAAAAAAAAAAAAAAAEnk/df4pVUXzI6aj7OiPF/c6PCCppJLBJJJLQklsSIrJawchoRxWtPXlv07F3LDzJcAAAAAAAAAAABQMq7o/D6mfBYU5t4LohLa48Old+4v5pXxYleFGdPpa1XuktMX4gcwB61hoejf2dh4AAAAAAAAAAAAAAAAAAAA2bus/KqtOn1pxT4Y6fLE1iYyThn2qn2Z7/Y/uB0RaAAAAAAAAAAAAAAAAc3yls/JrTVS2Nqa+JYvzxIsseXMMK8HvorylL7lcAAAAAAAAAAAAAAAAAAAATGSUs21U+1TX7G/Qhzauu0clrUqnRGpHHhjg/JsDqQAAAAAAAAAAAAAAAKPl1LGvBbqK85S+xWyWyotHKLTU3RagvhWn92JEgAAAAAAAAAAAAAAAAAAAAAHSMm7dy+hCWOtFZk+K6e9YPvJQ53kzev4ZV1n/Dngp/l3T7vU6GniB6AAAAAAAAAABq3nbFYKU6r92Ohb3sS8cDaKNlhe3K5+xg8YQes+tPZ4LSu9gV6cnNtt4tttve3tZiAAAAAAAAAAAAAAAAAAAAAAAC05L5Qqz4UKz1dlOb9z8snu3Po4bKsegdbTxBz65coql24Ql/Ep7m9aP6Xu7H5Fyu696N4rUmseq9Wa+Hp4oDfAAAAxbx2AZA0LVeNOwaak1Hs2yfCK0sql85UztmMKWNOHS/6kvDmrh4gSWU2USop0aLxlsnJe5vjF9b6cdlLAAAAAAAAAAAAAAAAAAAAAAAAAA9R79zEAZA+9lu+ra/5dOUu1Reb82wlaGSdpq7cyH6p4v9uIGpZr7tFmwSqya3SwmuGtizdp5WV44Yxpv4Zr6SNunkXJ86slwpt+bkj7xyLgttaXdGK9QI6eV1ea5tNfDN/3Gnab+tFo21HFYe4lDzWknnkXD/el8sWfCpkU/drrvp4eakBVpNyeL0trS3pb26cTx+mP1J6vkjaKfNcJ8JNPzWHmRdquuvZOfSmlvwzo/MsUBqmIxAAAAAAAAAAAAAAAAAAAAAAAM6NKVdqMYuTexJNvwRO3NkvUtuE6mNOG7+pLgnzV2vwLlYLvp3fHNpwUd72ylxltYFTu7JCpWwdaXs11VhKfe9i8yx2G4bPYsM2mm+tPXlx06F3YEmAAAAAAAAAAAA0Lbc9C3c+nHHrLVl8y0srt4ZHSji6M878s9D7pLQ+9IuIA5TarNOySzakXB7msMe1PpXA+J1a1WWFsjmVIqS3NbO1PofaVC+Mk5UMZ0MZx6YPnr9L97ht4gVgHrWGh6N+9dh4AAAAAAAAAAAAA+tnoStMlCCzpSeCSAxo0pV5KEU5SbwSW1l4uHJqNhwqVcJ1NqW2NPhvfb4G3cNyRuqOOiVRrWlu/LHcvr9JYAAAAAAAAAAAAAAAAAAAAAAhr8uCF5pyWpUw0Sw0S7JLp4/4KHbLJOxTdOpHNkvBren0o6qaF73VC9YZstDXMktsH6regOZA2bwsU7vm6c1g1s3SXRJPcawAAAAAAAAHsYuTSSxbeCS0tvoSOg5N3KrshnS01JLWfVXUXqRWRt0Y/6ma3qkn4Ofou/sLeAAAAAAAAAAAAAAAAAAAAAAAAAAAEdfl0xvWnmvRJYunLqvc+x9Jzi0UJWaUoTWEovBo6wVzK+6OVQ9tBa8FrYe/H7rb49gFGAAAAADeua73edWNPo2ze6K2/bvNEvmRt38lo+1a1qmnhBc1d+l96AnqcFSSilgkkklsSWxGQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABzrKa7Pw2s81ak8ZQ3LrQ7sfBoiDpGUl3/AIhQkktaOtDitq71ijm4AAAbN3WV26rCkvekk+xbZPwTOowioJJLBJJJbkugpeQ1l9pUqVX7kVFcZdPhHzLsAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAObZRWLkNonFLCLefHhLTh3PFdx0kquXdlzo06q6JOD4PSvNPxApoAAv2RdD2VmUuvOUvDV/tJ40bkpexs9GP/FBvi1i/Nm8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAIzKShyizVVuhnL4db0JMwqw9rFxexpp96wA5MDPkk9wA6lYf5dP/rh9EfcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAKUAAP/9k="
                alt="Profile Image" class="w-24 h-24 rounded-full border-4 border-white shadow-lg mb-4">
            <h2 class="text-white text-2xl font-bold">Welcome Back!</h2>
            <p class="text-white 200 mt-1">Login to your account</p>
        </div>

        <!-- Body -->
        <div class="p-8">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email or Phone -->
                <div class="mb-4">
                    <label for="email_or_phone" class="block text-gray-700 font-medium">Email or Phone</label>
                    <input id="email_or_phone" type="text" name="email_or_phone" value="{{ old('email_or_phone') }}"
                        required autofocus placeholder="Enter your email or phone number"
                        class="mt-2 w-full px-4 py-2 rounded-xl border border-gray-300
               focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    @error('email_or_phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>



                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium">Password</label>
                    <div class="relative mt-2">
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">

                        <!-- Eye Icon -->
                        <span onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-500">
                            <i id="eyeIcon" class="mdi mdi-eye-off"></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="remember_me" class="ml-2 text-gray-600 text-sm">Remember me</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Forgot password?</a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition duration-300"
                    style="background-color: rgb(47, 138, 233);">
                    Log In
                </button>
                <!-- Sign Up Link -->
                {{-- <p class="mt-6 text-center text-gray-600 text-sm">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-indigo-600 font-medium hover:text-indigo-800">Sign
                        Up</a>
                </p> --}}
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("mdi-eye-off");
                eyeIcon.classList.add("mdi-eye");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("mdi-eye");
                eyeIcon.classList.add("mdi-eye-off");
            }
        }
    </script>

</body>

</html>
