@extends('kiosk.master')

@section('content')
<div class="text-center" id="success-view">
    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-100 text-green-500 mb-6">
        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
    </div>
    
    <h2 class="text-4xl font-bold text-gray-800 mb-2">Pendaftaran Berhasil!</h2>
    <p class="text-gray-500 mb-8 font-medium text-lg">Silakan ambil struk antrean yang keluar pada printer.</p>

    <!-- Display for screen -->
    <div class="bg-gradient-to-br from-blue-50 to-white p-8 rounded-3xl max-w-sm mx-auto shadow-sm border border-blue-100 mb-8">
        <h1 class="text-7xl font-black text-blue-600 mb-4 tracking-tighter">{{ $queue->queue_number }}</h1>
        <p class="font-bold text-xl text-gray-700">{{ $queue->serviceType->name }}</p>
        <p class="text-gray-500 mt-2 text-lg">{{ $queue->visitor->name }}</p>
    </div>

    <!-- Hidden Printable Area (thermal) -->
    <div id="printArea" class="bg-white text-black text-center font-sans">
        <h3 class="font-bold text-xl mb-1 border-b border-black pb-2 text-center uppercase">BPS BINTAN</h3>
        <p class="text-sm mt-3 uppercase tracking-widest text-center">Nomor Antrean</p>
        <h1 class="text-[50px] font-black my-1 text-center leading-none">{{ $queue->queue_number }}</h1>
        <p class="text-sm font-bold border-t border-black pt-2 mt-2 text-center">{{ $queue->serviceType->name }}</p>
        <p class="text-xs mt-1 text-center uppercase">{{ $queue->visitor->name }}</p>
        
        <div class="my-4 flex justify-center w-full">
            {!! QrCode::size(120)->margin(0)->generate(route('queue.track', $queue->token)) !!}
        </div>
        
        <p class="text-[10px] text-center">Waktu: {{ $queue->created_at->format('d/m/Y H:i') }}</p>
        <p class="text-[10px] mt-2 italic border-t border-dashed border-black pt-2 text-center font-bold">Harap simpan tiket ini!</p>
    </div>

    <p class="text-gray-400 text-sm mt-8" id="countdown">Otomatis kembali ke beranda dalam 5 detik...</p>
</div>

@push('scripts')
<script>
    const synth = window.speechSynthesis;
    // Replace hyphens to make TTS sound better instead of "A minus zero zero one" -> "A kosong kosong satu"
    const readableQueue = "{{ $queue->queue_number }}".replace('-', ' ').replace('0', 'kosong ').replace('0', 'kosong ');
    const msg = new SpeechSynthesisUtterance("Nomor antrean " + readableQueue + ", silakan menunggu.");
    msg.lang = 'id-ID';
    msg.rate = 0.9;
    
    setTimeout(() => {
        synth.speak(msg);
        
        window.print();
        
        let secs = 5;
        setInterval(() => {
            secs--;
            document.getElementById('countdown').innerText = `Otomatis kembali ke beranda dalam ${secs} detik...`;
            if(secs <= 0) window.location.href = "{{ route('kiosk.scan') }}";
        }, 1000);
    }, 800);
</script>
@endpush
@endsection
