<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['city', 'street']);
            $table->string('country', 100)->nullable();
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('city', 100)->nullable();
            $table->string('street', 255)->nullable();
            $table->dropColumn('country');
        });
    }
};
