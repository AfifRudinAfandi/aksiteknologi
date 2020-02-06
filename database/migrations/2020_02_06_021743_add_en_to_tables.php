<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->longText('en_description')->nullable();
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->longText('en_bio')->nullable();
        });

        Schema::table('testimony', function (Blueprint $table) {
            $table->longText('en_content')->nullable();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->longText('en_about_us')->nullable();
            $table->longText('en_vision')->nullable();
            $table->longText('en_mission')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('en_description');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('en_bio');
        });

        Schema::table('testimony', function (Blueprint $table) {
            $table->dropColumn('en_content');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('en_about_us');
            $table->dropColumn('en_vision');
            $table->dropColumn('en_mission');
        });

    }
}
