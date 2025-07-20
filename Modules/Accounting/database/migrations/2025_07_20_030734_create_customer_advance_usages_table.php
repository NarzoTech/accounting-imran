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
        Schema::create('customer_advance_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_advance_id')->constrained();
            $table->foreignId('invoice_id')->nullable()->constrained();
            $table->decimal('used_amount', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_advance_usages');
    }
};
