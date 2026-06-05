<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Change face_data to longText
        Schema::table('visitors', function (Blueprint $table) {
            $table->longText('face_data')->nullable()->change();
        });

        // 2. Encrypt all existing plain JSON data
        $visitors = DB::table('visitors')->whereNotNull('face_data')->get();

        foreach ($visitors as $visitor) {
            try {
                // If it can be decrypted, it's already encrypted
                Crypt::decryptString($visitor->face_data);
            } catch (\Exception $e) {
                // Not encrypted yet, let's encrypt it
                // We use Crypt::encryptString on the raw JSON string.
                // Laravel's encrypted:array cast expects Crypt::encryptString(json_encode($array))
                // But the column currently holds the plain JSON string. So we just encrypt the plain JSON string!
                // Wait! 'encrypted:array' cast uses encrypt(json_encode($value)) and json_decode(decrypt($value), true)
                // So if the current DB has a JSON string e.g. "[[...]]", and we encrypt that string, 
                // when Laravel fetches it with 'encrypted:array', it decrypts it, getting the string "[[...]]", 
                // and then json_decode()s it into a PHP array.
                // So we just need to encrypt the raw string value!
                
                $encrypted = Crypt::encryptString($visitor->face_data);
                DB::table('visitors')->where('id', $visitor->id)->update([
                    'face_data' => $encrypted
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Decrypt all data back to plain JSON
        $visitors = DB::table('visitors')->whereNotNull('face_data')->get();

        foreach ($visitors as $visitor) {
            try {
                $plain = Crypt::decryptString($visitor->face_data);
                DB::table('visitors')->where('id', $visitor->id)->update([
                    'face_data' => $plain
                ]);
            } catch (\Exception $e) {
                // Already plain
            }
        }
        
        // Cannot revert longText to json reliably in all SQL dialects, so we leave it as longText
    }
};
