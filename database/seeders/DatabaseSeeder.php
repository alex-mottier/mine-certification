<?php

namespace Database\Seeders;

use App\Domain\Report\ReportType;
use App\Domain\Status\Status;
use App\Domain\User\UserType;
use App\Models\Chapter;
use App\Models\Criteria;
use App\Models\CriteriaReport;
use App\Models\Institution;
use App\Models\Mine;
use App\Models\MineUser;
use App\Models\Report;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Random\Randomizer;

class DatabaseSeeder extends Seeder
{
    use WithFaker, RefreshDatabase;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        /**
         * Creation of the four possible types
         */
        $admin = User::factory()->create([
            'username' => 'amottier',
            'email' => 'alex_mottier@hotmail.com',
            'type' => 'administrator',
            'status' => 'validated',
            'validated_at' => now()
        ]);

        $certifier = User::factory()->create([
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

        User::factory()->create([
            'username' => 'test_owner',
            'email' => 'test_owner@hotmail.com',
            'type' => 'owner',
            'status' => 'validated',
            'validated_at' => now(),
            'validated_by' => $admin->id
        ]);

        /**
         * Creation of the base chapter/criteria
         */
        $chapters = Chapter::factory(5)->create();
        foreach ($chapters as $chapter){
            Criteria::factory(10)->create([
                'chapter_id' => $chapter->id
            ]);
        }

        /**
         * Creation of the evaluation
         */

        $reports = Report::factory(5)->create([
            'type' => ReportType::EVALUATION,
            'status' => Status::VALIDATED
        ]);

        foreach ($reports as $report){
            $count = $this->faker(Factory::DEFAULT_LOCALE)->numberBetween(1,20);
            $score = 0;
            for ($i = 0; $i < $count; ++$i){
                $criteriaReport = CriteriaReport::factory()->create([
                    'criteria_id' => Criteria::query()->inRandomOrder()->first()->id,
                    'report_id' => $report->id
                ]);
                $score += $criteriaReport->score;
            }
            $report->score = $score / $report->criteriaReports()->count();
            $report->mine->score = $report->score;
            $report->mine->save();
            $report->save();
        }

        $mines = Mine::query()->get();
        foreach ($mines as $mine) {
            MineUser::query()->create([
                'user_id' => $certifier->id,
                'mine_id' => $mine->id
            ]);
        }

        Mine::factory(10)->create();

        $institutionUsers = User::factory(10)->create([
            'type' => UserType::INSTITUTION
        ]);
        $institutions = Institution::factory(5)->create();
        foreach ($institutions->whereIn('status', [Status::VALIDATED, Status::FOR_VALIDATION]) as $institution){
            $users = $institutionUsers
                ->where('status', Status::VALIDATED)
                ->random(
                    (new Randomizer)
                        ->getInt(1,$institutionUsers
                            ->where('status', Status::VALIDATED)
                            ->count()
                        )
                );
            /**
             * @var Institution $institution
             */
            $institution->users()->attach($users);
        }

        foreach(Mine::query()->where('status', Status::VALIDATED)->get() as $mine){
            $owners = User::factory((new Randomizer)->getInt(1,5))->create([
                'type' => UserType::OWNER,
                'validated_by' => $admin,
                'validated_at' => now(),
                'status' => Status::VALIDATED
            ]);

            $certifiers = User::factory((new Randomizer)->getInt(1,5))->create([
                'type' => UserType::CERTIFIER,
                'validated_by' => $admin,
                'validated_at' => now(),
                'status' => Status::VALIDATED
            ]);

            foreach ($owners->merge($certifiers) as $user) {
                MineUser::query()->create([
                    'user_id' => $user->id,
                    'mine_id' => $mine->id
                ]);
            }
        }
    }
}
