@extends('kiosk.master')

@section('content')
<h2 class="text-3xl font-bold text-center mb-2 text-gray-800">Pendaftaran Identitas</h2>
<p class="text-center text-gray-600 mb-6 bg-blue-50 border border-blue-200 p-3 rounded-lg max-w-xl mx-auto">Kami memerlukan profil multi-sisi wajah Anda (3x Frame) untuk menjamin akurasi identifikasi selanjutnya.</p>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
    <!-- Camera Section -->
    <div>
        <div class="relative w-full aspect-video bg-gray-900 rounded-xl overflow-hidden shadow-inner mb-4">
            <video id="video-reg" class="w-full h-full object-cover shadow-lg transform scale-x-[-1]" autoplay muted></video>
            <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                <div id="face-guide-reg" class="w-40 h-56 border-4 border-dashed border-white rounded-3xl opacity-60"></div>
            </div>
            <div id="overlay-reg" class="absolute inset-0 z-10 flex items-center justify-center text-white bg-black bg-opacity-80">
                Memuat Kamera...
            </div>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-center">
            <h3 class="font-bold mb-2">Progres Tangkap Wajah (1 of 3)</h3>
            <div class="w-full bg-gray-300 rounded-full h-3 mb-4">
                <div id="progress-bar" class="bg-blue-600 h-3 rounded-full transition-all" style="width: 0%"></div>
            </div>
            <button type="button" id="capture-btn" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg shadow disabled:opacity-50" disabled>
                📸 Tangkap Wajah 1 (Hadap Depan)
            </button>
            <p class="text-xs text-gray-500 mt-2" id="capture-instruction">Tatap kamera dengan jelas.</p>
        </div>
    </div>

    <!-- Form Section -->
    <div>
        <form action="{{ route('kiosk.register.store') }}" method="POST" id="register-form" class="space-y-4 relative">
            @csrf
            <input type="hidden" name="face_data" id="face_data">

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border focus:border-blue-500 outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Jenis Kelamin</label>
                    <select name="gender" required class="w-full px-4 py-3 rounded-xl border focus:border-blue-500 outline-none">
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Instansi (Opsional)</label>
                    <input type="text" name="institution" class="w-full px-4 py-3 rounded-xl border focus:border-blue-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Nomor HP</label>
                    <input type="text" name="phone" class="w-full px-4 py-3 rounded-xl border focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-3 rounded-xl border focus:border-blue-500 outline-none">
                </div>
            </div>

            <button type="submit" id="submit-btn" class="w-full pt-2 mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl disabled:opacity-50" disabled>
                📋 Simpan Profil & Lanjut
            </button>
            <p class="text-xs text-red-500 mt-2 text-center" id="submit-warning">Selesaikan 3x tangkap wajah untuk menyimpan profil.</p>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const videoReg = document.getElementById('video-reg');
    const overlayReg = document.getElementById('overlay-reg');
    const captureBtn = document.getElementById('capture-btn');
    const submitBtn = document.getElementById('submit-btn');
    const progressBar = document.getElementById('progress-bar');
    const instruct = document.getElementById('capture-instruction');
    const faceDataInput = document.getElementById('face_data');
    const warning = document.getElementById('submit-warning');
    const MODEL_URL = '/models';

    let capturedDescriptors = [];
    let captureCount = 0;

    // Ambil descriptor frame 1 dari halaman scan
    const scanned = sessionStorage.getItem('scanned_descriptor');
    if (scanned) {
        capturedDescriptors.push(JSON.parse(scanned));
        captureCount = 1;
        
        // Update UI untuk frame 1
        progressBar.style.width = "33.33%";
        captureBtn.innerText = "📸 Tangkap Wajah 2 (Senyum Sedikit)";
        instruct.innerText = "Berikan ekspresi senyum sedikit tahan kepala statis.";
    }

    Promise.all([
        faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL),
        faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
        faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
    ]).then(startVideo).catch(err => {
        overlayReg.innerText = "Gagal memuat model Face API.";
    });

    async function startVideo() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
            videoReg.srcObject = stream;
            overlayReg.style.display = 'none';
            captureBtn.disabled = false;
        } catch (err) {
            overlayReg.innerText = "Kamera tidak diizinkan.";
        }
    }

    captureBtn.addEventListener('click', async () => {
        captureBtn.disabled = true;
        const originalText = captureBtn.innerText;
        captureBtn.innerText = "Menganalisa...";

        const detection = await faceapi.detectSingleFace(videoReg).withFaceLandmarks().withFaceDescriptor();
        
        if (!detection) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak Terdeteksi',
                text: 'Posisikan wajah Anda tepat pada kotak bantuan.',
                confirmButtonColor: '#2563eb'
            });
            captureBtn.innerText = originalText;
            captureBtn.disabled = false;
            return;
        }

        const videoWidth = videoReg.videoWidth || 640;
        if (detection.detection.box.width < (videoWidth * 0.15)) {
            Swal.fire({
                icon: 'warning',
                title: 'Terlalu Jauh',
                text: 'Silakan dekatkan lagi wajah sedikit ke arah kamera.',
                confirmButtonColor: '#2563eb'
            });
            captureBtn.innerText = originalText;
            captureBtn.disabled = false;
            return;
        }

        // Save descriptor
        capturedDescriptors.push(Array.from(detection.descriptor));
        captureCount++;
        
        progressBar.style.width = (captureCount / 3) * 100 + "%";

        if (captureCount === 1) {
            captureBtn.innerText = "📸 Tangkap Wajah 2 (Senyum Sedikit)";
            instruct.innerText = "Berikan ekspresi senyum sedikit tahan kepala statis.";
            captureBtn.disabled = false;
        } else if (captureCount === 2) {
            captureBtn.innerText = "📸 Tangkap Wajah 3 (Posisi Santai)";
            instruct.innerText = "Kembalikan wajah rileks. Ini adalah tangkapan terakhir.";
            captureBtn.disabled = false;
        } else if (captureCount === 3) {
            captureBtn.innerText = "✅ Wajah Terekam!";
            captureBtn.classList.replace('bg-green-600', 'bg-gray-500');
            instruct.innerText = "Data vektor wajah berkualitas tinggi siap disimpan.";
            
            faceDataInput.value = JSON.stringify(capturedDescriptors);
            submitBtn.disabled = false;
            warning.style.display = 'none';
        }
    });

    document.getElementById('register-form').addEventListener('submit', (e) => {
        if(capturedDescriptors.length < 3) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Belum Selesai',
                text: 'Mohon selesaikan tangkapan wajah 3x terlebih dahulu.',
                confirmButtonColor: '#2563eb'
            });
        }
    });
</script>
@endpush
@endsection
