@extends('kiosk.master')

@section('content')
<div class="max-w-xl mx-auto text-center py-6">
    <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 shadow-inner">
        🔍
    </div>
    
    <h2 class="text-3xl font-bold text-gray-800 mb-3">Data Ditemukan</h2>
    <p class="text-gray-600 mb-8 leading-relaxed">
        Wajah Anda terdeteksi sudah terdaftar di sistem kami atas nama:<br />
        <strong class="text-xl text-blue-600 font-extrabold block mt-2">{{ $visitor->name }}</strong>
    </p>

    <div class="space-y-4">
        <!-- GUNAKAN DATA LAMA -->
        <a href="{{ route('kiosk.options', $visitor->id) }}" 
           class="block w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all text-center text-lg">
            👍 Gunakan Data Lama
        </a>

        <!-- VERIFIKASI & PERBARUI -->
        <a href="{{ route('kiosk.options.verify', $visitor->id) }}" 
           class="block w-full py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl border border-gray-200 text-center transition-all">
            ✏️ Verifikasi & Perbarui Profil
        </a>
    </div>

    <div class="mt-8">
        <a href="{{ route('kiosk.scan') }}" class="text-sm text-gray-400 hover:text-gray-600 font-semibold">&larr; Batal & Kembali</a>
    </div>
</div>
@endsection
