<?php

use DutchCodingCompany\FilamentSocialite\Facades\FilamentSocialite;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('socialite_users', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(FilamentSocialite::getUserModelClass(), 'user_id');
            $table->string('provider');
            $table->string('provider_id');

            $table->timestamps();

            $table->unique([
                'provider',
                'provider_id',
            ]);
        });
    }

    public function down()
    {
        Schema::dropIfExists('socialite_users');
    }
};
