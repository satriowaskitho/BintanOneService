<body class="bg-gradient-to-br from-slate-50 to-blue-50 text-gray-800 overflow-x-hidden">

<!-- HERO -->
<section class="min-h-screen flex flex-col justify-center items-center text-center px-6">

    <h1 class="text-5xl md:text-6xl font-extrabold leading-tight mb-6">
        Pelayanan Statistik <br>
        <span class="text-blue-600">Lebih Cepat & Cerdas</span>
    </h1>

    <p class="text-lg md:text-xl text-gray-600 max-w-xl mb-10">
        B-ONE menghadirkan layanan tanpa antre manual, tanpa formulir berulang,
        dan tanpa ribet. Cukup wajah Anda.
    </p>

    <button onclick="scrollToScan()" 
        class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white text-lg font-bold rounded-xl shadow-lg transition">
        🚀 Mulai Sekarang
    </button>

</section>

<!-- WHY -->
<section class="py-20 bg-white text-center px-6">
    <h2 class="text-3xl font-bold mb-6">Kenapa B-ONE Dibuat?</h2>

    <p class="max-w-3xl mx-auto text-gray-600 text-lg leading-relaxed">
        Pelayanan publik sering kali lambat karena antrean manual, pengisian data berulang,
        dan kurangnya sistem terintegrasi. 
        <br><br>
        B-ONE hadir sebagai solusi digital berbasis AI untuk mempercepat proses,
        meningkatkan akurasi, dan memberikan pengalaman layanan yang modern.
    </p>
</section>

<!-- FEATURES -->
<section class="py-20 px-6">
    <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">

        <div class="bg-white p-8 rounded-2xl shadow hover:shadow-lg transition">
            <h3 class="text-xl font-bold mb-2">🤖 Face Recognition</h3>
            <p class="text-gray-600">Identifikasi otomatis tanpa input berulang</p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow hover:shadow-lg transition">
            <h3 class="text-xl font-bold mb-2">⚡ Cepat & Efisien</h3>
            <p class="text-gray-600">Kurangi waktu tunggu dan antre manual</p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow hover:shadow-lg transition">
            <h3 class="text-xl font-bold mb-2">📊 Terintegrasi</h3>
            <p class="text-gray-600">Data langsung terhubung ke sistem pelayanan</p>
        </div>

    </div>
</section>

<!-- SCAN SECTION -->
<section id="scanSection" class="min-h-screen flex flex-col items-center justify-center px-6 bg-blue-50">

    <h2 class="text-3xl font-bold mb-6">Scan Wajah Anda</h2>

    <div class="relative w-full max-w-xl">
        <video id="video" class="w-full rounded-xl shadow"></video>
        <canvas id="face-canvas" class="absolute top-0 left-0 w-full h-full"></canvas>
    </div>

    <button id="scan-btn" 
        class="mt-6 px-6 py-3 bg-blue-600 text-white rounded-xl font-bold">
        🔍 Scan Wajah
    </button>

</section>

<script>
function scrollToScan() {
    document.getElementById('scanSection').scrollIntoView({ behavior: 'smooth' });
}
</script>

</body>