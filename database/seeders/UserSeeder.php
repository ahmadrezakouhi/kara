<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([[
            'fname' => 'هادی',
            'lname' => 'فروتن',
            'mobile' => '09131020663',
            'phone' => '031395019000',
            'role' => 'manager',
            'email' => 'hadi.foroutan@gmail.com',
            'password' => Hash::make('ta550025'),

        ],[
            'fname' => 'نگار',
            'lname' => 'طیباتی',
            'mobile' => '09133897885',
            'phone' => '031395019000',
            'role' => 'manager',
            'email' => 'tayebati.elham@gmail.com',
            'password' => Hash::make('ta550025'),

        ],[
            'fname' => 'محمد جواد',
            'lname' => 'محرابی',
            'mobile' => '09336223710',
            'phone' => '031395019000',
            'role' => 'admin',
            'email' => 'mj.mehrabi@gmail.com',
            'password' => Hash::make('ta550025'),

        ],[
            'fname' => 'محمد حسین',
            'lname' => 'سلطانی',
            'mobile' => '09223178724',
            'phone' => '031395019000',
            'role' => 'user',
            'email' => 'm.soltani@gmail.com',
            'password' => Hash::make('ta550025'),

        ],[
            'fname' => 'محمد',
            'lname' => 'صفاتاج',
            'mobile' => '091345665471',
            'phone' => '031395019000',
            'role' => 'user',
            'email' => 'm.safataj@gmail.com',
            'password' => Hash::make('ta550025'),

        ],[
            'fname' => 'رضا',
            'lname' => 'براتی',
            'mobile' => '09108429599',
            'phone' => '031395019000',
            'role' => 'user',
            'email' => 'reaz.barati@gmail.com',
            'password' => Hash::make('ta550025'),

        ],[
            'fname' => 'محمد',
            'lname' => 'بابایی',
            'mobile' => '09138289302',
            'phone' => '031395019000',
            'role' => 'user',
            'email' => 'm.babaee@gmail.com',
            'password' => Hash::make('ta550025'),

        ],[
            'fname' => 'سعید',
            'lname' => 'جعفری',
            'mobile' => '09010000292',
            'phone' => '031395019000',
            'role' => 'admin',
            'email' => 's.jafari@gmail.com',
            'password' => Hash::make('ta550025'),

        ],[
            'fname' => 'علی',
            'lname' => 'شیرعلی',
            'mobile' => '09138147436',
            'phone' => '031395019000',
            'role' => 'user',
            'email' => 'ali.shirali@gmail.com',
            'password' => Hash::make('ta550025'),

        ],[
            'fname' => 'مجید',
            'lname' => 'مولایی',
            'mobile' => '09130000150',
            'phone' => '031395019000',
            'role' => 'user',
            'email' => 'm.molaee@gmail.com',
            'password' => Hash::make('ta550025'),

        ],[
            'fname' => 'الهام',
            'lname' => 'ناصری',
            'mobile' => '0936041163',
            'phone' => '031395019000',
            'role' => 'user',
            'email' => 'eli.naseri@gmail.com',
            'password' => Hash::make('ta550025'),

        ],[
            'fname' => 'احمدرضا',
            'lname' => 'کوهی',
            'mobile' => '09130774939',
            'phone' => '031395019000',
            'role' => 'user',
            'email' => 'ahmad.kouhi@gmail.com',
            'password' => Hash::make('ta550025'),

        ]]);
    }
}
