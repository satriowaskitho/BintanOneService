<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Operator B-ONE') }}
        </h2>
    </x-slot>

    <div class="py-12 relative max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <style>
            /* Fix untuk panah kalender flatpickr yang dirusak oleh reset SVG Tailwind */
            .flatpickr-calendar svg {
                display: inline-block !important;
                vertical-align: baseline !important;
            }
        </style>
        @if(session('status'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('status') }}</span>
        </div>
        @endif

        <!-- Global Loader Overlay -->
        <div id="page-loader" class="fixed inset-0 z-[100] flex items-center justify-center bg-white/80 backdrop-blur-sm transition-opacity duration-300 hidden opacity-0">
            <div class="flex flex-col items-center bg-white p-8 rounded-2xl shadow-xl border border-blue-50">
                <svg class="animate-spin h-12 w-12 text-blue-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <p class="text-gray-800 font-bold text-lg tracking-wide">Memuat Data...</p>
                <p class="text-gray-400 text-sm mt-1">Harap tunggu sebentar</p>
            </div>
        </div>

        <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-b">
            <h3 class="text-lg font-bold mb-4">Grafik Kunjungan (14 Hari Terakhir)</h3>
            <canvas id="visitChart" height="80"></canvas>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 border-b">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 sm:mb-0">Daftar Antrean</h3>
                    <form action="{{ route('admin.dashboard') }}" method="GET" id="filter-form" class="relative w-full sm:w-auto">
                        <div class="flex flex-col sm:flex-row items-center gap-3">
                            <!-- Tombol Hari Ini -->
                            <a href="{{ route('admin.dashboard', ['date' => \Carbon\Carbon::today()->format('Y-m-d')]) }}"
                               class="bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 rounded-xl px-4 py-2 shadow-sm transition font-bold text-sm w-full sm:w-auto text-center">
                                Hari Ini
                            </a>

                            <!-- Date Picker Group -->
                            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-200 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 w-full sm:w-auto">
                                <div class="pl-4 pr-3 text-blue-500 bg-white border-r border-gray-200 h-full flex items-center py-2.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <!-- DATE PICKER -->
                                <input
                                    type="text"
                                    id="datePicker"
                                    value="{{ $filterDate }}"
                                    class="bg-white border-none px-4 py-2 font-semibold text-gray-700 focus:ring-0 outline-none w-full sm:w-44 cursor-pointer"
                                    placeholder="Pilih Tanggal..."
                                >
                            </div>
                        </div>
                    </form>
                </div>
                <div class="overflow-hidden rounded-2xl border border-gray-100 shadow-sm">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-50 to-indigo-50 text-gray-700 uppercase text-xs tracking-wider">
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
                            <tr class="border-b border-gray-100 hover:bg-blue-50/40 transition-colors">
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
                                    <div id="qr-{{ $q->id }}" class="hidden">
                                        <div class="flex justify-center p-4 bg-white rounded-lg">
                                            {!! QrCode::size(200)->margin(1)->generate(route('queue.track', $q->token)) !!}
                                        </div>
                                        <p class="text-gray-500 mt-2 text-sm text-center">Scan untuk membuka halaman tracking tiket ini.</p>
                                    </div>
                                    <button type="button" onclick="showQR('{{ $q->queue_number }}', 'qr-{{ $q->id }}')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-3 rounded text-xs shadow border-b-2 border-gray-800 active:border-b-0 active:translate-y-1" title="Lihat QR Code">
                                        📱 QR
                                    </button>

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
                                <td colspan="6" class="py-8 text-center text-gray-500 font-bold">Belum ada antrean pada tanggal ini.</td>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const ctx = document.getElementById('visitChart').getContext('2d');
        const visitChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($dates ?? []) !!},
                datasets: [{
                    label: 'Total Kunjungan ',
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

        function showQR(queueNumber, qrDivId) {
            Swal.fire({
                title: 'QR Code ' + queueNumber,
                html: document.getElementById(qrDivId).innerHTML,
                showConfirmButton: true,
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'Tutup'
            });
        }

        function showLoaderAndSubmit() {
            const loader = document.getElementById('page-loader');
            loader.classList.remove('hidden');
            setTimeout(() => {
                loader.classList.remove('opacity-0');
            }, 10);
            document.getElementById('filter-form').submit();
        }

        // Auto refresh dashboard every 15 seconds to see new queues
        // Only refresh automatically if we are on today's date, to prevent disrupting history viewing
        const urlParams = new URLSearchParams(window.location.search);
        const filterDate = urlParams.get('date');
        const todayStr = new Date().toLocaleDateString('en-CA'); // YYYY-MM-DD
        
        if (!filterDate || filterDate === todayStr) {
            setInterval(() => {
                // Jangan refresh jika SweetAlert sedang aktif
                if (document.querySelector('.swal2-shown') || document.querySelector('.swal2-container')) {
                    return;
                }

                fetch(window.location.href)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTbody = doc.querySelector('tbody');
                        const oldTbody = document.querySelector('tbody');
                        
                        if (newTbody && oldTbody) {
                            oldTbody.innerHTML = newTbody.innerHTML;
                        }
                    })
                    .catch(err => console.error('Failed to poll new queues:', err));
            }, 15000);
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const initFlatpickr = () => {
                if (window.flatpickr) {
                    window.flatpickr("#datePicker", {
                        dateFormat: "Y-m-d",
                        defaultDate: "{{ $filterDate }}",
                        onChange: function(selectedDates, dateStr) {
                            showLoader();
                            setTimeout(() => {
                                window.location.href = `{{ route('admin.dashboard') }}?date=${dateStr}`;
                            }, 100);
                        }
                    });
                } else {
                    setTimeout(initFlatpickr, 50);
                }
            };
            initFlatpickr();
        });
        
        function showLoader() {
            const loader = document.getElementById('page-loader');

            loader.classList.remove('hidden');

            setTimeout(() => {
                loader.classList.remove('opacity-0');
            }, 10);
        }
    </script>
    @endpush
</x-app-layout>
