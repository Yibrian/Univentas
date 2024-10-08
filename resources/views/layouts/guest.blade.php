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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        @media (max-width: 767px) {
            .hide-on-mobile {
                display: none !important;
            }
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

    @stack('css')


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans text-gray-900 antialiased">

    <nav class="bg-red-700 h-16 w-full flex items-center">
        <div class="flex items-center ml-4">
            <div class="h-12 w-20">
                <a href="#" wire:navigate>
                    <x-application-logo-invert class="w-20 h-12 fill-current text-gray-500" />
                </a>
            </div>
            <span class="ml-4 text-white text-lg font-bold">UNIVENTAS</span>
        </div>
        
    </nav>
    
    
    
    

    <div class="flex flex-col min-h-screen">
        <!-- Contenido principal -->
        <div class="flex-grow flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="w-full sm:max-w-2xl h-auto sm:min-h-full px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg mt-4 mb-2">
                <div class="w-full h-full">
                    {{ $slot }}
                </div>
            </div>
        </div>
    
        <!-- Footer -->
        <footer class="bg-red-700 text-white py-4">
            <div class="container mx-auto text-center">
                <p class="text-sm">&copy; {{ date('Y') }} Software CYJ. All rights reserved.</p>
                <div class="mt-2">
                    <a href="#" class="text-white hover:text-gray-300 mx-2">Política de privacidad</a>
                    <a href="#" class="text-white hover:text-gray-300 mx-2">Contáctanos</a>
                </div>
            </div>
        </footer>
    </div>
    
    

    @stack('js')
    @livewireScripts

</body>

</html>
