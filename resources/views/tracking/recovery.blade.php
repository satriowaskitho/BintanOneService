<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemulihan Tautan | Bintan One Service</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('Logo Bintan One Service.png') }}">
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-blue-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white shadow-xl rounded-3xl overflow-hidden p-8 text-center mx-4 my-8 border border-gray-100">
        
        <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-6">
            🔍
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-2">Pemulihan Tautan</h2>
        <p class="text-sm text-gray-500 mb-6">Tautan pelacakan Anda telah kedaluwarsa atau tidak valid. Silakan masukkan Kode Antrean atau Kode Booking Anda di bawah.</p>

        @if(session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded-xl text-xs font-semibold mb-6 text-left">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-xl text-xs font-semibold mb-6 text-left">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('tracking.recovery.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2 text-left">Kode Antrean / Kode Booking</label>
                <input type="text" name="code" placeholder="Contoh: A-001 atau Token Janji" required 
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 outline-none text-center font-mono font-bold text-lg">
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <!-- ACTION: TRACK -->
                <button type="submit" name="action_type" value="track" 
                        class="py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-all shadow-md">
                    🔎 Lacak
                </button>

                <!-- ACTION: RESEND EMAIL -->
                <button type="submit" name="action_type" value="resend" 
                        class="py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm border border-gray-200 transition-all">
                    ✉️ Kirim Ulang Email
                </button>
            </div>
        </form>

        <div class="mt-8 border-t border-gray-100 pt-6">
            <a href="{{ route('kiosk.scan') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">&larr; Kembali ke Scanner Kiosk</a>
        </div>
    </div>
</body>
</html>
