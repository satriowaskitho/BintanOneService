@extends('kiosk.master')

@section('content')
<div class="max-w-xl mx-auto text-center">
    <h2 class="text-3xl font-bold text-gray-800 mb-2">Halo, {{ $visitor->name }}!</h2>
    <p class="text-gray-500 mb-8">Anda sudah terdaftar. Silakan pilih layanan di bawah ini.</p>

    @if(session('status'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl font-bold" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- TODAY'S APPOINTMENT CHECK-IN CARD -->
    @if($todayAppointment)
        <div class="mb-8 p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-500/20 rounded-2xl shadow-md text-left">
            <div class="flex items-center space-x-3 mb-3 text-green-800">
                <span class="text-2xl">📅</span>
                <h3 class="font-extrabold text-lg">Janji Temu Anda Hari Ini</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4">
                Anda memiliki jadwal janji untuk <strong>{{ $todayAppointment->serviceType->name }}</strong> pukul <strong>{{ substr($todayAppointment->time, 0, 5) }} WIB</strong>.
            </p>
            <form action="{{ route('kiosk.appointment.checkin', $todayAppointment->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow transition-colors flex items-center justify-center space-x-2 text-lg">
                    <span>✅ Check-In Sekarang</span>
                </button>
            </form>
        </div>
    @endif

    <!-- CHANNELS -->
    <div class="grid grid-cols-1 gap-6">
        <!-- DATANG SEKARANG (WALK-IN QUEUE) -->
        <a href="{{ route('kiosk.ticket', $visitor->id) }}" class="group block p-6 bg-white hover:bg-blue-50 border border-gray-200 hover:border-blue-500/40 rounded-2xl shadow-sm hover:shadow-md transition-all text-left">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-blue-600 transition-colors flex items-center space-x-2">
                        <span>⚡ Datang Sekarang</span>
                    </h3>
                    <p class="text-sm text-gray-500 mt-2">
                        Ambil nomor antrean langsung untuk melakukan konsultasi atau pelayanan hari ini juga.
                    </p>
                </div>
                <span class="text-3xl text-gray-400 group-hover:text-blue-500 transition-colors">&rarr;</span>
            </div>
        </a>

        <!-- BUAT JANJI (APPOINTMENT) -->
        <a href="{{ route('kiosk.appointment.create', $visitor->id) }}" class="group block p-6 bg-white hover:bg-emerald-50 border border-gray-200 hover:border-emerald-500/40 rounded-2xl shadow-sm hover:shadow-md transition-all text-left">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-emerald-600 transition-colors flex items-center space-x-2">
                        <span>📅 Buat Janji Temu</span>
                    </h3>
                    <p class="text-sm text-gray-500 mt-2">
                        Jadwalkan kunjungan Anda pada hari lain di masa mendatang agar mendapatkan slot pelayanan terbaik.
                    </p>
                </div>
                <span class="text-3xl text-gray-400 group-hover:text-emerald-500 transition-colors">&rarr;</span>
            </div>
        </a>
    </div>

    <!-- BACK BUTTON -->
    <div class="mt-8">
        <a href="{{ route('kiosk.scan') }}" class="text-sm text-gray-400 hover:text-gray-600 font-semibold">&larr; Kembali ke Scanner</a>
    </div>
</div>
@endsection
