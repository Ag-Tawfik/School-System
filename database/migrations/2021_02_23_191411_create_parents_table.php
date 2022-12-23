<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');

            //Father information
            $table->string('fatherName');
            $table->string('fatherNationalID');
            $table->string('fatherPassportID');
            $table->string('fatherPhoneNumber');
            $table->string('fatherJobTitle');
            $table->foreignId('fatherNationalty_id')->constrained('nationalities')->onDelete('cascade');
            $table->foreignId('fatherBloodType_id')->constrained('blood_types')->onDelete('cascade');
            $table->foreignId('fatherReligion_id')->constrained('religions')->onDelete('cascade');
            $table->string('fatherAddress');

            //Mother information
            $table->string('motherName');
            $table->string('motherNationalID');
            $table->string('motherPassportID');
            $table->string('motherPhoneNumber');
            $table->string('motherJobTitle');
            $table->foreignId('motherNationalty_id')->constrained('nationalities')->onDelete('cascade');
            $table->foreignId('motherBloodType_id')->constrained('blood_types')->onDelete('cascade');
            $table->foreignId('motherReligion_id')->constrained('religions')->onDelete('cascade');
            $table->string('motherAddress');
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
        Schema::dropIfExists('parents');
    }
}
