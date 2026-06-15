@extends('kiosk.master')

@section('content')
<div class="max-w-xl mx-auto text-center py-6">
    <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 shadow-inner animate-bounce">
        ✅
    </div>

    <h2 class="text-3xl font-bold text-gray-800 mb-2">Janji Temu Berhasil!</h2>
    <p class="text-gray-500 mb-6">Detail konfirmasi telah dikirim ke email **{{ $appointment->email }}**.</p>

    <!-- Booking Summary Card -->
    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 text-left mb-8 space-y-3 shadow-inner">
        <div>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Layanan</span>
            <p class="font-bold text-gray-800 text-lg">{{ $appointment->serviceType->name }}</p>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal Kunjungan</span>
                <p class="font-bold text-gray-800">{{ $appointment->date->format('d F Y') }}</p>
            </div>
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jam Kunjungan</span>
                <p class="font-bold text-gray-800">{{ substr($appointment->time, 0, 5) }} WIB</p>
            </div>
        </div>

    </div>

    <!-- QR Code Tracking -->
    <div class="mb-8 p-4 bg-white border border-gray-200 rounded-2xl inline-block shadow-md">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($trackingUrl) }}" alt="QR Tracking" class="w-36 h-36 mx-auto" />
        <p class="text-xs text-gray-400 mt-2 font-bold">Pindai untuk Melacak/Mengubah Jadwal</p>
    </div>

    <div class="space-y-4">
        <a href="{{ $trackingUrl }}" 
           class="block w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition-colors text-center text-lg">
            📱 Pantau Janji Temu Anda
        </a>
        <a href="{{ route('kiosk.scan') }}" 
           class="block w-full py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-center border border-gray-200 transition-colors">
            🏠 Selesai & Kembali ke Awal
        </a>
    </div>
</div>
@endsection
