@extends('layouts.admin')


@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold mb-4">Users</h1>

    @if($users->isEmpty())
        <p class="text-gray-500">No users found.</p>
    @else
        <div class="bg-white shadow rounded">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Role</th>
                        <th class="px-4 py-2 text-left">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2 uppercase text-xs">{{ $user->role }}</td>
                            <td class="px-4 py-2">{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
