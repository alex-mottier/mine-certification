<?php

namespace Tests\Feature;

use Tests\TestCase;

class MineTest extends TestCase
{
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
    }
}
