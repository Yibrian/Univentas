<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />


    <!--CSS-->
    @stack('css')
    <style>
        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Para Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

    </style>



    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    @livewireStyles
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <livewire:layout.navigation />

        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif


        <main>
            {{ $slot }}
        </main>
    </div>



    <footer class="bg-red-700 shadow">
        <div class="w-full mx-auto max-w-screen-xl p-4 flex items-center justify-between">
            <span class="text-sm text-white hover:text-gray-300">&copy; {{ date('Y') }} <a href="#"
                    class="hover:underline">Carlos Villamil</a> - All Rights Reserved.</span>
            <div class="ml-auto">
                <span class="text-xs text-white hover:text-gray-300">UNIVENTAS - Ecommerce desarrollado para la Universidad del Valle</span>
            </div>
        </div>
    </footer>






    @stack('js')




</body>

</html>
