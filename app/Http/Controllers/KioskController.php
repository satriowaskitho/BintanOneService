<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\ServiceType;
use App\Models\Queue;
use App\Models\Appointment;
use App\Mail\QueueCreatedMail;
use App\Mail\AppointmentCreatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KioskController extends Controller
{
    public function scan()
    {
        return view('kiosk.scan');
    }

    public function getVisitors()
    {
        $visitors = Visitor::whereNotNull('face_data')->get(['id', 'name', 'face_data']);
        return response()->json($visitors);
    }

    public function register()
    {
        return view('kiosk.register');
    }

    public function storeVisitor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'gender' => 'required|string',
            'institution' => 'nullable|string',
            'face_data' => 'required|string' // JSON string from client
        ]);

        // Silent Face Matching to prevent duplicate registrations
        $newFaceData = json_decode($validated['face_data'], true);
        if ($newFaceData && is_array($newFaceData) && count($newFaceData) > 0) {
            $newDescriptor = $newFaceData[0];
            if (is_array($newDescriptor) && count($newDescriptor) === 128) {
                $bestDistance = 1.0;
                $matchingVisitor = null;

                $visitors = Visitor::whereNotNull('face_data')->get();
                foreach ($visitors as $v) {
                    $storedFaceData = $v->face_data;
                    if ($storedFaceData && is_array($storedFaceData)) {
                        if (isset($storedFaceData[0]) && is_array($storedFaceData[0])) {
                            foreach ($storedFaceData as $stored) {
                                if (is_array($stored) && count($stored) === 128) {
                                    $dist = $this->euclideanDistance($newDescriptor, $stored);
                                    if ($dist < $bestDistance) {
                                        $bestDistance = $dist;
                                        $matchingVisitor = $v;
                                    }
                                }
                            }
                        } else {
                            if (count($storedFaceData) === 128) {
                                $dist = $this->euclideanDistance($newDescriptor, $storedFaceData);
                                if ($dist < $bestDistance) {
                                    $bestDistance = $dist;
                                    $matchingVisitor = $v;
                                }
                            }
                        }
                    }
                }

                if ($matchingVisitor && $bestDistance < 0.45) {
                    return redirect()->route('kiosk.options.confirm-match', $matchingVisitor->id);
                }
            }
        }

        $visitor = Visitor::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? '',
            'phone' => $validated['phone'] ?? '',
            'gender' => $validated['gender'],
            'institution' => $validated['institution'] ?? '',
            'face_data' => json_decode($validated['face_data'], true)
        ]);

        return redirect()->route('kiosk.options', $visitor->id);
    }

    public function options(Visitor $visitor)
    {
        $today = Carbon::today();

        // 1. If visitor already has active queue (waiting/called), redirect to tracking page
        $activeQueue = Queue::where('visitor_id', $visitor->id)
            ->whereDate('created_at', $today)
            ->whereIn('status', ['waiting', 'called'])
            ->first();

        if ($activeQueue) {
            $signedUrl = URL::temporarySignedRoute('queue.track', now()->addHours(24), ['token' => $activeQueue->token]);
            return redirect($signedUrl)->with('info', 'Anda memiliki antrean aktif saat ini.');
        }

        // 2. Check if visitor has a scheduled appointment today
        $todayAppointment = Appointment::where('visitor_id', $visitor->id)
            ->whereDate('date', $today)
            ->where('status', 'scheduled')
            ->first();

        return view('kiosk.options', compact('visitor', 'todayAppointment'));
    }

    public function confirmMatch(Visitor $visitor)
    {
        return view('kiosk.confirm_match', compact('visitor'));
    }

    public function verifyIdentityForm(Visitor $visitor)
    {
        return view('kiosk.verify_identity', compact('visitor'));
    }

    public function verifyIdentitySubmit(Request $request, Visitor $visitor)
    {
        $validated = $request->validate([
            'verification' => 'required|string'
        ]);

        $input = trim($validated['verification']);

        // Check against phone or email
        if ($input === $visitor->phone || strtolower($input) === strtolower($visitor->email)) {
            session(['verified_visitor_id' => $visitor->id]);
            return redirect()->route('kiosk.options.edit-profile', $visitor->id);
        }

        return back()->withErrors(['verification' => 'Verifikasi gagal. Nomor HP atau Email tidak cocok.']);
    }

    public function editProfile(Visitor $visitor)
    {
        if (session('verified_visitor_id') !== $visitor->id) {
            return redirect()->route('kiosk.options.verify', $visitor->id);
        }

        return view('kiosk.edit_profile', compact('visitor'));
    }

    public function updateProfile(Request $request, Visitor $visitor)
    {
        if (session('verified_visitor_id') !== $visitor->id) {
            return redirect()->route('kiosk.options.verify', $visitor->id);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'institution' => 'nullable|string|max:255'
        ]);

        $visitor->update($validated);
        session()->forget('verified_visitor_id');

        return redirect()->route('kiosk.options', $visitor->id)->with('status', 'Profil berhasil diperbarui.');
    }

    public function ticket(Visitor $visitor)
    {
        $activeQueue = Queue::where('visitor_id', $visitor->id)
            ->whereDate('created_at', Carbon::today())
            ->whereIn('status', ['waiting', 'called'])
            ->first();

        if ($activeQueue) {
            $signedUrl = URL::temporarySignedRoute('queue.track', now()->addHours(24), ['token' => $activeQueue->token]);
            return redirect($signedUrl)->with('info', 'Anda memiliki antrean aktif saat ini.');
        }

        $services = ServiceType::all();
        return view('kiosk.ticket', compact('visitor', 'services'));
    }

    public function generateQueue(Request $request)
    {
        $validated = $request->validate([
            'visitor_id' => 'required|exists:visitors,id',
            'service_type_id' => 'required|exists:service_types,id',
            'purpose' => 'nullable|string'
        ]);

        $today = Carbon::today();

        // 1. Max 1 Active Queue per Visitor
        $activeQueue = Queue::where('visitor_id', $validated['visitor_id'])
            ->whereDate('created_at', $today)
            ->whereIn('status', ['waiting', 'called'])
            ->first();

        if ($activeQueue) {
            $signedUrl = URL::temporarySignedRoute('queue.track', now()->addHours(24), ['token' => $activeQueue->token]);
            return redirect($signedUrl)->with('info', 'Anda memiliki antrean aktif saat ini.');
        }

        // 2. Anti-Spam 1-Minute Cooldown
        $recentQueue = Queue::where('visitor_id', $validated['visitor_id'])
            ->where('created_at', '>=', now()->subMinute())
            ->first();

        if ($recentQueue) {
            $signedUrl = URL::temporarySignedRoute('queue.track', now()->addHours(24), ['token' => $recentQueue->token]);
            return redirect($signedUrl)->with('info', 'Harap tunggu 1 menit sebelum membuat antrean baru.');
        }

        $queue = DB::transaction(function () use ($validated, $today) {
            // Lock queries matching service_type_id + date on queues table
            // Wait, we lock using lockForUpdate() on the queues generated today for this service type.
            $lastQueue = Queue::where('service_type_id', $validated['service_type_id'])
                ->whereDate('created_at', $today)
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            $service = ServiceType::find($validated['service_type_id']);
            $nextNumber = 1;
            if ($lastQueue) {
                $parts = explode('-', $lastQueue->queue_number);
                $nextNumber = intval($parts[1]) + 1;
            }

            $queueNumber = $service->code . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $q = Queue::create([
                'visitor_id' => $validated['visitor_id'],
                'service_type_id' => $service->id,
                'queue_number' => $queueNumber,
                'purpose' => $validated['purpose'] ?? '',
                'status' => 'waiting',
                'queue_source' => 'walk_in',
                'token' => Str::random(10),
                'last_email_sent_at' => now(),
            ]);

            return $q;
        });

        // Send Email asynchronously via Queue
        if ($queue->visitor->email) {
            $trackingUrl = URL::temporarySignedRoute('queue.track', now()->addHours(24), ['token' => $queue->token]);
            Mail::to($queue->visitor->email)->queue(new QueueCreatedMail($queue, $trackingUrl));
        }

        return redirect()->route('kiosk.success', $queue->id);
    }

    public function checkIn(Appointment $appointment)
    {
        $today = Carbon::today();

        if ($appointment->status !== 'scheduled') {
            return redirect()->route('kiosk.options', $appointment->visitor_id)
                ->withErrors(['error' => 'Janji temu tidak dapat di-check-in karena status tidak valid.']);
        }

        if ($appointment->date->format('Y-m-d') !== $today->format('Y-m-d')) {
            return redirect()->route('kiosk.options', $appointment->visitor_id)
                ->withErrors(['error' => 'Janji temu hanya dapat di-check-in pada hari H.']);
        }

        // Check if visitor already has active queue today
        $activeQueue = Queue::where('visitor_id', $appointment->visitor_id)
            ->whereDate('created_at', $today)
            ->whereIn('status', ['waiting', 'called'])
            ->first();

        if ($activeQueue) {
            $signedUrl = URL::temporarySignedRoute('queue.track', now()->addHours(24), ['token' => $activeQueue->token]);
            return redirect($signedUrl)->with('info', 'Anda memiliki antrean aktif saat ini.');
        }

        $queue = DB::transaction(function () use ($appointment, $today) {
            // Lock for concurrency on (service_type + date)
            $lastQueue = Queue::where('service_type_id', $appointment->service_type_id)
                ->whereDate('created_at', $today)
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            $service = ServiceType::find($appointment->service_type_id);
            $nextNumber = 1;
            if ($lastQueue) {
                $parts = explode('-', $lastQueue->queue_number);
                $nextNumber = intval($parts[1]) + 1;
            }

            $queueNumber = $service->code . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // Update appointment state
            $appointment->update([
                'status' => 'checked_in',
                'checked_in_at' => now(),
            ]);

            // Create queue record
            $q = Queue::create([
                'visitor_id' => $appointment->visitor_id,
                'service_type_id' => $appointment->service_type_id,
                'queue_number' => $queueNumber,
                'purpose' => $appointment->purpose,
                'status' => 'waiting',
                'queue_source' => 'appointment',
                'checked_in_at' => now(),
                'token' => Str::random(10),
                'last_email_sent_at' => now(),
            ]);

            return $q;
        });

        // Send Queue Email
        if ($appointment->email) {
            $trackingUrl = URL::temporarySignedRoute('queue.track', now()->addHours(24), ['token' => $queue->token]);
            Mail::to($appointment->email)->queue(new QueueCreatedMail($queue, $trackingUrl));
        }

        return redirect()->route('kiosk.success', $queue->id);
    }

    public function createAppointment(Visitor $visitor)
    {
        $services = ServiceType::all();
        return view('kiosk.appointment', compact('visitor', 'services'));
    }

    public function storeAppointment(Request $request)
    {
        $validated = $request->validate([
            'visitor_id' => 'required|exists:visitors,id',
            'service_type_id' => 'required|exists:service_types,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|string',
            'purpose' => 'required|string|min:10',
            'email' => 'required|email'
        ]);

        $visitor = Visitor::findOrFail($validated['visitor_id']);

        // Rule: Prevent past hours/minutes on the current day
        $selectedDate = Carbon::parse($validated['date']);
        if ($selectedDate->isToday()) {
            $currentTime = Carbon::now()->format('H:i');
            if ($validated['time'] <= $currentTime) {
                return back()->withInput()->withErrors(['time' => 'Waktu janji temu terpilih sudah lewat untuk hari ini.']);
            }
        }

        // Rule: max 1 active appointment per visitor
        $activeAppointment = Appointment::where('visitor_id', $visitor->id)
            ->whereIn('status', ['scheduled', 'checked_in'])
            ->first();

        if ($activeAppointment) {
            return back()->withInput()->withErrors(['visitor_id' => 'Anda sudah memiliki janji temu aktif yang terjadwal.']);
        }

        // Rule: capacity limit check (max 2 per slot)
        $bookedCount = Appointment::whereDate('date', $validated['date'])
            ->where('time', $validated['time'])
            ->whereIn('status', ['scheduled', 'checked_in'])
            ->count();

        if ($bookedCount >= 2) {
            return back()->withInput()->withErrors(['time' => 'Slot waktu ini sudah penuh (kapasitas maksimal 2 janji).']);
        }

        $appointment = Appointment::create([
            'visitor_id' => $validated['visitor_id'],
            'service_type_id' => $validated['service_type_id'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'purpose' => $validated['purpose'],
            'required_documents' => '-',
            'email' => $validated['email'],
            'status' => 'scheduled',
            'token' => Str::random(10),
            'last_email_sent_at' => now(),
        ]);

        // Send Confirmation Email
        $trackingUrl = URL::temporarySignedRoute('appointment.track', now()->addHours(24), ['token' => $appointment->token]);
        Mail::to($appointment->email)->queue(new AppointmentCreatedMail($appointment, $trackingUrl));

        return redirect()->route('kiosk.appointment.success', $appointment->id);
    }

    public function appointmentSuccess(Appointment $appointment)
    {
        $appointment->load(['visitor', 'serviceType']);
        $trackingUrl = URL::temporarySignedRoute('appointment.track', now()->addHours(24), ['token' => $appointment->token]);
        return view('kiosk.appointment_success', compact('appointment', 'trackingUrl'));
    }

    public function success(Queue $queue)
    {
        $queue->load(['visitor', 'serviceType']);
        return view('kiosk.success', compact('queue'));
    }

    private function euclideanDistance(array $a, array $b): float
    {
        $sum = 0.0;
        $count = min(count($a), count($b), 128);
        for ($i = 0; $i < $count; $i++) {
            $diff = $a[$i] - $b[$i];
            $sum += $diff * $diff;
        }
        return sqrt($sum);
    }
}
