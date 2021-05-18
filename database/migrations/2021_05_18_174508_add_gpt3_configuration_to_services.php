<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGpt3ConfigurationToServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->float('gpt3_temperature')->default(0.9);
            $table->unsignedInteger('gpt3_tokens')->default(40);
            $table->unsignedInteger('gpt3_best_of')->default(3);
            $table->unsignedInteger('gpt3_n')->default(3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('gpt3_temperature');
            $table->dropColumn('gpt3_tokens');
            $table->dropColumn('gpt3_best_of');
            $table->dropColumn('gpt3_n');
        });
    }
}
