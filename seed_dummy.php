<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$visitor = \App\Models\Visitor::firstOrCreate(['name' => 'Dummy Visitor', 'gender' => 'Laki-laki', 'face_data' => '[0]']);
$services = \App\Models\ServiceType::pluck('id')->toArray();
if (empty($services)) {
    echo "No services found.";
    exit;
}

for ($i = 13; $i >= 0; $i--) {
    $date = \Carbon\Carbon::today()->subDays($i);
    $count = rand(3, 5);
    for ($j = 0; $j < $count; $j++) {
        \App\Models\Queue::create([
            'visitor_id' => $visitor->id,
            'service_type_id' => $services[array_rand($services)],
            'queue_number' => 'DUMMY-' . $i . '-' . $j,
            'status' => 'done',
            'token' => \Illuminate\Support\Str::random(10),
            'created_at' => $date->copy()->addHours($j),
            'updated_at' => $date->copy()->addHours($j)
        ]);
    }
}
echo "Seeded successfully.\n";
