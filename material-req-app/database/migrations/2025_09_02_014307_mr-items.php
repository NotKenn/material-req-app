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
        Schema::create('mr_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mr_id')->nullable()->constrained('mr_table')->nullOnDelete();
            $table->string('itemName');
            $table->string('Qty');
            $table->string('satuan');
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
