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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default initial settings
        DB::table('settings')->insert([
            [
                'key' => 'store_name',
                'value' => 'KAFE DIGITAL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'store_address',
                'value' => 'Jl. Coffee Boulevard No. 88, Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'store_phone',
                'value' => '0812-3456-7890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'receipt_footer_text',
                'value' => 'Terima Kasih Atas Kunjungan Anda!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'receipt_wifi_info',
                'value' => 'WiFi: CafeGuest / Pass: ngopidulu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
