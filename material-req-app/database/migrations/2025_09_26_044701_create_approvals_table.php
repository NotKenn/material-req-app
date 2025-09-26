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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->morphs('approvable'); // bikin approvable_id + approvable_type
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status')->nullable();
            // $table->unsignedInteger('level')->default(1); // kalau multi level approval enable, gitu lh
            $table->text('notes')->nullable();
            $table->timestamp('approved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
