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
        Schema::create('state', function (Blueprint $table) {
            $table->id();
            $table->decimal('statecode', $precision = 18, $scale = 0);
            $table->string('statename', '50');
        });

         // Insert some stuff
        \DB::table('state')->insert(
           [
            ['id' => 1,'statecode' =>'11', 'statename' =>  'تهران'],
            ['id' =>2, 'statecode' => '14', 'statename' => 'قم'],
            ['id' =>3, 'statecode' =>'15', 'statename' => 'قزوین'],
            ['id' =>4, 'statecode' =>'16', 'statename' => 'مازندران'],
            ['id' =>5, 'statecode' =>'18', 'statename' => 'البرز'],
            ['id' =>6, 'statecode' =>'21', 'statename' => 'اصفهان'],
            ['id' =>7, 'statecode' =>'26', 'statename' => 'آذربایجان شرقی'],
            ['id' =>8, 'statecode' =>'31', 'statename' => 'خراسان رضوی'],
            ['id' =>9, 'statecode' =>'32', 'statename' => 'خراسان شمالی'],
            ['id' =>10, 'statecode' =>'33', 'statename' => 'خراسان جنوبی'],
            ['id' =>11, 'statecode' =>'36', 'statename' => 'خوزستان'],
            ['id' =>12, 'statecode' =>'41', 'statename' => 'فارس'],
            ['id' =>13, 'statecode' =>'45', 'statename' => 'کرمان'],
            ['id' =>14, 'statecode' =>'51', 'statename' => 'مرکزی'],
            ['id' =>15, 'statecode' =>'54', 'statename' => 'گیلان'],
            ['id' =>16, 'statecode' =>'57', 'statename' => 'آذربایجان غربی'],
            ['id' =>17, 'statecode' =>'61', 'statename' => 'سیستان و بلوچستان'],
            ['id' =>18, 'statecode' =>'64', 'statename' => 'هرمزگان'],
            ['id' =>19, 'statecode' =>'67', 'statename' => 'زنجان'],
            ['id' =>20, 'statecode' =>'71', 'statename' => 'کرمانشاه'],
            ['id' =>21, 'statecode' =>'73', 'statename' => 'کردستان'],
            ['id' =>22, 'statecode' =>'75', 'statename' => 'همدان'],
            ['id' =>23, 'statecode' =>'77', 'statename' => 'چهارمحال و بختیارى'],
            ['id' =>24, 'statecode' =>'81', 'statename' => 'لرستان'],
            ['id' =>25, 'statecode' =>'83', 'statename' => 'ایلام'],
            ['id' =>26, 'statecode' =>'85', 'statename' => 'کهگیلویه و بویراحمد'],
            ['id' =>27, 'statecode' =>'87', 'statename' => 'سمنان'],
            ['id' =>28, 'statecode' =>'91', 'statename' => 'اردبیل'],
            ['id' =>29, 'statecode' =>'93', 'statename' => 'یزد'],
            ['id' =>30, 'statecode' =>'95', 'statename' => 'بوشهر'],
            ['id' =>31, 'statecode' =>'97', 'statename' => 'گلستان']
           ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('state');
    }
};
