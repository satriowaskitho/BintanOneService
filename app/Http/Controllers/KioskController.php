<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\ServiceType;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $visitor = Visitor::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'gender' => $validated['gender'],
            'institution' => $validated['institution'],
            'face_data' => json_decode($validated['face_data'], true)
        ]);

        return redirect()->route('kiosk.ticket', $visitor->id);
    }

    public function ticket(Visitor $visitor)
    {
        // 1. Cek apakah visitor memiliki antrean aktif (waiting/called) hari ini
        $activeQueue = Queue::where('visitor_id', $visitor->id)
            ->whereDate('created_at', Carbon::today())
            ->whereIn('status', ['waiting', 'called'])
            ->first();

        // Jika ada, kembalikan ke struk tiketnya dengan pesan info
        if ($activeQueue) {
            return redirect()->route('kiosk.success', $activeQueue->id)
                ->with('info', 'Anda masih memiliki antrean aktif. Tidak perlu mengambil tiket baru.');
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

        // Backend Service Validations
        // 1. Active Queue Constraint
        $activeQueue = Queue::where('visitor_id', $validated['visitor_id'])
            ->whereDate('created_at', $today)
            ->whereIn('status', ['waiting', 'called'])
            ->first();

        if ($activeQueue) {
            return redirect()->route('kiosk.success', $activeQueue->id)
                ->with('info', 'Anda masih memiliki antrean aktif hari ini.');
        }

        // 2. Anti-Spam 1-Minute Cooldown Constraint (Based on Creation Time)
        $recentQueue = Queue::where('visitor_id', $validated['visitor_id'])
            ->where('created_at', '>=', now()->subMinute())
            ->first();

        if ($recentQueue) {
            return redirect()->route('kiosk.success', $recentQueue->id)
                ->with('info', 'Anda mengambil tiket terlalu cepat. Harap tunggu 1 menit sebelum membuat tiket lagi.');
        }

        $queue = DB::transaction(function () use ($validated, $today) {
            $service = ServiceType::find($validated['service_type_id']);
            
            // Atomic lock for queue number generation
            $lastQueue = Queue::where('service_type_id', $service->id)
                ->whereDate('created_at', $today)
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = 1;
            if ($lastQueue) {
                $parts = explode('-', $lastQueue->queue_number);
                $nextNumber = intval($parts[1]) + 1;
            }

            $queueNumber = $service->code . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            return Queue::create([
                'visitor_id' => $validated['visitor_id'],
                'service_type_id' => $service->id,
                'queue_number' => $queueNumber,
                'purpose' => $validated['purpose'],
                'status' => 'waiting',
                'token' => Str::random(10)
            ]);
        });

        return redirect()->route('kiosk.success', $queue->id);
    }

    public function success(Queue $queue)
    {
        $queue->load(['visitor', 'serviceType']);
        return view('kiosk.success', compact('queue'));
    }
}
