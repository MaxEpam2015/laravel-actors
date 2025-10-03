<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

class ActorController
{
    public function promptValidation(): JsonResponse
    {
        $textPromptDate = now()->toDateString();

        return response()->json([
            'message' => $textPromptDate,
        ]);
    }
}
