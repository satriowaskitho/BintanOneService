<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Antrean B-ONE</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f3f4f6; padding: 20px; margin: 0; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
        <!-- Header -->
        <div style="background-color: #2563eb; padding: 30px; text-align: center; color: #ffffff;">
            <h2 style="margin: 0; font-size: 24px; font-weight: bold; letter-spacing: 0.05em;">BINTAN ONE SERVICE</h2>
            <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">Badan Pusat Statistik Kabupaten Bintan</p>
        </div>

        <!-- Body Content -->
        <div style="padding: 30px; text-align: center;">
            <p style="margin-top: 0; font-size: 16px; color: #4b5563;">Halo <strong>{{ $queueTicket->visitor->name }}</strong>,</p>
            <p style="font-size: 16px; color: #4b5563; line-height: 1.5;">Tiket antrean Anda berhasil dibuat. Berikut rincian antrean Anda:</p>

            <div style="background-color: #f9fafb; border: 1px solid #f3f4f6; border-radius: 8px; padding: 20px; margin: 25px 0; text-align: center;">
                <span style="font-size: 14px; text-transform: uppercase; color: #9ca3af; letter-spacing: 0.1em; display: block; margin-bottom: 5px;">Nomor Antrean</span>
                <strong style="font-size: 48px; color: #2563eb; font-family: 'Courier New', monospace; font-weight: 900; display: block; line-height: 1;">{{ $queueTicket->queue_number }}</strong>
                <span style="font-size: 16px; font-weight: bold; color: #374151; display: block; margin-top: 15px;">{{ $queueTicket->serviceType->name }}</span>
            </div>

            <!-- QR code and Link -->
            <div style="margin: 30px 0;">
                <p style="font-size: 14px; color: #6b7280; margin-bottom: 15px;">Pindai QR code di bawah ini untuk memantau status antrean secara real-time:</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($trackingUrl) }}" alt="QR Tracking" style="border: 4px solid #f3f4f6; padding: 8px; border-radius: 8px; background-color: #ffffff; width: 150px; height: 150px;" />
            </div>

            <div style="margin-top: 30px;">
                <a href="{{ $trackingUrl }}" style="display: inline-block; background-color: #2563eb; color: #ffffff; padding: 14px 28px; font-size: 16px; font-weight: bold; text-decoration: none; border-radius: 8px; transition: background-color 0.2s;">
                    Pantau Antrean Anda
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb;">
            <p style="margin: 0 0 5px 0;">Sistem Antrean Digital B-ONE</p>
            <p style="margin: 0;">Email ini dikirim secara otomatis oleh sistem. Harap tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
