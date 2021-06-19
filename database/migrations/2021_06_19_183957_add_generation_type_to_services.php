<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGenerationTypeToServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->enum('generation_type', ['single', 'per_line'])->default('single');
            $table->string('per_line_generation_field_name')->nullable();
            $table->integer('per_line_max_lines')->nullable();
            $table->integer('per_line_max_generations_per_minute')->nullable();
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
            $table->dropColumn('generation_type');
            $table->dropColumn('per_line_max_lines');
            $table->dropColumn('per_line_generation_field_name');
            $table->dropColumn('per_line_max_generations_per_minute');
        });
    }
}
