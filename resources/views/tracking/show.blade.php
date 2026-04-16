<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Antrean B-ONE</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-blue-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white shadow-xl rounded-3xl overflow-hidden p-8 text-center mx-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Status Antrean</h2>
        
        <div class="my-6">
            <h1 class="text-6xl font-black text-blue-600 mb-2">{{ $queue->queue_number }}</h1>
            <p class="font-bold text-lg text-gray-700">{{ $queue->serviceType->name }}</p>
        </div>

        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 mb-6">
            <p class="text-gray-500 mb-1">Status Saat Ini:</p>
            @if($queue->status == 'waiting')
                <span class="inline-block px-4 py-2 bg-yellow-100 text-yellow-800 font-bold rounded-full text-lg">⏳ Menunggu</span>
            @elseif($queue->status == 'called')
                <span class="inline-block px-4 py-2 bg-blue-100 text-blue-800 font-bold rounded-full text-lg animate-pulse">📢 Sedang Dipanggil</span>
            @else
                <span class="inline-block px-4 py-2 bg-green-100 text-green-800 font-bold rounded-full text-lg">✅ Selesai</span>
            @endif
        </div>

        @if($queue->status == 'waiting')
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100">
                <p class="text-sm font-semibold text-blue-800 mb-1">Antrean di Depan</p>
                <p class="text-3xl font-black text-blue-600">{{ $position }} <span class="text-base font-normal">Orang</span></p>
            </div>
            <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100">
                <p class="text-sm font-semibold text-blue-800 mb-1">Estimasi Waktu</p>
                <p class="text-3xl font-black text-blue-600" id="countdown-display">
                    --:--
                </p>
            </div>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let remainingSeconds = {{ $remainingSeconds }};
                const display = document.getElementById('countdown-display');
                
                function updateDisplay() {
                    if (remainingSeconds <= 0) {
                        display.innerHTML = '<span class="text-lg">Mohon tunggu sebentar</span>';
                        return true;
                    } else {
                        const minutes = Math.floor(remainingSeconds / 60);
                        let seconds = remainingSeconds % 60;
                        if (seconds < 10) seconds = '0' + seconds;
                        display.innerHTML = minutes + ':' + seconds;
                        return false;
                    }
                }

                if (!updateDisplay()) {
                    const countdown = setInterval(function() {
                        remainingSeconds--;
                        if (updateDisplay()) {
                            clearInterval(countdown);
                        }
                    }, 1000);
                }
            });
        </script>

        @elseif($queue->status == 'done')
        <div class="mt-6 border-t border-gray-100 pt-6">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Penilaian Layanan</h3>
            
            @if(session('status'))
                <div class="bg-green-100 text-green-800 p-4 rounded-xl mb-4 font-bold">
                    {{ session('status') }}
                </div>
            @endif

            @if($queue->rating)
                <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100">
                    <p class="text-blue-800 font-bold text-lg mb-1">Terima Kasih!</p>
                    <p class="text-blue-600">Penilaian Anda ({{ 
                        $queue->rating == 3 ? '🤩 Puas' : ($queue->rating == 2 ? '😐 Netral' : '😠 Tidak Puas') 
                    }}) telah kami simpan.</p>
                </div>
            @else
                <p class="text-sm text-gray-500 mb-4">Seberapa puas Anda dengan layanan hari ini?</p>
                <form action="{{ route('queue.rate', $queue->token) }}" method="POST" id="rating-form">
                    @csrf
                    <div class="flex justify-center space-x-4 mb-6">
                        <button type="submit" name="rating" value="1" class="flex flex-col items-center p-3 rounded-xl border border-red-200 bg-red-50 hover:bg-red-100 transition-colors w-24">
                            <span class="text-3xl mb-1">😠</span>
                            <span class="text-xs font-bold text-red-600">Tidak Puas</span>
                        </button>
                        <button type="submit" name="rating" value="2" class="flex flex-col items-center p-3 rounded-xl border border-yellow-200 bg-yellow-50 hover:bg-yellow-100 transition-colors w-24">
                            <span class="text-3xl mb-1">😐</span>
                            <span class="text-xs font-bold text-yellow-600">Netral</span>
                        </button>
                        <button type="submit" name="rating" value="3" class="flex flex-col items-center p-3 rounded-xl border border-green-200 bg-green-50 hover:bg-green-100 transition-colors w-24">
                            <span class="text-3xl mb-1">🤩</span>
                            <span class="text-xs font-bold text-green-600">Puas</span>
                        </button>
                    </div>
                </form>
            @endif

            <div class="mt-4">
                <a href="https://skd.bps.go.id/skd/s/2102" target="_blank" class="block w-full py-4 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold shadow-sm transition-colors text-sm border border-gray-200">
                    📝 Isi Survei Kepuasan (SKD) BPS Utama
                </a>
            </div>
        </div>
        @endif


        <p class="text-xs text-gray-400 mt-8">Diperbarui pada: {{ now()->format('H:i:s') }}. Silakan muat ulang halaman untuk pembaruan terbaru.</p>
    </div>
</body>
</html>
