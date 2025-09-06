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
        Schema::create('po_mr', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->nullable()->constrained('po_details')->nullOnDelete();
            $table->foreignId('mr_id')->nullable()->constrained('mr_table')->nullOnDelete();
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
