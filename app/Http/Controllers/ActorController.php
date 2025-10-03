<?php

namespace App\Http\Controllers;

use App\Exceptions\MissingRequiredActorFields;
use App\Http\Requests\StoreActorRequest;
use App\Models\Actor;
use App\Services\ActorService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ActorController extends Controller
{
    public function __construct(private readonly ActorService $service) {}



    public function create(): array|string|null|View
    {
        return view('actors.create');
    }


    public function index(): array|string|null|View
    {
        $submissions = Actor::latest()->get();

        return view('actors.index', compact('submissions'));
    }

    /**
     * @throws MissingRequiredActorFields
     */
    public function store(StoreActorRequest $request): RedirectResponse
    {
        $this->service->submit($request->email, $request->description);


        return redirect()->route('actors.index');
    }
}
