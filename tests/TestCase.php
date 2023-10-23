<?php

namespace Tests;

use App\Domain\Status\Status;
use App\Domain\Type\UserType;
use App\Models\Mine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;
    use WithFaker;


    protected User $certifier;
    protected User $administrator;
    protected User $crashUser;
    protected Mine $crashMine;

    protected string $uri;
    protected string $uriWithId;

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

        $this->crashMine = Mine::factory()->create();
    }
}
