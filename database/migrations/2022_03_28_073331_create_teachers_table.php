<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('Name');
            $table->string('Email')->unique();
            $table->string('Password');
            $table->foreignId('Specialization_id')->references('id')->on('specializations')->onDelete('cascade');
            $table->foreignId('Gender_id')->references('id')->on('genders')->onDelete('cascade');
            $table->date('Joining_Date');
            $table->text('Address');
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
        Schema::dropIfExists('teachers');
    }
}
