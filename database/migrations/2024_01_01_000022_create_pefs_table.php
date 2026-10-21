<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pefs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('term_id')->constrained()->cascadeOnDelete();
            $table->enum('status', [
                'draft',
                'pending_advising',
                'pending_admission',
                'pending_registrar',
                'pending_cashier',
                'enrolled',
                'returned',
                'rejected',
                'cancelled',
            ])->default('draft');
            $table->unsignedTinyInteger('total_units')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'term_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pefs');
    }
};
