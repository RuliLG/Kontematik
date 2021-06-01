<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewInformationToUserProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->mediumText('about')->nullable();
            $table->string('company')->nullable();
            $table->string('photo_s3_key')->nullable();
            $table->boolean('notify_new_tools')->default(false);
            $table->boolean('notify_new_products')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('about');
            $table->dropColumn('company');
            $table->dropColumn('photo_s3_key');
            $table->dropColumn('notify_new_tools');
            $table->dropColumn('notify_new_products');
        });
    }
}
