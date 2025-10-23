<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_scans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->string('ticket_code', 50);
            $table->integer('staff_id');
            $table->string('staff_name', 100);
            $table->timestamp('scanned_at');
            $table->string('scan_location', 100)->nullable();
            
            // Don't use foreign key to avoid constraint issues
            $table->index(['booking_id', 'scanned_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_scans');
    }
};
