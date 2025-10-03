<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActorApiTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function prompt_date_as_message()
    {
        $this->travelTo(Carbon::parse('2024-01-15 10:00:00'));

        $this->getJson('/api/actors/prompt-validation')
            ->assertOk()
            ->assertExactJson([
                'message' => '2024-01-15',
            ]);
    }
}
