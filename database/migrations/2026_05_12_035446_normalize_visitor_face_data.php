<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $visitors = DB::table('visitors')->get();

        foreach ($visitors as $visitor) {
            if (empty($visitor->face_data)) continue;

            $data = json_decode($visitor->face_data, true);
            
            // Check if it's an array and not empty
            if (is_array($data) && count($data) > 0) {
                // If the first element is NOT an array, it means it's a flat 1D array (old format)
                if (!is_array($data[0])) {
                    // Normalize it by wrapping in another array
                    $normalized = [$data];
                    DB::table('visitors')
                        ->where('id', $visitor->id)
                        ->update(['face_data' => json_encode($normalized)]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // One-way migration to prevent corrupting valid multi-descriptor data back into single descriptor
    }
};
