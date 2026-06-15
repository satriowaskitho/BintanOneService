@extends('kiosk.master')

@section('content')
<div class="max-w-xl mx-auto py-6">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Perbarui Profil Anda</h2>
        <p class="text-sm text-gray-500 mt-2">Silakan ubah data profil Anda di bawah ini jika terdapat ketidaksesuaian.</p>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl font-bold" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('kiosk.options.update-profile', $visitor->id) }}" method="POST" class="space-y-4 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        @csrf
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $visitor->name) }}" required 
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 outline-none">
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $visitor->email) }}" required 
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 outline-none">
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-1">Nomor HP</label>
            <input type="text" name="phone" value="{{ old('phone', $visitor->phone) }}" required 
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 outline-none">
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-1">Instansi (Opsional)</label>
            <input type="text" name="institution" value="{{ old('institution', $visitor->institution) }}" 
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 outline-none">
        </div>

        <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-colors mt-6">
            💾 Simpan & Kembali ke Pilihan
        </button>
    </form>

    <div class="mt-8 text-center">
        <a href="{{ route('kiosk.options', $visitor->id) }}" class="text-sm text-gray-400 hover:text-gray-600 font-semibold">&larr; Batalkan Perubahan</a>
    </div>
</div>
@endsection
