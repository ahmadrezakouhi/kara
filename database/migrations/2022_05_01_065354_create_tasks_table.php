<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date_pre')->nullable();
            // $table->tinyInteger('level')->default(0);
            // $table->bigInteger('project_id')->default(0);
            // $table->text('project_title')->nullable();
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            // $table->bigInteger('userid')->default(0);
            // $table->text('username')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0:undone 1:done');
            // $table->dateTime('time_do')->nullable();
            $table->bigInteger('category_id')->default(0);
            $table->text('category_title')->nullable();
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
        Schema::dropIfExists('tasks');
    }
};
