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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('driver_id');
            $table->decimal('quantity', 10, 2);
            $table->decimal('price_per_ton', 10, 2);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->decimal('supplier_balance', 10, 2)->default(0);
            $table->decimal('driver_balance', 10, 2)->default(0);
            $table->decimal('transportation_cost', 10, 2)->default(0);
            $table->string('from');
            $table->string('to');
            $table->date('date'); // 🟢 Simply Adding `date` Column

            // Foreign keys
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
