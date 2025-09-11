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
        Schema::create('mr_table', function (Blueprint $table) {
            $table->id();
            $table->string('kodeRequest');
            $table->foreignId('requester_id')
                  ->nullable()
                  ->constrained('requesters')
                  ->nullOnDelete();
            $table->dateTime('created_at')->useCurrent();
            $table->string('status');
            $table->string('po_file');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
