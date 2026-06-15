# PROPOSAL INOVASI
# Bintan One Service (B-ONE) – BPS Kabupaten Bintan

**Tanggal Implementasi Inovasi**: Senin, 1 Juni 2026  
**Kelompok**: Kelompok Umum  
**Kategori**: Kategori 8 – Transformasi Digital Pelayanan Publik  

---

## Ringkasan (5%)
Badan Pusat Statistik (BPS) Kabupaten Bintan menghadirkan inovasi **Bintan One Service (B-ONE)**, sebuah sistem manajemen antrean berbasis web terintegrasi pencocokan biometrik pengenalan wajah (*face recognition*) di sisi client. Inovasi ini menyederhanakan proses registrasi pengunjung, mempercepat verifikasi identitas, meminimalkan antrean fisik, serta mengeliminasi penyalahgunaan antrean melalui pembatasan tiket aktif harian dan mekanisme cooldown anti-spam. Melalui B-ONE, pengunjung dapat mendaftar secara mandiri, memilih jenis layanan statistik terpadu, dan melacak nomor antrean mereka secara real-time via perangkat pribadi menggunakan QR Code yang tertera pada tiket fisik. Inovasi ini secara nyata meningkatkan transparansi, efisiensi waktu tunggu, dan kepuasan masyarakat terhadap pelayanan statistik.

---

## Ide Inovatif (20%)

### Latar Belakang
Di era transformasi digital, instansi pemerintah dituntut untuk menyajikan pelayanan publik yang cepat, mudah, dan transparan. Pada unit Pelayanan Statistik Terpadu (PST) BPS Kabupaten Bintan, prosedur antrean konvensional seringkali menghadapi kendala inefisiensi, seperti pendaftaran berulang bagi pengunjung setia, antrean fisik yang menumpuk di ruang tunggu, serta potensi pengambilan tiket ganda secara berulang yang mengacaukan estimasi waktu tunggu.

Untuk mengatasi permasalahan tersebut, BPS Kabupaten Bintan merancang **Bintan One Service (B-ONE)**. Dengan memanfaatkan kemajuan teknologi kecerdasan buatan (*computer vision*) di browser lokal melalui *Face-API.js*, B-ONE mampu mengidentifikasi pengunjung dalam hitungan detik secara aman dan terenkripsi tanpa membebani kinerja server. Kehadiran B-ONE merupakan komitmen nyata BPS Bintan untuk menghadirkan pelayanan publik yang inklusif, modern, dan adaptif terhadap perkembangan teknologi.

### Tujuan dan Output Inovasi
- **Mempermudah akses pendaftaran** pengunjung PST secara mandiri berbasis biometrik.
- **Mengoptimalkan alokasi waktu tunggu** pengunjung melalui sistem estimasi waktu linier yang akurat dan pelacakan antrean real-time.
- **Mencegah manipulasi tiket** dengan menerapkan pembatasan antrean aktif tunggal per hari dan waktu tunggu cooldown anti-spam 1 menit.
- **Mengumpulkan umpan balik (rating) kepuasan** secara langsung setelah pelayanan selesai.

**Output yang dihasilkan**:
- Aplikasi Kiosk Web B-ONE yang aktif menggunakan kamera webcam untuk pemindaian wajah.
- Dasbor Admin Operator untuk pemanggilan, pemantauan, dan manajemen antrean secara real-time.
- Database terenkripsi berisi data pengunjung dan deskriptor biometrik wajah.
- Tiket cetak antrean 58mm yang dilengkapi QR Code pelacakan dinamis.

---

## Sisi Kebaruan dan Nilai Tambah
- **Teknologi Biometrik On-Client**: Pencocokan wajah dilakukan langsung di browser web client (*on-client processing*) menggunakan Face-API.js, sehingga pemrosesan sangat cepat dan menjaga kerahasiaan data tanpa mengirim gambar mentah ke server.
- **Validasi Antrean Cerdas**: Penerapan *Active Queue Constraint* dan *Anti-Spam Cooldown* secara otomatis di sisi backend untuk menjaga ketertiban urutan antrean.
- **Pelacakan Antrean Mandiri (QR Code)**: Pengunjung tidak perlu berdiam di ruang pelayanan; mereka dapat melacak giliran secara dinamis melalui handphone masing-masing.
- **Mekanisme Rating Terintegrasi**: Pengunjung dapat memberikan rating instan (skala Likert 3-point) langsung pada halaman sukses atau pelacakan begitu selesai dilayani.

---

## Signifikansi (25%)

### Implementasi dan Dampak

**Sebelum Bintan One Service (B-ONE)**:
- Pengunjung harus menulis data identitas secara manual di buku tamu kertas setiap kali datang.
- Petugas kesulitan memverifikasi apakah pengunjung tersebut pernah datang sebelumnya secara cepat.
- Penumpukan pengunjung di ruang tunggu karena tidak adanya informasi sisa antrean yang dapat dipantau dari luar ruangan.
- Estimasi waktu pelayanan tidak terukur, seringkali memicu keluhan ketidakpastian.

**Setelah Bintan One Service (B-ONE)**:
- Pengunjung setia cukup berdiri di depan kamera kiosk untuk diverifikasi secara instan dalam 1-2 detik tanpa perlu mengisi form ulang.
- Dasbor admin secara real-time menampilkan antrean tertunda, mempermudah petugas memanggil antrean berikutnya secara tertib.
- Pengunjung dapat menunggu di luar ruangan (seperti di kantin atau area parkir) sambil memantau sisa antrean via handphone pribadi.
- Tersedianya visualisasi tren kunjungan mingguan dan statistik kepuasan pengunjung di dasbor admin untuk bahan evaluasi berkala pimpinan.

---

## Kontribusi terhadap TPB (5%)
- **TPB 9 – Industri, Inovasi, dan Infrastruktur**: Mengintegrasikan inovasi teknologi biometrik wajah ke dalam infrastruktur pelayanan publik untuk menciptakan sistem yang andal.
- **TPB 16 – Perdamaian, Keadilan, dan Kelembagaan yang Tangguh**: Membangun lembaga publik yang efektif, akuntabel, dan transparan melalui digitalisasi pencatatan kunjungan dan penanganan keluhan secara real-time.

---

## Adaptabilitas (20%)
Aplikasi B-ONE dirancang dengan arsitektur web standar (Laravel + MySQL) sehingga sangat mudah diadaptasi dan direplikasi oleh unit kerja BPS lain maupun instansi pemerintah daerah:
- **Komponen Gagasan**: Mengubah sistem antrean fisik manual menjadi antrean digital mandiri berbasis biometrik wajah.
- **Komponen Teknis**: Menggunakan framework Laravel 11 yang terdokumentasi dengan baik, database MySQL standar, serta pustaka JavaScript open-source untuk pengenalan wajah (*no licensing cost*).
- **Komponen Manajerial**: Penerapan SOP operasional kiosk, penugasan operator dasbor pelayanan, serta pengaturan hak akses admin per PJ (Penanggung Jawab) layanan.

---

## Keberlanjutan (20%)

### Sumber Daya
- **SDM**: Petugas Pelayanan Statistik Terpadu (PST) bertindak sebagai operator dasbor pelayanan, didukung oleh tim IT BPS untuk pemeliharaan sistem.
- **Teknologi**: Menggunakan platform web responsif berbasis framework PHP Laravel yang stabil dan mudah dikembangkan untuk jangka panjang.
- **Infrastruktur**: PC Kiosk standar, webcam HD USB, dan printer thermal mini 58mm yang memiliki biaya operasional sangat rendah.

### Strategi Keberlanjutan
- **Institusional**: Penggunaan aplikasi B-ONE diintegrasikan ke dalam Standar Operasional Prosedur (SOP) Pelayanan Statistik Terpadu (PST) tahunan BPS Bintan.
- **Manajerial**: Pembagian hak akses admin khusus per Penanggung Jawab (PJ) bidang layanan untuk peringatan dini jika terjadi antrean berlebih.
- **Sosial**: Edukasi mandiri bagi pengunjung melalui petunjuk visual yang ditempel di sekitar area kiosk B-ONE.

---

## Kolaborasi Pemangku Kepentingan (5%)
- **Pengunjung Layanan**: Berpartisipasi aktif melakukan scan wajah mandiri dan memberikan rating penilaian setelah mendapatkan pelayanan.
- **Petugas Pelayanan / Operator**: Mengelola jalannya pemanggilan antrean dari dasbor admin untuk menjamin kelancaran alur pelayanan.
- **Pimpinan BPS Bintan / Stakeholder Sektoral**: Memanfaatkan data log kunjungan dan hasil rating harian sebagai instrumen evaluasi kinerja pelayanan publik secara objektif.
