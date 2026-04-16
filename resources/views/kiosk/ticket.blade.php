@extends('kiosk.master')

@section('content')
<div class="text-center">
    <div class="mb-8">
        <h2 class="text-3xl font-bold mb-2">Selamat Datang, <span class="text-blue-600">{{ $visitor->name }}</span></h2>
        <p class="text-gray-500 text-lg">Silakan pilih layanan yang Anda butuhkan hari ini.</p>
    </div>

    <form action="{{ route('kiosk.queue.generate') }}" method="POST" class="max-w-xl mx-auto">
        @csrf
        <input type="hidden" name="visitor_id" value="{{ $visitor->id }}">
        
        <div class="grid grid-cols-1 gap-4 mb-8">
            @foreach($services as $service)
            <label class="block cursor-pointer">
                <input type="radio" name="service_type_id" value="{{ $service->id }}" class="peer sr-only" required>
                <div class="p-6 rounded-2xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:ring-4 peer-checked:ring-blue-100 hover:bg-gray-50 transition-all text-left flex justify-between items-center group">
                    <div>
                        <div class="font-bold text-2xl text-gray-800">{{ $service->name }}</div>
                        <div class="text-gray-500 mt-1 uppercase tracking-widest text-sm">Layanan {{ $service->code }}</div>
                    </div>
                </div>
            </label>
            @endforeach
        </div>

        <div class="mb-8 bg-gray-50 p-6 rounded-2xl border border-gray-100">
            <label class="block text-left text-gray-700 font-semibold mb-3 text-lg">Keperluan Detail (Opsional)</label>
            <textarea name="purpose" rows="2" class="w-full px-5 py-4 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none resize-none" placeholder="Tuliskan keperluan Anda lebih rinci..."></textarea>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-5 text-2xl rounded-2xl shadow-xl border-b-8 border-blue-800 active:border-b-0 active:translate-y-2 transition-all">
            🎟️ AMBIL ANTREAN
        </button>
    </form>
</div>

@push('scripts')
<script>
    // clear memory from preceding page
    sessionStorage.removeItem('temp_face_descriptor');
</script>
@endpush
@endsection
