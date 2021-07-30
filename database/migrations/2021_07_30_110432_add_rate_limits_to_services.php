<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRateLimitsToServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedInteger('max_generations_per_minute')->nullable();
            $table->unsignedInteger('max_generations_per_hour')->nullable();
            $table->unsignedInteger('max_generations_per_action')->nullable();
        });
    }
}
