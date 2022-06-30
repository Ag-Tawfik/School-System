<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('from_Grade');
            $table->unsignedBigInteger('from_Classroom');
            $table->unsignedBigInteger('from_Section');
            $table->unsignedBigInteger('to_Grade');
            $table->unsignedBigInteger('to_Classroom');
            $table->unsignedBigInteger('to_Section');
            $table->timestamps();
        });

        Schema::table('promotions', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('from_Grade')->references('id')->on('grades')->onDelete('cascade');
            $table->foreign('from_Classroom')->references('id')->on('classrooms')->onDelete('cascade');
            $table->foreign('from_Section')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('to_Grade')->references('id')->on('grades')->onDelete('cascade');
            $table->foreign('to_Classroom')->references('id')->on('classrooms')->onDelete('cascade');
            $table->foreign('to_Section')->references('id')->on('sections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotions');
    }
}
