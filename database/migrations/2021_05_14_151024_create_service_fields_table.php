<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order')->default(0)->index();
            $table->string('name');
            $table->string('label');
            $table->boolean('is_required')->default(true);
            $table->enum('type', ['text', 'textarea', 'select'])->default('text');
            $table->enum('field_location', ['default', 'targeting'])->default('default');
            $table->unsignedInteger('max_length')->default(0);
            $table->mediumText('select_options')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_fields');
    }
}
