<?php

namespace Tests\Feature;

use App\Contracts\ActorExtractor;
use App\Models\Actor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActorsTest extends TestCase
{
    use RefreshDatabase;

    private function bindOkFakeExtractor(): void
    {
        $this->app->bind(ActorExtractor::class, function () {
            return new class implements ActorExtractor {
                public function buildPrompt(string $description): string
                {
                    return "FAKE_PROMPT: {$description}";
                }
                public function extract(string $description): array
                {
                    return [
                        'first_name' => 'John',
                        'last_name'  => 'Doe',
                        'address'    => '123 Main St, Metropolis',
                        'height'     => '180 cm',
                        'weight'     => '75 kg',
                        'gender'     => 'M',
                        'age'        => 30,
                    ];
                }
            };
        });
    }

    private function bindFailingFakeExtractor(): void
    {
        $this->app->bind(ActorExtractor::class, function () {
            return new class implements ActorExtractor {
                public function buildPrompt(string $description): string
                {
                    return "FAKE_PROMPT_MISSING: {$description}";
                }
                public function extract(string $description): array
                {
                    return [
                        'first_name' => null,
                        'last_name'  => null,
                        'address'    => null,
                        'height'     => null,
                        'weight'     => null,
                        'gender'     => null,
                        'age'        => null,
                    ];
                }
            };
        });
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'email' => 'john@example.com',
            'description' => 'Tall male actor named John Doe living at 123 Main St.',
        ], $overrides);
    }

    /** @test */
    public function form_page_renders()
    {
        $this->get('/')->assertOk()->assertSee('Actor Submitter');
    }

    /** @test */
    public function posting_without_required_fields_fails_validation()
    {
        $this->post('/submit', [])->assertSessionHasErrors(['email', 'description']);
    }

    /** @test */
    public function extractor_missing_required_fields_returns_custom_error()
    {
        $this->bindFailingFakeExtractor();

        $resp = $this->post('/submit', $this->validPayload());

        $resp->assertSessionHasErrors([
            'description' => 'Please add first name, last name, and address to your description.',
        ]);
    }

    /** @test */
    public function successful_submission_persists_and_redirects()
    {
        $this->bindOkFakeExtractor();

        $resp = $this->post('/submit', $this->validPayload());

        $resp->assertRedirect('/submissions');

        $this->assertDatabaseHas('actors', [
            'email' => 'john@example.com',
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'address'    => '123 Main St, Metropolis',
        ]);
    }

    /** @test */
    public function email_and_description_must_be_unique()
    {
        Actor::factory()->create([
            'email'       => 'taken@example.com',
            'description' => 'Existing actor description',
            'first_name'  => 'Jane',
            'last_name'   => 'Doe',
            'address'     => '456 Broadway',
        ]);

        $this->bindOkFakeExtractor();

        $response1 = $this->post('/submit', $this->validPayload([
            'email' => 'taken@example.com',
            'description' => 'Brand new description',
        ]));
        $response1->assertSessionHasErrors(['email']);

        $response2 = $this->post('/submit', $this->validPayload([
            'email' => 'unique@example.com',
            'description' => 'Existing actor description',
        ]));
        $response2->assertSessionHasErrors(['description']);
    }

    /** @test */
    public function index_page_lists_submissions()
    {
        Actor::factory()->create(['first_name' => 'Alice', 'address' => '1 Street',  'gender' => 'F', 'height' => '170 cm']);
        Actor::factory()->create(['first_name' => 'Bob',   'address' => '2 Avenue',  'gender' => 'M', 'height' => '180 cm']);

        $this->get('/submissions')
            ->assertOk()
            ->assertSee('Alice')
            ->assertSee('1 Street')
            ->assertSee('Bob')
            ->assertSee('2 Avenue');
    }
}
