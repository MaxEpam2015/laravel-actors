<?php

namespace App\Services;

use App\Contracts\ActorExtractor;
use App\Exceptions\MissingRequiredActorFields;
use App\Models\Actor;
use Illuminate\Support\Facades\DB;

readonly class ActorService
{
    public function __construct(private ActorExtractor $ai) {}

    /**
     * @throws MissingRequiredActorFields
     */
    public function submit(string $email, string $description): Actor
    {
        $data = $this->ai->extract($description);

        /** @var array{
         *  first_name:?string,last_name:?string,address:?string,
         *  height:?string,weight:?string,gender:?string,age:?int
         * } $data
         */

        // 2) Guard required extracted fields
        if (
            empty($data['first_name']) ||
            empty($data['last_name'])  ||
            empty($data['address'])
        ) {
            throw new MissingRequiredActorFields(['first_name','last_name','address']);
        }

        return DB::transaction(function () use ($email, $description, $data) {
            return Actor::create([
                'email'       => $email,
                'description' => $description,
                'first_name'  => $data['first_name'],
                'last_name'   => $data['last_name'],
                'address'     => $data['address'],
                'height'      => $data['height'] ?? null,
                'weight'      => $data['weight'] ?? null,
                'gender'      => $data['gender'] ?? null,
                'age'         => $data['age'] ?? null,
            ]);
        });
    }
}
