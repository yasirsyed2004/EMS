<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->date('joining_date')->nullable();

            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->
            references('id')->
            on('users')->
            onDelete('set null');

            $table->foreignId('department_id')->nullable();
            $table->foreign('department_id')->
            references('id')->
            on('departments')->
            onDelete('set null');

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
        Schema::dropIfExists('employees');
    }
}
