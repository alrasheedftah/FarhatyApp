<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address');
            $table->string('nickname',50)->nullable();
            $table->text('description');
            $table->string('gender',10);
            $table->string('image')->nullable();
            $table->string('beginning_work')->nullable();
            $table->string('working_time')->nullable();
            $table->string("status")->nullable()->default("AVIALABLE");
            $table->string('phone',10)->unique();

            // foreginKey Specialty
            $table->unsignedBigInteger('specialties_id')->unsigned();
            $table->foreign('specialties_id')->references('id')->on('specialties')->onDelete('cascade');

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
        Schema::dropIfExists('professors');
    }
}
