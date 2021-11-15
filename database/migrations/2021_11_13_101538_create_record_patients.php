<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordPatients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('record_patients', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_id')->unsigned();
            $table->bigInteger('status_covid_id')->unsigned();
            $table->date('in_date_at');
            $table->date('out_date_at');
            $table->timestamps();

            /**
             * relationship with cascace
             * cascade means auto delete to all table who reference the recorded data
             */
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('status_covid_id')->references('id')->on('status_covid')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('record_patients');
    }
}
