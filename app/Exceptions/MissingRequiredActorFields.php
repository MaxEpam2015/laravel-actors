<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

class MissingRequiredActorFields extends Exception
{
    /** @var array<string> */
    protected array $missing;

    public function __construct(array $missing = ['first_name','last_name','address'])
    {
        $this->missing = $missing;

        parent::__construct(
            'Please add first name, last name, and address to your description.'
        );
    }


    public function render($request): RedirectResponse
    {
        return back()
            ->withInput()
            ->withErrors([
                'description' => $this->getMessage(),
            ]);
    }
}
