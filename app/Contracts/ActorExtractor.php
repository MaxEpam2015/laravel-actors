<?php

namespace App\Contracts;

interface ActorExtractor
{
    public function buildPrompt(string $description): string;

    /**
     * @return array{
     *   first_name: ?string,
     *   last_name: ?string,
     *   address: ?string,
     *   height: ?string,
     *   weight: ?string,
     *   gender: ?string,
     *   age: ?int
     * }
     */
    public function extract(string $description): array;
}
