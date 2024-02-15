<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->references('id')->on('contracts')->onDelete('cascade')->onUpdate('cascade');
            
            $table->date('start_date');
            $table->date('end_date');

            $table->foreignId('travel_agent_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade')->default(Auth::user()->id ?? null)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
