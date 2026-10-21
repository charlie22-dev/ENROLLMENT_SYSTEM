<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pef_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pef_id')->constrained()->cascadeOnDelete();
            $table->string('stage', 30);
            $table->enum('action', ['submitted', 'approved', 'returned', 'rejected', 'cancelled']);
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('acted_by');
            $table->foreign('acted_by')->references('id')->on('users');
            $table->timestamp('acted_at');
            $table->timestamps();

            $table->index('pef_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pef_approvals');
    }
};
