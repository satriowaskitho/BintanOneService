# LAPORAN INOVASI PEMANFAATAN TEKNOLOGI INFORMASI
# BINTAN ONE SERVICE (B-ONE) BPS KABUPATEN BINTAN

---

## 1. Latar Belakang/Alasan Pengembangan Aplikasi
Aplikasi **Bintan One Service (B-ONE)** merupakan langkah strategis Badan Pusat Statistik (BPS) Kabupaten Bintan dalam melakukan transformasi digital di bidang pelayanan publik, khususnya pada unit Pelayanan Statistik Terpadu (PST). Pelayanan statistik yang responsif dan transparan membutuhkan sistem pencatatan kunjungan serta antrean yang efisien. Metode antrean konvensional seringkali memicu kendala operasional seperti pengisian buku tamu berulang oleh pengunjung yang sama, keterlambatan pemanggilan karena antrean fisik yang tidak teratur, serta ketiadaan pelacakan posisi antrean mandiri bagi pengunjung.

Dengan mengintegrasikan teknologi pencocokan biometrik pengenalan wajah (*face recognition*) berbasis client (*on-browser*), B-ONE hadir untuk memodernisasi alur pelayanan. Pengunjung yang telah terdaftar tidak perlu lagi menginput data identitas secara manual cukup dengan pemindaian wajah singkat. Sistem ini juga secara aktif membatasi tiket ganda serta spamming tiket demi menjaga keandalan data antrean. Inovasi B-ONE memperkuat komitmen BPS Kabupaten Bintan terhadap transparansi, akuntabilitas, dan pelayanan prima dalam rangka reformasi birokrasi.

---

## 2. Pemanfaatan Aplikasi Tersebut
Manfaat utama yang diperoleh BPS Kabupaten Bintan dari implementasi aplikasi B-ONE adalah sebagai berikut:
- **Kemudahan Akses dan Verifikasi Instan**: Pengunjung terdaftar diverifikasi secara instan melalui kamera kiosk dalam hitungan detik, menghilangkan kebutuhan pengisian berkas manual secara berulang.
- **Peningkatan Ketertiban dan Kepuasan Layanan**: Pengunjung dapat memantau estimasi sisa antrean dari handphone masing-masing melalui scan QR Code tiket. Hal ini mengurangi kepadatan fisik di ruang tunggu PST.
- **Validasi Data Kunjungan yang Akurat**: Data kunjungan terintegrasi langsung dengan database untuk pelaporan berkala pimpinan secara otomatis dan tepercaya.
- **Penilaian Evaluasi Instan**: Integrasi survei kepuasan (3-point Likert scale) langsung pada halaman pelacakan tiket setelah status antrean diselesaikan oleh petugas.

### Alur Kerja Aplikasi B-ONE (Kiosk ke Operator)
1. **Pemindaian Wajah**: Pengunjung berdiri di depan kamera Kiosk B-ONE di ruang pelayanan.
2. **Pengecekan Database**:
   - Jika *Wajah Dikenali*, sistem langsung menampilkan halaman profil pengunjung dan mengizinkan pemilihan layanan.
   - Jika *Wajah Baru*, pengunjung mengisi formulir pendaftaran singkat (nama, email, no. handphone, instansi, gender) dan merekam deskriptor wajah baru.
3. **Pilihan Layanan & Cetak Tiket**: Pengunjung memilih jenis layanan (PST, Konsultasi, atau Rekomendasi) beserta tujuan kunjungan. Kiosk mencetak tiket antrean thermal yang dilengkapi nomor tiket unik dan QR Code pelacakan.
4. **Pemanggilan & Pelayanan**: Admin operator memanggil nomor antrean melalui dasbor admin B-ONE secara real-time. Pengunjung memantau posisinya melalui QR Code tiket.
5. **Pemberian Rating**: Setelah pelayanan selesai, status diubah menjadi *done* dan pengunjung mengisi survei kepuasan layanan secara digital.

*(Visualisasi flowchart infografis alur kerja B-ONE dapat dilihat pada dokumen cetak PDF).*

### Tata Cara Akses Layanan Kiosk
- Kiosk Pelayanan dapat diakses secara lokal di lokasi kantor BPS Kabupaten Bintan melalui terminal web browser di alamat: `http://localhost:8000/`.
- Layanan konsultasi dibuka setiap hari kerja (Senin - Jumat) mulai pukul 08.00 s.d 15.30 WIB.

---

## 3. Kendala dan Solusi

Berdasarkan hasil pemantauan dan evaluasi operasional aplikasi B-ONE sejak awal rilis, diidentifikasi beberapa kendala minor yang kemudian segera ditindaklanjuti dengan solusi taktis guna menjaga stabilitas sistem:

### 1. Kendala: Ukuran Wadah Kamera Biometrik di Mobile (Mobile Container Face)
- **Deskripsi Masalah**: Tampilan frame/wadah pemindaian kamera pengenalan wajah kurang responsif ketika diakses melalui browser perangkat mobile (tablet/handphone), menyebabkan sebagian area tangkapan kamera terpotong.
- **Solusi**: Penyesuaian layout container CSS menggunakan media query Tailwind CSS agar ukuran frame kamera secara dinamis menyesuaikan resolusi layar perangkat mobile (*mobile-responsive camera viewport*).

### 2. Kendala: Ketiadaan Notifikasi Tiket Langsung ke Pengunjung (No Ticket Notifications)
- **Deskripsi Masalah**: Pengunjung yang tidak mencetak tiket fisik (karena printer habis kertas atau kendala hardware) kehilangan akses link pelacakan karena tidak adanya notifikasi digital yang dikirimkan langsung ke kontak pengunjung.
- **Solusi**: Integrasi modul notifikasi otomatis berbasis WhatsApp Business API dan Email Gateway di sisi backend. Setiap kali tiket dibuat, sistem akan mengirimkan pesan notifikasi otomatis berisi detail tiket dan link pelacakan langsung ke nomor WhatsApp/email pengunjung terdaftar.

### 3. Peningkatan Fitur: Notifikasi Real-Time Operator
- **Deskripsi Peningkatan**: Menambahkan fitur notifikasi alarm suara dan pop-up instan pada dasbor admin operator setiap kali ada tiket antrean baru yang ditambahkan di kiosk untuk meminimalkan keterlambatan respon petugas.

### 4. Peningkatan Fitur: Hak Akses Khusus per Penanggung Jawab (PJ) Bidang Layanan
- **Deskripsi Peningkatan**: Penambahan sistem manajemen pengguna admin bertingkat. Setiap Penanggung Jawab (PJ) bidang layanan didaftarkan ke sistem dan memiliki akses dasbor khusus untuk memantau waktu respon layanan di areanya masing-masing sebagai peringatan awal (*early warning*) apabila terjadi penumpukan antrean.

---

Bintan, 1 Juni 2026  
Plt. Kepala BPS Kabupaten Bintan  

*(Kosong / Tanpa Tanda Tangan)*  

**Donny Cahyo Wibowo, SST., M.Si.**  
NIP. 19790203 200212 1 008  
