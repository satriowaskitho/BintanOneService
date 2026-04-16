<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One Service</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 overflow-x-hidden selection:bg-blue-600 selection:text-white">

    <!-- Header / Navbar -->
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('Logo Bintan One Service.png') }}" alt="Logo" class="w-12 h-12 object-contain">

                <div>
                    <h1 class="text-xl font-bold leading-none text-gray-900">ONE Service</h1>
                    <p class="text-xs text-gray-500 font-semibold tracking-wide">BPS Kabupaten Bintan</p>
                </div>

            </div>
            <a href="{{ route('login') }}"
                class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                Panel Operator &rarr;
            </a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-b from-blue-50 to-white pt-24 pb-32 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <div
                class="inline-block px-4 py-1.5 bg-blue-100 text-blue-800 rounded-full text-sm font-bold tracking-wide uppercase mb-6 shadow-sm border border-blue-200">
                Pembaruan Layanan Publik
            </div>

            <h2 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-6 leading-tight tracking-tight">
                Pelayanan Cepat,<br class="hidden md:block" />
                <span class="text-blue-600">Tanpa Antrian Manual.</span>
            </h2>

            <p class="text-xl md:text-2xl text-gray-600 mb-10 leading-relaxed font-light max-w-3xl mx-auto">
                B-ONE (Bintan One Service) hadir dengan inovasi <span class="font-semibold text-gray-800">Face
                    Recognition</span> untuk mewujudkan layanan BPS yang modern, privat, dan efisien.
            </p>

            <a href="#scan-section"
                class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-full shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                Akses Kiosk Layanan
                <svg class="w-5 h-5 ml-3 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h3 class="text-3xl font-bold text-gray-900">Mengapa Menggunakan B-ONE?</h3>
                <p class="text-gray-500 mt-3 text-lg">Inovasi pelayanan yang menjadikan waktu Anda lebih berharga.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div
                    class="p-8 rounded-2xl bg-gray-50 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div
                        class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-3xl mb-6">
                        🤖</div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Teknologi Biometrik</h4>
                    <p class="text-gray-600 leading-relaxed">Sistem cerdas mengenali wajah Anda dalam hitungan detik.
                        Cukup daftar sekali pada kunjungan pertama tanpa form berulang.</p>
                </div>
                <div
                    class="p-8 rounded-2xl bg-gray-50 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div
                        class="w-14 h-14 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-3xl mb-6">
                        ⚡</div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Cepat & Transparan</h4>
                    <p class="text-gray-600 leading-relaxed">Pengambilan tiket yang cepat dengan pemantauan posisi
                        antrian secara real-time langsung melalui smartphone Anda.</p>
                </div>
                <div
                    class="p-8 rounded-2xl bg-gray-50 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div
                        class="w-14 h-14 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-3xl mb-6">
                        🔗</div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Integrasi Penuh</h4>
                    <p class="text-gray-600 leading-relaxed">terhubung kuat dengan panel operator BPS
                        Bintan untuk pemanggilan antrian.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Scan Kiosk Section -->
    <section id="scan-section" class="py-24 bg-gradient-to-t from-gray-100 to-white">
        <div class="max-w-4xl mx-auto px-6">
            <div class="bg-white p-8 md:p-12 rounded-[2rem] shadow-2xl border border-gray-100 text-center">

                <h3 class="text-3xl font-bold text-gray-900 mb-2">Identitas</h3>
                <p class="text-gray-500 mb-8">Arahkan wajah Anda ke kamera untuk melanjutkan.</p>

                <div
                    class="relative w-full aspect-video bg-gray-900 rounded-2xl overflow-hidden shadow-inner mx-auto max-w-2xl">
                    <video id="video" class="w-full h-full object-cover transform scale-x-[-1]" autoplay
                        muted></video>

                    <!-- FACE GUIDE -->
                    <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                        <div id="face-guide"
                            class="w-48 h-64 border-4 border-dashed border-white/70 rounded-[2rem] transition-colors duration-300">
                        </div>
                    </div>

                    <div id="overlay"
                        class="absolute inset-0 z-10 flex flex-col items-center justify-center text-white bg-black/80 backdrop-blur-sm">
                        <svg class="animate-spin h-10 w-10 text-blue-500 mb-4" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span class="text-lg font-semibold tracking-wide">Menginisialisasi Sistem Cerdas...</span>
                    </div>
                </div>

                <div
                    class="mt-10 flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <button id="scan-btn"
                        class="w-full sm:w-auto px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white text-lg font-bold rounded-xl shadow-lg hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        🔍 Konfirmasi Scan Wajah
                    </button>
                    <a href="{{ route('kiosk.register') }}"
                        class="w-full sm:w-auto px-10 py-4 bg-white text-gray-700 border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50 text-lg font-bold rounded-xl transition-all">
                        Login Manual
                    </a>
                </div>

                <form id="redirect-form" method="GET" style="display:none;"></form>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-10 text-center text-sm border-t border-gray-800">
        <p>&copy; 2026 Badan Pusat Statistik Kabupaten Bintan. Teknologi untuk Masyarakat.</p>
    </footer>

    <!-- Logic Script -->
    <script>
        const video = document.getElementById('video');
        const overlay = document.getElementById('overlay');
        const scanBtn = document.getElementById('scan-btn');
        const faceGuide = document.getElementById('face-guide');

        let labeledFaceDescriptors = [];
        const MODEL_URL = '/models';

        Promise.all([
            faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL),
            faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
            faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
        ]).then(startVideo).catch(err => {
            console.error(err);
            overlay.innerHTML = "<span class='text-red-400 font-bold'>Gagal memuat sistem pendeteksi.</span>";
        });

        async function startVideo() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {}
                });
                video.srcObject = stream;

                const res = await fetch('{{ route('kiosk.api.visitors') }}');
                const visitors = await res.json();

                labeledFaceDescriptors = visitors.map(v => {
                    let data = Object.values(v.face_data);
                    let descriptors = [];
                    if (Array.isArray(data[0]) || typeof data[0] === 'object') {
                        // multiple descriptors
                        descriptors = Object.values(v.face_data).map(arr => new Float32Array(Object.values(
                            arr)));
                    } else {
                        // single descriptor fallback
                        descriptors = [new Float32Array(data)];
                    }
                    return new faceapi.LabeledFaceDescriptors(v.id.toString(), descriptors);
                });

                overlay.style.display = 'none';
                scanBtn.disabled = false;
            } catch (err) {
                console.error(err);
                overlay.innerHTML =
                    "<span class='text-red-400 font-bold'>Akses kamera diblokir atau webcam tidak ditemukan.</span>";
            }
        }

        scanBtn.addEventListener('click', async () => {
            scanBtn.disabled = true;
            const originalText = scanBtn.innerHTML;
            scanBtn.innerHTML = "⏳ Menganalisa Data...";
            faceGuide.classList.replace('border-white/70', 'border-blue-400');

            const detection = await faceapi.detectSingleFace(video).withFaceLandmarks().withFaceDescriptor();

            if (!detection) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Terdeteksi',
                    text: 'Pastikan pencahayaan cukup dan wajah terlihat utuh di dalam layar.',
                    confirmButtonColor: '#2563eb'
                });
                resetBtn();
                return;
            }

            const boxWidth = detection.detection.box.width;
            if (boxWidth < 120) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Terlalu Jauh',
                    text: 'Mohon dekatkan wajah Anda ke dalam kotak panduan.',
                    confirmButtonColor: '#2563eb'
                });
                resetBtn();
                return;
            }

            faceGuide.classList.replace('border-blue-400', 'border-green-500');

            if (labeledFaceDescriptors.length > 0) {
                // Sangat ketat, threshold = 0.42
                const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.42);
                const bestMatch = faceMatcher.findBestMatch(detection.descriptor);

                if (bestMatch.label !== 'unknown') {
                    const visitorId = bestMatch.label;
                    document.getElementById('redirect-form').action = `/kiosk/ticket/${visitorId}`;
                    document.getElementById('redirect-form').submit();
                    return;
                }
            }

            Swal.fire({
                icon: 'info',
                title: 'Data Baru',
                text: 'Wajah Anda belum terdaftar. Mengalihkan ke form pendaftaran identitas...',
                showConfirmButton: false,
                timer: 2500
            }).then(() => {
                window.location.href = '{{ route('kiosk.register') }}';
            });

            function resetBtn() {
                scanBtn.innerHTML = originalText;
                scanBtn.disabled = false;
                faceGuide.classList.remove('border-blue-400', 'border-green-500');
                faceGuide.classList.add('border-white/70');
            }
        });
    </script>

</body>

</html>
