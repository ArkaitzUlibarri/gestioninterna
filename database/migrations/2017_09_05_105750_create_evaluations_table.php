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
         * Tabla con la lista de eficiencias disponibles asociadas a los grupos
         */
        /*
        Schema::create('averages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('efficiency_id')->unsigned();
            $table->tinyInteger('month')->unsigned();
            $table->float('average', 8, 2)->unsigned();

            $table->foreign('efficiency_id')
                  ->references('id')
                  ->on('efficiencies')
                  ->onDelete('cascade');

            $table->unique(['efficiency_id', 'month']); 
        });
        */

        /**
         * Tabla con la lista de eficiencias disponibles asociadas a los grupos
         */
        /*
        Schema::create('efficiencies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned();
            $table->string('short_escription');
            $table->string('description')->nullable();
            $table->integer('average_id')->unsigned();

            $table->foreign('group_id')
                  ->references('id')
                  ->on('groups')
                  ->onDelete('cascade');

            $table->foreign('average_id')
                  ->references('id')
                  ->on('averages')
                  ->onDelete('cascade');

            $table->unique(['group_id', 'short_escription']); 
        });
        */

        /**
         * Tabla con la lista de desempeños por persona,proyecto y mes
         */
        Schema::create('performances', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('year')->unsigned();
            $table->tinyInteger('month')->unsigned();            
            $table->integer('user_id')->unsigned();
            $table->integer('project_id')->unsigned()->nullable();
            $table->enum('type',['quality', 'efficiency', 'knowledge', 'availability']);
            //$table->integer('efficiency_id')->unsigned()->nullable();
            $table->tinyInteger('mark')->unsigned();
            $table->string('comment')->nullable();
            $table->float('weight',5,2)->unsigned();
            $table->integer('pm_id')->unsigned();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade'); 
            /*
            $table->foreign('efficiency_id')
                  ->references('id')
                  ->on('efficiencies')
                  ->onDelete('cascade'); 
           */
            $table->foreign('pm_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            //$table->unique(['user_id', 'project_id','type','efficiency_id','year','month']);
            $table->unique(['user_id', 'project_id','type','year','month']); 
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
        Schema::dropIfExists('efficiencies'); 
        Schema::dropIfExists('averages'); 
    }
}
