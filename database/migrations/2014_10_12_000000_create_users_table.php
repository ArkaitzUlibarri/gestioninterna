<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         *  Tabla con los datos de los usuarios (empleados) y su rol en el programa
         */
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('lastname_1');
            $table->string('lastname_2')->nullable();
            //$table->string('DNI',9)->unique();
            $table->enum('role',config('options.roles'));
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        /**
         * Tabla con los tipos de contratos existentes
         */
        Schema::create('contract_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 3)->unique();
            $table->string('name');
            $table->tinyInteger('holidays')->unsigned();   
        });

        /**
         * Tabla con los codigos de los festivos y su region
         */
        Schema::create('bank_holidays_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', config('options.bank_holidays'));
            $table->text('name');
            $table->string('code'); 
        });

        /**
         * Tabla donde se registra el contrato del usuario
         */
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('contract_type_id')->unsigned();
            $table->date('start_date');
            $table->date('estimated_end_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('national_days_id')->unsigned();
            $table->integer('regional_days_id')->unsigned();
            $table->integer('local_days_id')->unsigned();
            $table->tinyInteger('week_hours')->unsigned();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('contract_type_id')
                  ->references('id')
                  ->on('contract_types')
                  ->onDelete('cascade');    

            $table->foreign('national_days_id')
                  ->references('id')
                  ->on('bank_holidays_codes')
                  ->onDelete('cascade'); 

            $table->foreign('regional_days_id')
                  ->references('id')
                  ->on('bank_holidays_codes')
                  ->onDelete('cascade');       

            $table->foreign('local_days_id')
                  ->references('id')
                  ->on('bank_holidays_codes')
                  ->onDelete('cascade');              
        });

        /**
         * Tabla para las vacaciones del usuario
         */
        Schema::create('user_holidays', function (Blueprint $table) {
            $table->integer('contract_id')->unsigned();
            $table->smallInteger('year')->unsigned();           
            $table->tinyInteger('current_year')->unsigned()->default(0);
            $table->tinyInteger('used_current_year')->unsigned()->default(0);
            $table->tinyInteger('last_year')->unsigned()->default(0);
            $table->tinyInteger('used_last_year')->unsigned()->default(0);
            $table->tinyInteger('extras')->unsigned()->default(0);
            $table->tinyInteger('used_extras')->unsigned()->default(0);
               
            $table->foreign('contract_id')
                  ->references('id')
                  ->on('contracts')
                  ->onDelete('cascade'); 

            $table->primary(['contract_id','year']);       
        });

        /**
         * Tabla con los datos de las reducciones de jornada en los contratos de los empleados
         */
        Schema::create('reductions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contract_id')->unsigned();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->tinyInteger('week_hours')->unsigned();

            $table->foreign('contract_id')
                  ->references('id')
                  ->on('contracts')
                  ->onDelete('cascade'); 
        });

        /**
         * Tabla con los datos de teletrabajo en los contratos de los empleados
         */
        Schema::create('teleworking', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contract_id')->unsigned();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('monday')->default(false);
            $table->boolean('tuesday')->default(false);
            $table->boolean('wednesday')->default(false);
            $table->boolean('thursday')->default(false);
            $table->boolean('friday')->default(false);
            $table->boolean('saturday')->default(false);
            $table->boolean('sunday')->default(false);

            $table->foreign('contract_id')
                  ->references('id')
                  ->on('contracts')
                  ->onDelete('cascade'); 
        });

        /**
         * Tabla con el registro de los días de vacaciones solicitados y su validación
         */
        Schema::create('calendar_holidays', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->date('date');
            $table->enum('type', config('options.holidays'));
            $table->text('comments')->nullable();
            $table->boolean('validated');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); 

            $table->primary(['user_id','date']);      
        });

        /**
         * Tabla con los datos de los dias festivos por region geografica
         */
        Schema::create('bank_holidays', function (Blueprint $table) {
            $table->date('date');
            $table->integer('code_id')->unsigned();

            $table->foreign('code_id')
                  ->references('id')
                  ->on('bank_holidays_codes')
                  ->onDelete('cascade'); 
                  
            $table->primary(['date','code_id']);
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_holidays');
        Schema::dropIfExists('calendar_holidays');
        Schema::dropIfExists('teleworking');
        Schema::dropIfExists('reductions');
        Schema::dropIfExists('user_holidays');
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('bank_holidays_codes');
        Schema::dropIfExists('contract_types');
        Schema::dropIfExists('users');
    }
}
