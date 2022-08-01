<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ybazli\Faker\Facades\Faker;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Task::create([
            'user_id'=>12,
            'sprint_id'=>2,
            'category_id'=>1,
            'title'=>Faker::sentence(),
            'description'=>Faker::paragraph(),
            'duration'=>100
        ]);
    }
}
