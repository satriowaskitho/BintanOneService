<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bintan One Service</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('Logo Bintan One Service.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #printArea,
            #printArea * {
                visibility: visible;
            }

            @page {
                size: 58mm auto;
                /* Thermal printer size 58mm width */
                margin: 0;
            }

            #printArea {
                position: absolute;
                left: 50%;
                top: 0;
                transform: translateX(-50%);
                width: 58mm;
                padding: 2mm;
                margin: 0;
            }
        }
    </style>
</head>

<body class="bg-blue-50 flex items-center justify-center min-h-screen">
    <div
        class="w-full max-w-4xl bg-white shadow-2xl rounded-2xl overflow-hidden print:shadow-none print:w-[58mm] print:mx-auto">
        <div class="bg-blue-600 text-white p-6 text-center print:hidden flex flex-col items-center justify-center">
            <div class="flex items-center space-x-3 mb-2">
                <img src="{{ asset('Logo Bintan One Service.png') }}" alt="Logo"
                    class="w-16 h-16 object-contain brightness-0 invert">
                <h1 class="text-4xl font-bold tracking-wider">ONE Service</h1>
            </div>
            <p class="text-lg opacity-80">BPS Kabupaten Bintan</p>
        </div>

        <div class="p-8">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>

</html>
