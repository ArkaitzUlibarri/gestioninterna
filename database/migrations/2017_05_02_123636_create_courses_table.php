<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Tabla con los planes de los cursos
        
        Schema::create('planes', function (Blueprint $table) {
            $table->increments('id');        
            $table->string('area');
            $table->string('name');
            $table->tinyInteger('hours')->unsigned();

            $table->unique(['area', 'name']);  
        });
*/
        /**
         * Tabla con los datos de los cursos en funcion del año
        
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');   
            $table->integer('plan_id')->unsigned();     
            $table->smallInteger('year')->unsigned();
            $table->tinyInteger('hobetuz')->unsigned()->default(0);
            $table->tinyInteger('hobetuz_done')->unsigned()->default(0);
            $table->tinyInteger('tripartita')->unsigned()->default(0);
            $table->tinyInteger('without_subsidy')->unsigned()->default(0);
            $table->tinyInteger('attendees')->unsigned()->default(0);

            $table->foreign('plan_id')
                  ->references('id')
                  ->on('planes')
                  ->onDelete('cascade');
        });
    */     
        /**
         * Tabla con los grupos relativos a cada curso
         
        Schema::create('course_groups', function (Blueprint $table) {
            $table->increments('id');   
            $table->integer('course_id')->unsigned();     
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('hours')->unsigned();

            $table->foreign('course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('cascade');
        });
*/
        /**
         * Tabla con el calendario de días de cada curso
        
        Schema::create('calendar_course', function (Blueprint $table) {
            $table->increments('id');   
            $table->date('date');  
            $table->integer('course_group_id')->unsigned();
            $table->time('start_time');
            $table->time('end_time');

            $table->foreign('group_id')
                  ->references('id')
                  ->on('course_groups')
                  ->onDelete('cascade');
        });
       */       
        
        /**
         * Tabla con los datos de los asistentes a los cursos
         
        Schema::create('attendees', function (Blueprint $table) {
            $table->integer('user_id')->unsigned(); 
            $table->integer('group_id')->unsigned();     
            $table->decimal('participation', 5, 2);  
            $table->boolean('open_invitation');
            $table->tinyInteger('loadwork_absences')->unsigned()->nullable(); 
            $table->integer('project_id')->unsigned()->nullable();  
            $table->tinyInteger('other_absences')->unsigned()->nullable();
            $table->decimal('mark', 5, 2)->nullable();  
            $table->text('comments')->nullable();  

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('group_id')
                  ->references('id')
                  ->on('course_groups')
                  ->onDelete('cascade');

            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade'); 

            $table->unique(['user_id', 'group_id']);       
        }); 
         */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        //Schema::dropIfExists('attendees');
        //Schema::dropIfExists('calendar_course');
        //Schema::dropIfExists('course_groups');
        //Schema::dropIfExists('courses');
        //Schema::dropIfExists('planes');
        //DB::statement('SET FOREIGN_KEY_CHECKS = 1'); 
    }
}
