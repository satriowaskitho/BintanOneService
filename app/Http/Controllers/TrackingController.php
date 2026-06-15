<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Appointment;
use App\Mail\QueueCreatedMail;
use App\Mail\AppointmentCreatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class TrackingController extends Controller
{
    public function show(Request $request, $token)
    {
        // Check signature expiration
        if (! $request->hasValidSignature()) {
            return redirect()->route('tracking.recovery')->with('error', 'Tautan pemantauan telah kedaluwarsa atau tidak valid. Silakan gunakan formulir pemulihan di bawah.')->with('token', $token);
        }

        $queue = Queue::with(['serviceType', 'visitor'])->where('token', $token)->firstOrFail();
        
        $today = Carbon::today();
        
        $queueTime = $queue->checked_in_at ?: $queue->created_at;
        
        // Count waiting queues that have earlier coalesce(checked_in_at, created_at)
        $position = Queue::whereDate('created_at', $today)
            ->where('status', 'waiting')
            ->where(function ($query) use ($queue, $queueTime) {
                $query->whereRaw('coalesce(checked_in_at, created_at) < ?', [$queueTime])
                      ->orWhere(function ($q) use ($queue, $queueTime) {
                          $q->whereRaw('coalesce(checked_in_at, created_at) = ?', [$queueTime])
                            ->where('id', '<', $queue->id);
                      });
            })
            ->count();
        
        $remainingMinutes = $position * 5;

        return view('tracking.show', compact('queue', 'position', 'remainingMinutes'));
    }

    public function showAppointment(Request $request, $token)
    {
        if (! $request->hasValidSignature()) {
            return redirect()->route('tracking.recovery')->with('error', 'Tautan pemantauan telah kedaluwarsa atau tidak valid. Silakan gunakan formulir pemulihan di bawah.')->with('token', $token);
        }

        $appointment = Appointment::with(['serviceType', 'visitor'])->where('token', $token)->firstOrFail();

        return view('tracking.show_appointment', compact('appointment'));
    }

    public function recovery()
    {
        return view('tracking.recovery');
    }

    public function recoverySubmit(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'action_type' => 'required|in:track,resend'
        ]);

        $code = trim($validated['code']);

        // Check if code matches Queue Token or Queue Number (e.g. A-001)
        $queue = Queue::where('token', $code)
            ->orWhere('queue_number', $code)
            ->first();

        if ($queue) {
            $trackingUrl = URL::temporarySignedRoute('queue.track', now()->addHours(24), ['token' => $queue->token]);

            if ($validated['action_type'] === 'resend') {
                if ($queue->last_email_sent_at && $queue->last_email_sent_at->addSeconds(60)->isFuture()) {
                    return back()->withErrors(['code' => 'Harap tunggu 60 detik sebelum mengirim ulang email.']);
                }

                $queue->update(['last_email_sent_at' => now()]);
                if ($queue->visitor->email) {
                    Mail::to($queue->visitor->email)->queue(new QueueCreatedMail($queue, $trackingUrl));
                }
                return back()->with('status', 'Email pelacakan antrean telah dikirim ulang ke: ' . ($queue->visitor->email ?: 'email terdaftar'));
            }

            return redirect($trackingUrl);
        }

        // Check if code matches Appointment Token
        $appointment = Appointment::where('token', $code)->first();

        if ($appointment) {
            $trackingUrl = URL::temporarySignedRoute('appointment.track', now()->addHours(24), ['token' => $appointment->token]);

            if ($validated['action_type'] === 'resend') {
                if ($appointment->last_email_sent_at && $appointment->last_email_sent_at->addSeconds(60)->isFuture()) {
                    return back()->withErrors(['code' => 'Harap tunggu 60 detik sebelum mengirim ulang email.']);
                }

                $appointment->update(['last_email_sent_at' => now()]);
                Mail::to($appointment->email)->queue(new AppointmentCreatedMail($appointment, $trackingUrl));
                return back()->with('status', 'Email konfirmasi janji temu telah dikirim ulang ke: ' . $appointment->email);
            }

            return redirect($trackingUrl);
        }

        return back()->withErrors(['code' => 'Kode Antrean atau Kode Booking tidak ditemukan.']);
    }

    public function resendQueueEmail(Request $request, $token)
    {
        $queue = Queue::where('token', $token)->firstOrFail();

        if ($queue->last_email_sent_at && $queue->last_email_sent_at->addSeconds(60)->isFuture()) {
            return back()->withErrors(['error' => 'Harap tunggu 60 detik sebelum mengirim ulang email.']);
        }

        $queue->update(['last_email_sent_at' => now()]);
        $trackingUrl = URL::temporarySignedRoute('queue.track', now()->addHours(24), ['token' => $queue->token]);

        if ($queue->visitor->email) {
            Mail::to($queue->visitor->email)->queue(new QueueCreatedMail($queue, $trackingUrl));
        }

        return back()->with('status', 'Email pelacakan antrean telah berhasil dikirim ulang.');
    }

    public function resendAppointmentEmail(Request $request, $token)
    {
        $appointment = Appointment::where('token', $token)->firstOrFail();

        if ($appointment->last_email_sent_at && $appointment->last_email_sent_at->addSeconds(60)->isFuture()) {
            return back()->withErrors(['error' => 'Harap tunggu 60 detik sebelum mengirim ulang email.']);
        }

        $appointment->update(['last_email_sent_at' => now()]);
        $trackingUrl = URL::temporarySignedRoute('appointment.track', now()->addHours(24), ['token' => $appointment->token]);

        Mail::to($appointment->email)->queue(new AppointmentCreatedMail($appointment, $trackingUrl));

        return back()->with('status', 'Email konfirmasi janji temu telah berhasil dikirim ulang.');
    }

    public function rate(Request $request, $token)
    {
        $queue = Queue::where('token', $token)->firstOrFail();
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:3'
        ]);

        $queue->update([
            'rating' => $request->rating
        ]);

        return back()->with('status', 'Terima kasih atas penilaian Anda!');
    }
}
