<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('day'); // 0 lunes, 1 martes, etc
            $table->boolean('active');
            
            $table->time('morningStart')->nullable();
            $table->time('morningEnd')->nullable();
            
            $table->time('afternoonStart')->nullable();
            $table->time('afternoonEnd')->nullable();

            $table->unsignedBigInteger('doctor_id');
            $table->foreign('doctor_id')->references('id')->on('users');
            
            $table->timestamps();

            //creamos la tabla en la bd usando php artisan migrate
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_days');
    }
}
