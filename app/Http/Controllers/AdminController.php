<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Appointment;
use App\Models\ServiceType;
use App\Mail\QueueCreatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $filterDate = $request->input('date', Carbon::today()->format('Y-m-d'));
        
        // Sorting queues: called -> waiting (coalesce checked_in_at/created_at) -> done
        $queues = Queue::with(['visitor', 'serviceType'])
            ->whereDate('created_at', $filterDate)
            ->orderByRaw("CASE WHEN status = 'called' THEN 1 WHEN status = 'waiting' THEN 2 ELSE 3 END ASC")
            ->orderByRaw("coalesce(checked_in_at, created_at) ASC")
            ->orderBy('id', 'asc')
            ->get();

        $appointments = Appointment::with(['visitor', 'serviceType'])
            ->whereDate('date', $filterDate)
            ->orderBy('time', 'asc')
            ->get();

        $chartRaw = Queue::selectRaw('DATE(created_at) as date, count(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(13))
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $dates = [];
        $counts = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $dates[] = $date;
            $counts[] = $chartRaw[$date] ?? 0;
        }

        return view('admin.dashboard', compact('queues', 'appointments', 'dates', 'counts', 'filterDate'));
    }

    public function callQueue(Queue $queue)
    {
        $queue->update([
            'status' => 'called',
            'started_at' => now()
        ]);
        return back()->with('status', 'Memanggil antrean: ' . $queue->queue_number);
    }

    public function doneQueue(Queue $queue)
    {
        $queue->update([
            'status' => 'done',
            'finished_at' => now()
        ]);
        return back()->with('status', 'Antrean selesai: ' . $queue->queue_number);
    }

    public function adminCheckIn(Appointment $appointment)
    {
        $today = Carbon::today();

        if ($appointment->status !== 'scheduled') {
            return back()->withErrors(['error' => 'Janji temu tidak dapat di-check-in karena status tidak valid.']);
        }

        // Check if visitor already has active queue today
        $activeQueue = Queue::where('visitor_id', $appointment->visitor_id)
            ->whereDate('created_at', $today)
            ->whereIn('status', ['waiting', 'called'])
            ->first();

        if ($activeQueue) {
            return back()->withErrors(['error' => 'Pengunjung ini sudah memiliki antrean aktif hari ini (' . $activeQueue->queue_number . ').']);
        }

        $queue = DB::transaction(function () use ($appointment, $today) {
            // Lock queries matching service_type_id + date on queues table
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

            // Update appointment
            $appointment->update([
                'status' => 'checked_in',
                'checked_in_at' => now(),
            ]);

            // Create queue
            return Queue::create([
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
        });

        // Send Queue Email
        if ($appointment->email) {
            $trackingUrl = URL::temporarySignedRoute('queue.track', now()->addHours(24), ['token' => $queue->token]);
            Mail::to($appointment->email)->queue(new QueueCreatedMail($queue, $trackingUrl));
        }

        return back()->with('status', 'Berhasil check-in janji temu. Nomor antrean: ' . $queue->queue_number);
    }

    public function adminCancel(Appointment $appointment)
    {
        if ($appointment->status !== 'scheduled') {
            return back()->withErrors(['error' => 'Hanya janji temu dengan status terjadwal yang dapat dibatalkan.']);
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('status', 'Janji temu berhasil dibatalkan.');
    }

    public function adminReschedule(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|string'
        ]);

        if ($appointment->status !== 'scheduled') {
            return back()->withErrors(['error' => 'Hanya janji temu dengan status terjadwal yang dapat diubah jadwalnya.']);
        }

        // Rule: Prevent past hours/minutes on the current day
        $selectedDate = Carbon::parse($validated['date']);
        if ($selectedDate->isToday()) {
            $currentTime = Carbon::now()->format('H:i');
            if ($validated['time'] <= $currentTime) {
                return back()->withErrors(['error' => 'Waktu janji temu terpilih sudah lewat untuk hari ini.']);
            }
        }

        // Rule: capacity limit check (max 2 per slot)
        $bookedCount = Appointment::whereDate('date', $validated['date'])
            ->where('time', $validated['time'])
            ->where('id', '!=', $appointment->id)
            ->whereIn('status', ['scheduled', 'checked_in'])
            ->count();

        if ($bookedCount >= 2) {
            return back()->withErrors(['error' => 'Slot waktu terpilih sudah penuh (kapasitas maksimal 2 janji).']);
        }

        $appointment->update([
            'date' => $validated['date'],
            'time' => $validated['time'],
            'status' => 'scheduled'
        ]);

        return back()->with('status', 'Jadwal janji temu berhasil diubah.');
    }
}
