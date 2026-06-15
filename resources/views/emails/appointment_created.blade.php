<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Janji Temu B-ONE</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f3f4f6; padding: 20px; margin: 0; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
        <!-- Header -->
        <div style="background-color: #059669; padding: 30px; text-align: center; color: #ffffff;">
            <h2 style="margin: 0; font-size: 24px; font-weight: bold; letter-spacing: 0.05em;">BINTAN ONE SERVICE</h2>
            <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">Konfirmasi Jadwal Janji Temu</p>
        </div>

        <!-- Body Content -->
        <div style="padding: 30px;">
            <p style="margin-top: 0; font-size: 16px; color: #4b5563; text-align: center;">Halo <strong>{{ $appointment->visitor->name }}</strong>,</p>
            <p style="font-size: 16px; color: #4b5563; line-height: 1.5; text-align: center;">Jadwal janji temu Anda telah berhasil dibuat. Berikut adalah rincian janji temu Anda:</p>

            <table style="width: 100%; border-collapse: collapse; margin: 25px 0; font-size: 14px; color: #374151;">
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; font-weight: bold; color: #6b7280; width: 40%;">Layanan</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; font-weight: bold; color: #111827;">{{ $appointment->serviceType->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; font-weight: bold; color: #6b7280;">Tanggal Janji</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; font-weight: bold; color: #111827;">{{ $appointment->date->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; font-weight: bold; color: #6b7280;">Jam Janji</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; font-weight: bold; color: #111827;">{{ substr($appointment->time, 0, 5) }} WIB</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; font-weight: bold; color: #6b7280;">Keperluan</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #374151;">{{ $appointment->purpose }}</td>
                </tr>

            </table>

            <!-- QR code and Link -->
            <div style="margin: 30px 0; text-align: center;">
                <p style="font-size: 14px; color: #6b7280; margin-bottom: 15px;">Simpan QR code di bawah ini untuk Check-In atau melacak janji temu Anda:</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($trackingUrl) }}" alt="QR Tracking" style="border: 4px solid #f3f4f6; padding: 8px; border-radius: 8px; background-color: #ffffff; width: 150px; height: 150px;" />
            </div>

            <div style="margin-top: 30px; text-align: center;">
                <a href="{{ $trackingUrl }}" style="display: inline-block; background-color: #059669; color: #ffffff; padding: 14px 28px; font-size: 16px; font-weight: bold; text-decoration: none; border-radius: 8px; transition: background-color 0.2s;">
                    Pantau / Ubah Jadwal Janji
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb;">
            <p style="margin: 0 0 5px 0;">Sistem Janji Temu Digital B-ONE</p>
            <p style="margin: 0;">Email ini dikirim secara otomatis oleh sistem. Harap tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
