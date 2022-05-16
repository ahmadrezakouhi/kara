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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->string('senderid')->nullable();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('mellicode')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->string('mobile1', '11')->nullable();
            $table->string('mobile2', '11')->nullable();
            $table->bigInteger('user_id');
            // $table->string('statecode','2');
            // $table->string('statename');
            // $table->decimal('citycode', $precision = 18, $scale = 0);
            // $table->string('cityname', '100');
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('sex')->default(0)->comment('0:man 1:woman');
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
        Schema::dropIfExists('personnels');
    }
};
