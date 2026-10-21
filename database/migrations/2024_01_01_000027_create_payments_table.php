<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pef_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('or_number', 50);
            $table->unsignedBigInteger('received_by');
            $table->foreign('received_by')->references('id')->on('users');
            $table->timestamp('paid_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
