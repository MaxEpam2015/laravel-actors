<?php

namespace App\Services\Ai;

use App\Contracts\ActorExtractor;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

readonly class OpenAiActorExtractor implements ActorExtractor
{
    public function __construct(
        private string $model = 'gpt-4o-mini',
        private ?string $apiKey = null
    ) {}

    public function buildPrompt(string $description): string
    {
        $today = now()->toDateString();

        return <<<TXT
[DATE={$today}]
Extract the following fields from the user's actor description.

REQUIRED:
- first_name (string)
- last_name (string)
- address (string)

OPTIONAL:
- height (string)
- weight (string)
- gender (string)
- age (integer)

Rules:
- Return JSON only, no markdown.
- If an optional field is not present, set it to null.
- Do not invent data.

User description:
{$description}
TXT;
    }

    /**
     * @return array{
     *   first_name:?string,last_name:?string,address:?string,
     *   height:?string,weight:?string,gender:?string,age:?int
     * }
     */
    public function extract(string $description): array
    {
        $prompt = $this->buildPrompt($description);

        $schema = [
            'name'   => 'ActorExtraction',
            'strict' => true,
            'schema' => [
                '$schema' => 'https://json-schema.org/draft/2020-12/schema',
                'type' => 'object',
                'properties' => [
                    'first_name' => ['type' => 'string'],
                    'last_name'  => ['type' => 'string'],
                    'address'    => ['type' => 'string'],
                    'height'     => ['anyOf' => [['type' => 'string'], ['type' => 'null']]],
                    'weight'     => ['anyOf' => [['type' => 'string'], ['type' => 'null']]],
                    'gender'     => ['anyOf' => [['type' => 'string'], ['type' => 'null']]],
                    'age'        => ['anyOf' => [['type' => 'integer'], ['type' => 'null']]],
                ],
                'required' => ['first_name', 'last_name', 'address'],
                'additionalProperties' => false,
            ],
        ];

        $body = [
            'model' => config('services.openai.model', $this->model),
            'messages' => [
                ['role' => 'system', 'content' => 'You return only JSON that matches the provided schema.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => $schema,
            ],
        ];

        $key = $this->apiKey ?? config('services.openai.key');

        try {
            $response = Http::withToken($key)
                ->acceptJson()
                ->post('https://api.openai.com/v1/chat/completions', $body)
                ->throw()
                ->json();

            $json = data_get($response, 'choices.0.message.content', '{}');
        } catch (RequestException $e) {
            // Fallback for models/endpoints that reject json_schema:
            $message = (string) optional($e->response)->body();
            if (str_contains($message, 'Invalid schema for response_format')
                || str_contains($message, 'Unsupported parameter: \'response_format\'')
            ) {
                $fallback = [
                    'model' => config('services.openai.model', $this->model),
                    'messages' => [
                        ['role' => 'system', 'content' => 'Return only a single JSON object with the specified keys. Use null for missing optional fields.'],
                        ['role' => 'user', 'content' => $prompt . PHP_EOL . 'Required keys: first_name, last_name, address. Optional keys: height, weight, gender, age.'],
                    ],
                    'response_format' => ['type' => 'json_object'],
                ];

                $response = Http::withToken($key)
                    ->acceptJson()
                    ->post('https://api.openai.com/v1/chat/completions', $fallback)
                    ->throw()
                    ->json();

                $json = data_get($response, 'choices.0.message.content', '{}');
            } else {
                throw $e;
            }
        }

        $data = json_decode($json, true) ?: [];

        return [
            'first_name' => $data['first_name'] ?? null,
            'last_name'  => $data['last_name'] ?? null,
            'address'    => $data['address'] ?? null,
            'height'     => $data['height'] ?? null,
            'weight'     => $data['weight'] ?? null,
            'gender'     => $data['gender'] ?? null,
            'age'        => isset($data['age']) ? (int) $data['age'] : null,
        ];
    }
}
