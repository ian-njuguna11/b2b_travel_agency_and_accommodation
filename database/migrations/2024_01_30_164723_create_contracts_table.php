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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->decimal('contract_rates', 8, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('accommodation_id')->references('id')->on('accommodations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('travel_agent_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
