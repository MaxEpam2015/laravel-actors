<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\ActorExtractor;
use App\Services\Ai\OpenAiActorExtractor;

class AiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ActorExtractor::class, function ($app) {
            $driver = config('ai.driver');

            return match ($driver) {
                // 'another_ai' => ..
                default => new OpenAiActorExtractor(
                    model: config('services.openai.model'),
                    apiKey: config('services.openai.key'),
                ),
            };
        });
    }
}
