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
        Schema::create('mr_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mr_ids')->nullable()->constrained('mr_table')->nullOnDelete();
            $table->date('tanggal');
            $table->date('tanggalPerlu');
            $table->string('lokasiPengantaran');
            $table->string('lampiran')->nullable();
            $table->string('notes')->nullable();
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
