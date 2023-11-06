<?php

namespace Tests\Feature;

use App\Domain\Status\Status;
use App\Domain\User\UserType;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

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

        $countCertifier = User::query()->where('type', UserType::CERTIFIER)->count();
        $response = $this->actingAs($this->administrator)->get($uri_type);
        $response->assertJsonCount($countCertifier, 'users');

        $countValidated = User::query()->where('status', Status::VALIDATED)->count();
        $response = $this->actingAs($this->administrator)->get($uri_status);
        $response->assertJsonCount($countValidated, 'users');

        $countBoth = User::query()
            ->where('status', Status::REFUSED)
            ->where('type', UserType::ADMINISTRATOR)
            ->count();
        $response = $this->actingAs($this->administrator)->get($uri_both);
        $response->assertJsonCount($countBoth, 'users');
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
        $userCreated = User::query()->where('username', $body['username'])->first();
        $this->assertDatabaseHas(User::class, [
            'id' => $userCreated->id,
            'type' => UserType::ADMINISTRATOR,
            'status' => Status::VALIDATED
        ]);

        $body['type'] = UserType::CERTIFIER->value;
        $response = $this->actingAs($this->administrator)->post($this->uri, $body);
        $response->assertStatus(422);
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
        $count = User::query()->count();
        $this->assertDatabaseCount(User::class, $count);

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
        $count = User::query()->count();
        $this->assertDatabaseCount(User::class, $count);

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
