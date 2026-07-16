<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pattaya Apartment</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('rooms.index') }}" class="text-xl font-bold text-indigo-600">Pattaya Apartment</a>
                </div>

                <!-- Language Switcher -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="text-sm {{ app()->getLocale() == 'en' ? 'font-bold text-indigo-600 underline' : 'text-gray-500' }}">EN</a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('lang.switch', 'th') }}"
                        class="text-sm {{ app()->getLocale() == 'th' ? 'font-bold text-indigo-600 underline' : 'text-gray-500' }}">TH
                        (ไทย)</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-10">
        @yield('content')
    </main>

</body>

</html>
