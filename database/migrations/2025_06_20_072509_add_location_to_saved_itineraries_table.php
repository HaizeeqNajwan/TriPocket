<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('saved_itineraries', function (Blueprint $table) {
        $table->string('location')->after('title'); // or place it wherever
    });
}

public function down()
{
    Schema::table('saved_itineraries', function (Blueprint $table) {
        $table->dropColumn('location');
    });
}

};
