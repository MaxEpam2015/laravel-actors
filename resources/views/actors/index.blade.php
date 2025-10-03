@extends('app')

@section('content')
    <div class="overflow-x-auto rounded border bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">First Name</th>
                <th class="px-4 py-2 text-left">Address</th>
                <th class="px-4 py-2 text-left">Gender</th>
                <th class="px-4 py-2 text-left">Height</th>
            </tr>
            </thead>
            <tbody>
            @forelse($submissions as $s)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $s->first_name }}</td>
                    <td class="px-4 py-2">{{ $s->address }}</td>
                    <td class="px-4 py-2">{{ $s->gender ?? '—' }}</td>
                    <td class="px-4 py-2">{{ $s->height ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                        No submissions yet.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
