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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('vehicle_number');
            $table->string('phone');
            $table->integer('no_of_trips'); // Fixed: No spaces & changed to integer
            $table->decimal('balance', 10, 2); // Fixed: Balance should be decimal for money

            $table->timestamps();
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn(['no_of_trips', 'balance']); // Remove columns if rolled back
        });
    }

};
