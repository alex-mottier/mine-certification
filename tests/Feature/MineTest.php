<?php

namespace Tests\Feature;

use App\Domain\Status\Status;
use App\Models\Mine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MineTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    protected function setUp(): void
    {
        parent::setUp();

        $this->uri = '/api/v1/mines';
        $this->uriWithId = $this->uri . "/{$this->crashMine->id}";
    }
    /**
     * A basic feature test example.
     */
    public function test_someone_can_create_a_mine(): void
    {
        $response = $this->post($this->uri);

        $response->assertStatus(422);

        $wrongBody = [
            'name' => $this->faker->company,
            'email' => $this->faker->companyEmail,
            'phone_number' => $this->faker->phoneNumber,
            'tax_number' => $this->faker->swiftBicNumber,
        ];

        $response = $this->post($this->uri, $wrongBody);
        $response->assertStatus(422);

        $body = array_merge($wrongBody, [
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude
        ]);
        $count = Mine::query()->count();
        $response = $this->post($this->uri, $body);
        $response->assertSuccessful();
        $this->assertDatabaseCount(Mine::class, $count + 1);
    }

    public function test_guest_can_only_list_and_search_validated_mines():void
    {
        Mine::factory(5)->create([
            'status' => Status::VALIDATED
        ]);
        Mine::factory(5)->create([
            'status' => Status::CREATED
        ]);
        Mine::factory(5)->create([
            'status' => Status::FOR_VALIDATION
        ]);
        $countValidatedMines = Mine::query()->where('status', Status::VALIDATED->value)->count();
        $response = $this->get($this->uri);
        $response->assertJsonCount($countValidatedMines, 'mines');

        $name = $this->faker->name;
        $mine = Mine::factory()->create([
            'name' => $name,
            'status' => Status::VALIDATED
        ]);
        $response = $this->get($this->uri . '?name='.$name);
        $response->assertJsonCount(1, 'mines');

        $mine->status = Status::REFUSED;
        $mine->save();
        $response = $this->get($this->uri . '?name='.$name);
        $response->assertJsonCount(0, 'mines');
    }

    public function test_certifier_can_only_list_and_search_validated_and_self_mines(): void
    {
        Mine::factory()->create([
            'status' => Status::VALIDATED
        ]);
        Mine::factory()->create([
            'status' => Status::CREATED
        ]);
        Mine::factory()->create([
            'status' => Status::FOR_VALIDATION
        ]);
        $countValidatedMines = Mine::query()->where('status', Status::VALIDATED->value)->count();
        $response = $this->actingAs($this->certifier)->get($this->uri);
        $response->assertJsonCount($countValidatedMines, 'mines');

        $count_created = 2;
        Mine::factory($count_created)->create([
            'created_by' => $this->certifier->id
        ]);

        $response = $this->actingAs($this->certifier)->get($this->uri);
        $response->assertJsonCount($countValidatedMines + $count_created, 'mines');
    }

    public function test_administrator_can_list_and_search_mines(): void
    {
        Mine::factory()->create([
            'status' => Status::VALIDATED
        ]);
        Mine::factory()->create([
            'status' => Status::CREATED
        ]);
        Mine::factory()->create([
            'status' => Status::FOR_VALIDATION
        ]);
        $count = Mine::query()->count();
        $response = $this->actingAs($this->administrator)->get($this->uri);
        $response->assertJsonCount($count, 'mines');
    }

    public function test_someone_can_view_a_mine(): void
    {
        $mine = Mine::factory()->create([
            'status' => Status::VALIDATED
        ]);

        $response = $this->get($this->uri . '/' . $mine->id);
        $response->assertSuccessful();
        $response->assertJson([
            'mine' => []
        ]);

        $mine = Mine::factory()->create();
        $response = $this->get($this->uri . '/' . $mine->id);
        $response->assertUnauthorized();
    }

    public function test_admin_can_assign_mine(): void
    {
        $mine = Mine::factory()->create();
        $response = $this->actingAs($this->administrator)->post("$this->uri/$mine->id/users");
        $response->assertStatus(422);

        $response = $this->actingAs($this->administrator)->post("$this->uri/$mine->id/users", [
            'certifiers' => [$this->certifier->id]
        ]);
        $response->assertSuccessful();
        $response->assertJson([
            'mine' => [],
            'certifiers' => []
        ]);
    }

    public function test_admin_can_revoke_mine(): void
    {
        $mine = Mine::factory()->create();
        $mine->certifiers()->attach($this->certifier);

        $response = $this->actingAs($this->administrator)->get("$this->uri/$mine->id");
        $response->assertJson([
            'mine' => [],
            'certifiers' => []
        ]);

        $response = $this->actingAs($this->administrator)
            ->delete("$this->uri/$mine->id/users/{$this->certifier->id}");

        $response->assertNoContent();

        $response = $this->actingAs($this->administrator)->get("$this->uri/$mine->id");
        $response->assertJsonMissing([
            'certifiers' => [],
        ]);
    }

    public function test_admin_can_validate_mine_in_status_for_validation(): void
    {
        $mine = Mine::factory()->create([
            'status' => Status::FOR_VALIDATION
        ]);

        $response = $this->actingAs($this->administrator)->patch("$this->uri/$mine->id");
        $response->assertStatus(422);

        $response = $this->actingAs($this->administrator)->patch("$this->uri/$mine->id", [
            'status' => 'created'
        ]);
        $response->assertForbidden();

        $response = $this->actingAs($this->administrator)->patch("$this->uri/$mine->id", [
            'status' => 'validated'
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas(Mine::class, [
            'id' => $mine->id,
            'status' => Status::VALIDATED
        ]);
    }

    public function test_admin_can_refuse_mine_in_status_for_validation(): void
    {
        $mine = Mine::factory()->create([
            'status' => Status::FOR_VALIDATION
        ]);

        $response = $this->actingAs($this->administrator)->patch("$this->uri/$mine->id");
        $response->assertStatus(422);

        $response = $this->actingAs($this->administrator)->patch("$this->uri/$mine->id", [
            'status' => 'created'
        ]);
        $response->assertForbidden();

        $response = $this->actingAs($this->administrator)->patch("$this->uri/$mine->id", [
            'status' => 'refused'
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas(Mine::class, [
            'id' => $mine->id,
            'status' => Status::REFUSED
        ]);
    }

    public function test_admin_cannot_validate_or_refuse_mine_in_status_different_than_for_validation(): void
    {
        $mine = Mine::factory()->create([
            'status' => Status::CREATED
        ]);

        $response = $this->actingAs($this->administrator)->patch("$this->uri/$mine->id");
        $response->assertStatus(422);

        $response = $this->actingAs($this->administrator)->patch("$this->uri/$mine->id", [
            'status' => 'created'
        ]);
        $response->assertForbidden();

        $response = $this->actingAs($this->administrator)->patch("$this->uri/$mine->id", [
            'status' => 'validated'
        ]);
        $response->assertForbidden();

        $response = $this->actingAs($this->administrator)->patch("$this->uri/$mine->id", [
            'status' => 'refused'
        ]);
        $response->assertForbidden();
    }
}
