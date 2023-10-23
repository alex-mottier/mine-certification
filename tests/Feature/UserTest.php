<?php

namespace Tests\Feature;

use App\Domain\Status\Status;
use App\Domain\Type\UserType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private User $certifier;
    private User $administrator;
    private User $crashUser;

    private string $uri;
    private string $uriWithId;
    protected function setUp(): void
    {
        parent::setUp();

        $this->certifier = User::factory()->create([
            'status' => Status::VALIDATED->value,
            'type' => UserType::CERTIFIER->value
        ]);

        $this->administrator = User::factory()->create([
            'status' => Status::VALIDATED->value,
            'type' => UserType::ADMINISTRATOR->value
        ]);

        $this->crashUser = User::factory()->create([
            'status' => Status::CREATED->value,
            'type' => UserType::CERTIFIER->value
        ]);

        $this->uri = '/api/v1/users';
        $this->uriWithId = $this->uri . "/{$this->crashUser->id}";
    }

    public function test_only_admin_can_list_users(): void
    {
        $response = $this->get($this->uri);
        $response->assertRedirect('/login');

        $response = $this->actingAs($this->certifier)->get($this->uri);
        $response->assertUnauthorized();

        $response = $this->actingAs($this->administrator)->get($this->uri);
        $response->assertSuccessful();
    }

    public function test_admin_can_search_users(): void
    {
        $uri_type = $this->uri . '?type=certifier';
        $uri_status = $this->uri . '?status=validated';
        $uri_both = $this->uri . '?type=administrator&status=refused';

        $response = $this->actingAs($this->administrator)->get($uri_type);
        $response->assertJsonCount(2, 'users');

        $response = $this->actingAs($this->administrator)->get($uri_status);
        $response->assertJsonCount(2, 'users');

        $response = $this->actingAs($this->administrator)->get($uri_both);
        $response->assertJsonCount(0, 'users');
    }

    public function test_guest_can_submit_a_user_creation_request_to_be_certifier(): void
    {
        $response = $this->post($this->uri);
        $response->assertStatus(422);

        $body = [
            'username' => $this->faker->userName(),
            'fullname' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password(),
            'type' => UserType::ADMINISTRATOR->value
        ];
        $response = $this->post($this->uri, $body);
        $response->assertUnauthorized();

        $body['type'] = UserType::CERTIFIER->value;
        $response = $this->post($this->uri, $body);
        $response->assertSuccessful();
        $this->assertDatabaseHas(User::class, [
            'id' => 4,
            'status' => Status::CREATED
        ]);
    }

    public function test_admin_can_create_user_with_all_type_and_validation(): void
    {
        $body = [
            'username' => $this->faker->userName(),
            'fullname' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password(),
            'type' => UserType::ADMINISTRATOR->value
        ];
        $response = $this->actingAs($this->administrator)->post($this->uri, $body);
        $response->assertSuccessful();
        $this->assertDatabaseHas(User::class, [
            'id' => 4,
            'type' => UserType::ADMINISTRATOR,
            'status' => Status::VALIDATED
        ]);

        $body['type'] = UserType::CERTIFIER->value;
        $response = $this->actingAs($this->administrator)->post($this->uri, $body);
        $response->assertStatus(422);
        $this->assertDatabaseCount(User::class, 4);

        $body = [
            'username' => $this->faker->userName(),
            'fullname' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password(),
            'type' => UserType::CERTIFIER->value
        ];
        $response = $this->actingAs($this->administrator)->post($this->uri, $body);
        $response->assertSuccessful();
        $this->assertDatabaseHas(User::class, [
            'id' => 5,
            'type' => UserType::CERTIFIER,
            'status' => Status::VALIDATED
        ]);
    }

    public function test_only_admin_can_validate_user(): void
    {
        $this->assertDatabaseHas(User::class, [
            'id' => $this->crashUser->id,
            'status' => Status::CREATED
        ]);

        $response = $this->patch($this->uriWithId);
        $response->assertRedirect('/login');

        $response = $this->actingAs($this->certifier)->patch($this->uriWithId);
        $response->assertUnauthorized();

        $response = $this->actingAs($this->administrator)->patch($this->uriWithId);
        $response->assertStatus(422);

        $body = [
            'status' => Status::VALIDATED->value
        ];
        $response = $this->actingAs($this->administrator)->patch($this->uriWithId, $body);
        $response->assertSuccessful();
        $this->assertDatabaseHas(User::class, [
            'id' => $this->crashUser->id,
            'status' => Status::VALIDATED
        ]);
    }

    public function test_only_admin_can_delete_user(): void
    {
        $this->assertDatabaseCount(User::class, 3);

        $response = $this->delete($this->uriWithId);
        $response->assertRedirect('/login');

        $response2 = $this->actingAs($this->certifier)->delete($this->uriWithId);
        $response2->assertUnauthorized();
    }

    public function test_admin_can_delete_user(): void
    {
        $response = $this->actingAs($this->administrator)->delete($this->uriWithId);
        $response->assertNoContent();
        $this->assertSoftDeleted(User::class, [
            'id' => $this->crashUser->id
        ]);
    }

    public function test_only_admin_can_update_user(): void
    {
        $this->assertDatabaseCount(User::class, 3);

        $response = $this->put($this->uriWithId);
        $response->assertRedirect('/login');

        $response2 = $this->actingAs($this->certifier)->put($this->uriWithId);
        $response2->assertUnauthorized();
    }

    public function test_admin_can_update_user(): void
    {
        $this->assertDatabaseHas(User::class, [
            'id' =>  $this->crashUser->id,
            'type' => $this->crashUser->type
        ]);
        $response = $this->actingAs($this->administrator)->put($this->uriWithId);
        $response->assertSuccessful();
        $this->assertDatabaseHas(User::class, [
            'id' =>  $this->crashUser->id,
            'type' => UserType::CERTIFIER
        ]);

        $body = [
            'type' => UserType::ADMINISTRATOR->value
        ];
        $response = $this->actingAs($this->administrator)->put($this->uriWithId, $body);
        $response->assertSuccessful();
        $this->assertDatabaseHas(User::class, [
            'id' =>  $this->crashUser->id,
            'type' => UserType::ADMINISTRATOR
        ]);

    }
}
