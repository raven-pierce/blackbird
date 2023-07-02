<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('alternate_email')->nullable()->change();
            $table->string('guardian_email')->nullable()->change();
            $table->string('guardian_phone')->nullable()->change();
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
            $table->string('alternate_email')->nullable(false)->change();
            $table->string('guardian_email')->nullable(false)->change();
            $table->string('guardian_phone')->nullable(false)->change();
        });
    }
};
