<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $filterDate = $request->input('date', Carbon::today()->format('Y-m-d'));
        
        $queues = Queue::with(['visitor', 'serviceType'])
            ->whereDate('created_at', $filterDate)
            ->orderBy('id', 'asc')
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

        return view('admin.dashboard', compact('queues', 'dates', 'counts', 'filterDate'));
    }

    public function callQueue(Queue $queue)
    {
        $queue->update(['status' => 'called']);
        return back()->with('status', 'Calling ' . $queue->queue_number);
    }

    public function doneQueue(Queue $queue)
    {
        $queue->update(['status' => 'done']);
        return back()->with('status', 'Completed ' . $queue->queue_number);
    }
}
