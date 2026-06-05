<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TrackingController extends Controller
{
    public function show($token)
    {
        $queue = Queue::with('serviceType')->where('token', $token)->firstOrFail();
        
        $today = Carbon::today();
        
        // Current actual position for "People Ahead" display
        $position = Queue::whereDate('created_at', $today)
            ->where('id', '<', $queue->id)
            ->where('status', 'waiting')
            ->count();
        
        $remainingSeconds = $position * 5 * 60;

        return view('tracking.show', compact('queue', 'position', 'remainingSeconds'));
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
