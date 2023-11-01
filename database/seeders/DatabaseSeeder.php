<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Chapter;
use App\Models\Criteria;
use App\Models\CriteriaReport;
use App\Models\Report;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;

class DatabaseSeeder extends Seeder
{
    use WithFaker;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         $admin = User::factory()->create([
             'username' => 'amottier',
             'email' => 'alex_mottier@hotmail.com',
             'type' => 'administrator',
             'status' => 'validated',
             'validated_at' => now()
         ]);

        User::factory()->create([
            'username' => 'test_certifier',
            'email' => 'test_certifier@hotmail.com',
            'type' => 'certifier',
            'status' => 'validated',
            'validated_at' => now(),
            'validated_by' => $admin->id
        ]);

        User::factory()->create([
            'username' => 'test_institution',
            'email' => 'test_institution@hotmail.com',
            'type' => 'institution',
            'status' => 'validated',
            'validated_at' => now(),
            'validated_by' => $admin->id
        ]);

         $chapters = Chapter::factory(5)->create();
         foreach ($chapters as $chapter){
             Criteria::factory(10)->create([
                 'chapter_id' => $chapter->id
             ]);
         }

         $reports = Report::factory(5)->create();

         foreach ($reports as $report){
             $count = $this->faker(Factory::DEFAULT_LOCALE)->numberBetween(1,20);
             for ($i = 0; $i < $count; ++$i){
                 CriteriaReport::factory()->create([
                     'criteria_id' => Criteria::query()->inRandomOrder()->first()->id,
                     'report_id' => $report->id
                 ]);
             }

         }
    }
}
