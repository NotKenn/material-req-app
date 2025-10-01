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
        Schema::create('po_details', function (Blueprint $table) {
            $table->id();
            $table->string('companyName');
            $table->string('officeAddress');
            $table->string('contactName');
            $table->string('phone');
            $table->string('po_number');
            $table->date('date');
            $table->string('termOfPayment');
            $table->string('vendorID');
            $table->string('notes')->nullable();
            $table->string('isRevised')->nullable();
            $table->string('gl_disc')->nullable();
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
