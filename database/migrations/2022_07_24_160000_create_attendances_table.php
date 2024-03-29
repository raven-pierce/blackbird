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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('lecture_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->unique(['enrollment_id', 'lecture_id']);
            $table->dateTime('join_date');
            $table->dateTime('leave_date');
            $table->integer('duration');
            $table->foreignId('invoice_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('paid')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
