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
        Schema::create('city', function (Blueprint $table) {
            $table->id();
            $table->decimal("cityxid",  $precision = 18, $scale = 0);
            $table->string("cityname", '100');
            $table->decimal("taxid",  $precision = 18, $scale = 0)->default(0); 
            $table->decimal("taxstate",  $precision = 18, $scale = 0)->default(0);
            $table->double("latitude")->default(-1); 
            $table->double("longitude")->default(-1);
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
        Schema::dropIfExists('city');
    }
};
