<?php

namespace Tests\Feature;

use App\Domain\Type\ReportType;
use App\Models\Criteria;
use App\Models\Mine;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    protected function setUp(): void
    {
        parent::setUp();

        $this->uri = '/api/v1/reports';
    }

    public function test_only_owner_certifier_or_administrator_can_list_reports(): void
    {
        $response = $this->get($this->uri);
        $response->assertRedirect();

        $response = $this->actingAs($this->administrator)->get($this->uri);
        $response->assertSuccessful();

        $count = Report::query()->count();
        $response->assertJsonCount($count, 'reports');
    }

    public function test_everybody_can_create_a_report(): void
    {
        $count = Report::query()->count();
        $mine = Mine::factory()->create();
        $criteria1 = Criteria::factory()->create();
        $criteria2 = Criteria::factory()->create();
        $response = $this->post($this->uri, [
            'name' => $this->faker->text(20),
            'mine_id' => $mine->id,
            'type' => ReportType::REPORT->value,
            'criterias' => [
                [
                    'criteria_id' => $criteria1->id,
                    'comment' => $this->faker->text,
                    'score' => $this->faker->numberBetween(0.1, 10)
                ],
                [
                    'criteria_id' => $criteria2->id,
                    'comment' => $this->faker->text,
                    'score' => $this->faker->numberBetween(0.1, 10)
                ],
            ]
        ]);
        $response->assertSuccessful();
        $this->assertDatabaseCount(Report::class, $count + 1);
    }

//    public function test_only_certifiers_can_create_an_evaluation(): void
//    {
//        $count = Report::query()->count();
//        $mine = Mine::factory()->create();
//        $criteria1 = Criteria::factory()->create();
//        $criteria2 = Criteria::factory()->create();
//
//        MineUser::query()->create([
//            'mine_id' => $mine->id,
//            'certifier_id' => $this->certifier->id
//        ]);
//
//        $body = [
//            'name' => $this->faker->text(20),
//            'mine_id' => $mine->id,
//            'type' => ReportType::EVALUATION->value,
//            'criterias' => [
//                [
//                    'criteria_id' => $criteria1->id,
//                    'comment' => $this->faker->text,
//                    'score' => $this->faker->numberBetween(0.1, 10)
//                ],
//                [
//                    'criteria_id' => $criteria2->id,
//                    'comment' => $this->faker->text,
//                    'score' => $this->faker->numberBetween(0.1, 10)
//                ],
//            ]
//        ];
//        $response = $this->post($this->uri, $body);
//        $response->assertUnauthorized();
//        $this->assertDatabaseCount(Report::class, $count);
//
//
//        $response = $this->actingAs($this->certifier)->post($this->uri, $body);
//        $response->assertSuccessful();
//        $response->assertJsonCount(1, 'report');
//        $this->assertDatabaseCount(Report::class, $count + 1);
//    }


}
