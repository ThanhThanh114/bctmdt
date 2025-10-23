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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('upgrade_request_id');
            $table->string('transaction_id')->unique(); // Mã giao dịch
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['bank_transfer', 'qr_code', 'cash'])->default('qr_code');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->text('qr_code_url')->nullable(); // URL của QR code
            $table->text('payment_proof')->nullable(); // URL ảnh chứng từ thanh toán
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('upgrade_request_id')->references('id')->on('upgrade_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
