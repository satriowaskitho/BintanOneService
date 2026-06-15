<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Janji Temu | Bintan One Service</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('Logo Bintan One Service.png') }}">
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-blue-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white shadow-xl rounded-3xl overflow-hidden p-8 text-center mx-4 my-8 border border-gray-100">
        
        <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">B-ONE Appointment Tracking</span>
            <span class="px-2.5 py-1 text-xs font-extrabold rounded-full bg-emerald-100 text-emerald-800 uppercase">
                Jadwal Janji
            </span>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-2">Status Janji Temu</h2>
        
        <div class="my-6">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                📅
            </div>
            <p class="font-bold text-2xl text-gray-800">{{ $appointment->serviceType->name }}</p>
        </div>

        <!-- STATUS BAR -->
        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 mb-6 text-left space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-gray-500 text-sm font-semibold">Status:</span>
                @if($appointment->status == 'scheduled')
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 font-extrabold rounded-full text-xs uppercase shadow-sm">⏳ Terjadwal</span>
                @elseif($appointment->status == 'checked_in')
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 font-extrabold rounded-full text-xs uppercase shadow-sm animate-pulse">📢 Check-in</span>
                @elseif($appointment->status == 'completed')
                    <span class="px-3 py-1 bg-green-100 text-green-800 font-extrabold rounded-full text-xs uppercase shadow-sm">✅ Selesai</span>
                @else
                    <span class="px-3 py-1 bg-red-100 text-red-800 font-extrabold rounded-full text-xs uppercase shadow-sm">❌ Batal</span>
                @endif
            </div>

            <div class="border-t border-gray-200 pt-3 space-y-2 text-sm text-gray-700">
                <div class="flex justify-between">
                    <span class="text-gray-400">Tanggal:</span>
                    <span class="font-bold">{{ $appointment->date->format('d F Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Jam:</span>
                    <span class="font-bold">{{ substr($appointment->time, 0, 5) }} WIB</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Keperluan:</span>
                    <span class="font-bold text-right max-w-[200px] truncate" title="{{ $appointment->purpose }}">{{ $appointment->purpose }}</span>
                </div>

            </div>
        </div>

        @if($appointment->status == 'scheduled')
            <div class="bg-blue-50 border border-blue-200 text-blue-700 p-4 rounded-2xl text-xs leading-relaxed mb-6">
                💡 <strong>Informasi:</strong> Silakan hadir di BPS Kabupaten Bintan 10 menit sebelum jadwal Anda dan lakukan <strong>Check-In</strong> di Kiosk Face Recognition.
            </div>
        @endif

        <!-- Email Resend Cooldown Option -->
        <div class="border-t border-gray-100 pt-6">
            <p class="text-xs text-gray-500 mb-3">Kehilangan tiket email Anda?</p>
            
            @if(session('status'))
                <div class="text-green-700 bg-green-50 border border-green-200 p-2.5 rounded-lg text-xs font-semibold mb-3">
                    {{ session('status') }}
                </div>
            @endif
            @if($errors->any())
                <div class="text-red-700 bg-red-50 border border-red-200 p-2.5 rounded-lg text-xs font-semibold mb-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('kiosk.appointment.resend-email', $appointment->token) }}" method="POST">
                @csrf
                <button type="submit" class="text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-600 font-bold py-2 px-4 rounded-lg border border-emerald-200 transition-colors">
                    ✉️ Kirim Ulang Email Konfirmasi
                </button>
            </form>
        </div>

        <p class="text-[10px] text-gray-400 mt-8">Diperbarui pada: {{ now()->format('H:i:s') }}. Silakan muat ulang halaman untuk pembaruan terbaru.</p>
    </div>
</body>
</html>
