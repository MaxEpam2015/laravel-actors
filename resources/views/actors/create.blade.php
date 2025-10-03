@extends('app')

@section('content')
    <div id="actor-form-app">
        <create-actor-form></create-actor-form>

        <noscript>
            <form method="POST" action="{{ route('actors.store') }}" class="space-y-4 mt-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}" class="mt-1 w-full rounded border p-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium">Actor Description</label>
                    <textarea
                        name="description"
                        rows="5"
                        class="mt-1 w-full rounded border p-2"
                        required
                        placeholder="Example: Brad Pitt, 123 Sunset Blvd, Los Angeles, CA."
                    >{{ old('description') }}</textarea>
                    <p class="mt-2 text-xs text-gray-600">
                        Please enter your first name and last name, and also provide your address.
                    </p>

                </div>

                <button type="submit" class="rounded bg-indigo-600 px-4 py-2 font-semibold text-white">
                    Submit
                </button>
            </form>
        </noscript>
    </div>
@endsection
