<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Tabla con la lista de desempeños por persona,proyecto y mes
         */
        Schema::create('performances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->enum('type',config('options.criterion'));
            $table->smallInteger('year')->unsigned();
            $table->tinyInteger('month')->unsigned();
            $table->string('comments')->nullable();
            $table->tinyInteger('mark')->unsigned();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade'); 
        });

        /**
         * Tabla con la lista de desempeños por persona al año
         */
        Schema::create('historical_performances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->smallInteger('year')->unsigned();
            $table->tinyInteger('mark')->unsigned();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performances'); 
        Schema::dropIfExists('historical_performances'); 
    }
}
