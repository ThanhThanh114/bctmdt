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
        Schema::create('upgrade_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->enum('request_type', ['Bus_owner'])->default('Bus_owner');
            $table->decimal('amount', 10, 2)->default(0); // Phí nâng cấp 0đ
            $table->enum('status', ['pending', 'payment_pending', 'paid', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->text('reason')->nullable(); // Lý do nâng cấp
            $table->text('business_info')->nullable(); // Thông tin kinh doanh (JSON)
            $table->text('admin_note')->nullable(); // Ghi chú của admin
            $table->integer('approved_by')->nullable(); // Admin ID
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upgrade_requests');
    }
};
