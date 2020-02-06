<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->longText('site_partner_desc')->nullable();
            $table->longText('site_en_partner_desc')->nullable();
            $table->longText('site_service_desc')->nullable();
            $table->longText('site_en_service_desc')->nullable();
            $table->string('playstore_link', 100)->nullable();
            $table->string('appstore_lin', 100)->nullable();
            $table->string('web_link', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['site_partner_desc', 'site_en_partner_desc', 'site_service_desc', 'site_en_service_desc', 'playstore_link', 'appstore_link', 'web_link']);
        });
    }
}
