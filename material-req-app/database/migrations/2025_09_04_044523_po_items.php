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
        Schema::create('po_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->nullable()->constrained('po_details')->nullOnDelete();
            $table->foreignId('mr_item_id')->nullable()->constrained('mr_items')->nullOnDelete();
            $table->string('itemName');
            $table->string('qty');
            $table->string('unit');
            $table->string('price')->default(0);
            $table->string('amount')->default(0);
            $table->string('subtotal')->default(0);
            $table->string('discount')->default(0);
            $table->string('total')->default(0);
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
