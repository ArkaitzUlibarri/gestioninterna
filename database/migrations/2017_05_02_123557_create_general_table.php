<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Tabla con la lista de posibles ausencias
         */
        Schema::create('absences', function (Blueprint $table) {
            $table->increments('id');  
            $table->enum('code',['m','w','ee','e','r','f','o','p','h']);
            $table->enum('group',['sick leave','permission','holidays','others']); 
            $table->string('name');
        });
        /*
        Schema::create('absences', function (Blueprint $table) {
            $table->increments('id');  
            $table->enum('code',['v','b','e','p','f','o','m','mud','pend']);
            $table->enum('group',['baja','permiso','vacaciones','otros']); 
            $table->string('name');
        });
        */

        /**
         * Tabla de cientes de nuestro proyectos
         */
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');        
            $table->string('name');
        });

        /**
         * Tabla de categorias para cada grupo de trabajo de los proyectos
         */
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code', 3);
            $table->string('description');
        });

        /**
         * Tabla de categorias para cada usuario/empleado
         */
        Schema::create('category_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();    
            $table->integer('category_id')->unsigned(); 

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');    

            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade'); 

            $table->unique(['user_id', 'category_id']);      

        });


        /**
         * Tabla con la lista de proyectos de la empresa
         */
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');        
            $table->string('name');
            $table->text('description');  
            $table->integer('customer_id')->unsigned();    
            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->foreign('customer_id')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('cascade');  

            $table->unique(['name', 'customer_id']);        
        });

        /**
         * Tabla con los posiles grupos de trabajo para cada proyecto
         */
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');    
            $table->integer('project_id')->unsigned();    
            $table->string('name');
            $table->boolean('enabled')->default(true);

            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade'); 

            $table->unique(['project_id', 'name']);       
        });

        /**
         * Tabla con las usuarios pertenecientes a un grupo y su categoria
         */
        Schema::create('group_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();    
            $table->integer('group_id')->unsigned();   

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); 

            $table->foreign('group_id')
                  ->references('id')
                  ->on('groups')
                  ->onDelete('cascade');      

            $table->unique(['user_id', 'group_id']);     
     
        });

        /**
         * Tabla con la informacion de cada parte de horas
         */
        Schema::create('working_report', function (Blueprint $table) {
            $table->increments('id');    
            $table->integer('user_id')->unsigned();
            $table->date('created_at');
            $table->enum('activity',config('options.activities'));

            //Proyecto
            $table->integer('project_id')->unsigned()->nullable();
            $table->integer('group_id')->unsigned()->nullable();
            $table->integer('category_id')->unsigned()->nullable();

            //Formacion
            $table->enum('training_type',config('options.training'))->nullable();
            $table->integer('course_group_id')->unsigned()->nullable();

            //Ausencia
            $table->integer('absence_id')->unsigned()->nullable();

            //General
            $table->tinyInteger('time_slots')->unsigned();
            $table->enum('job_type',config('options.typeOfJob'))->nullable();
            $table->text('comments')->nullable();

            //Validacion
            $table->boolean('pm_validation')->default(false);
            $table->boolean('admin_validation')->default(false);

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade'); 

            $table->foreign('group_id')
                  ->references('id')
                  ->on('groups')
                  ->onDelete('cascade'); 

            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade'); 

            $table->foreign('absence_id')
                  ->references('id')
                  ->on('absences')
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
        
        Schema::dropIfExists('working_report');     
        Schema::dropIfExists('group_user');
        Schema::dropIfExists('groups'); 
        Schema::dropIfExists('projects');
        Schema::dropIfExists('absences');
        Schema::dropIfExists('category_user');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('customers');      
        
    }
}
