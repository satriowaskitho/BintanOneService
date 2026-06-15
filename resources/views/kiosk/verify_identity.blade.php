@extends('kiosk.master')

@section('content')
<div class="max-w-xl mx-auto py-6">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
            🔒
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Verifikasi Identitas Anda</h2>
        <p class="text-sm text-gray-500 mt-2">Untuk melindungi privasi data Anda, masukkan nomor HP atau email yang sudah terdaftar untuk melanjutkan.</p>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl font-bold" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('kiosk.options.verify.submit', $visitor->id) }}" method="POST" class="space-y-6 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        @csrf
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Nomor HP atau Email Terdaftar</label>
            <input type="text" name="verification" placeholder="Contoh: 08123456789 atau user@email.com" required 
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-emerald-500 outline-none transition-colors">
        </div>

        <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition-colors">
            🔓 Verifikasi & Lanjutkan
        </button>
    </form>

    <div class="mt-8 text-center">
        <a href="{{ route('kiosk.options', $visitor->id) }}" class="text-sm text-gray-400 hover:text-gray-600 font-semibold">&larr; Kembali ke Pilihan</a>
    </div>
</div>
@endsection
