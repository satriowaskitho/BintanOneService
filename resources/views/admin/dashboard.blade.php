<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Operator B-ONE') }}
        </h2>
    </x-slot>

    <div class="py-12 relative max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if(session('status'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('status') }}</span>
        </div>
        @endif

        <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-b">
            <h3 class="text-lg font-bold mb-4">Grafik Kunjungan (14 Hari Terakhir)</h3>
            <canvas id="visitChart" height="80"></canvas>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 border-b">
                <h3 class="text-lg font-bold mb-4">Daftar Antrean Hari Ini</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Antrean</th>
                                <th class="py-3 px-6 text-left">Pengunjung</th>
                                <th class="py-3 px-6 text-left">Layanan</th>
                                <th class="py-3 px-6 text-center">Status</th>
                                <th class="py-3 px-6 text-center">Rating</th>
                                <th class="py-3 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @forelse($queues as $q)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left font-bold text-lg">
                                    {{ $q->queue_number }}
                                </td>
                                <td class="py-3 px-6 text-left font-semibold">
                                    {{ $q->visitor->name }}
                                </td>
                                <td class="py-3 px-6 text-left">
                                    {{ $q->serviceType->name }}
                                </td>
                                <td class="py-3 px-6 text-center">
                                    @if($q->status == 'waiting')
                                        <span class="bg-yellow-200 text-yellow-800 py-1 px-3 rounded-full text-xs font-bold">Menunggu</span>
                                    @elseif($q->status == 'called')
                                        <span class="bg-blue-200 text-blue-800 py-1 px-3 rounded-full text-xs font-bold">Dipanggil</span>
                                    @else
                                        <span class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs font-bold">Selesai</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-center text-lg">
                                    @if($q->rating == 3) 🤩
                                    @elseif($q->rating == 2) 😐
                                    @elseif($q->rating == 1) 😠
                                    @else - @endif
                                </td>
                                <td class="py-3 px-6 text-center flex justify-center space-x-2">
                                    @if($q->status == 'waiting')
                                    <form action="{{ route('admin.queue.call', $q->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="speakQueue('{{ str_replace('-', ' ', str_replace('0', 'kosong ', $q->queue_number)) }}')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-xs shadow border-b-2 border-blue-800 active:border-b-0 active:translate-y-1">📢 Panggil</button>
                                    </form>
                                    @elseif($q->status == 'called')
                                    <form action="{{ route('admin.queue.done', $q->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-xs shadow border-b-2 border-green-800 active:border-b-0 active:translate-y-1">✅ Selesai</button>
                                    </form>
                                    @else
                                        <button disabled class="bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded text-xs cursor-not-allowed border-b-2 border-gray-400">✅ Selesai</button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500 font-bold">Belum ada antrean hari ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('visitChart').getContext('2d');
        const visitChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($dates ?? []) !!},
                datasets: [{
                    label: 'Total Kunjungan per Hari',
                    data: {!! json_encode($counts ?? []) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        function speakQueue(queueText) {
            const synth = window.speechSynthesis;
            const msg = new SpeechSynthesisUtterance("Nomor antrean " + queueText + ", silakan menuju ke loket pelayanan.");
            msg.lang = 'id-ID';
            msg.rate = 0.9;
            synth.speak(msg);
        }

        // Auto refresh dashboard every 15 seconds to see new queues
        setTimeout(() => {
            window.location.reload();
        }, 15000);
    </script>
    @endpush
</x-app-layout>
