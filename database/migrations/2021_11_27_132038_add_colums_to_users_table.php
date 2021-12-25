<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_id')->nullable();
            $table->bigInteger('department_id')->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('nrc_number')->unique()->nullable();
            $table->enum('gender',['male','female'])->nullable();
            $table->date('birthday')->nullable();
            $table->text('address')->nullable();
            $table->date('date_of_joined')->nullable();
            $table->boolean ('is_present')->nullable()->default(true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone','nrc_number','birthday','gender','address','employee_id','department_id','date_of_joined','is_present']);
        });
    }
}
