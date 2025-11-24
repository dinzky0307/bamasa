@extends('layouts.admin')


@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">

    <h1 class="text-3xl font-bold mb-4">All Bookings</h1>

    {{-- Filters --}}
    <form method="GET" class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">

        {{-- Status --}}
        <div>
            <label class="text-xs font-semibold text-gray-600">Status</label>
            <select name="status"
                onchange="this.form.submit()"
                class="w-full border rounded px-3 py-2 text-sm">
                <option value="all" {{ $status=='all'?'selected':'' }}>All</option>
                <option value="pending" {{ $status=='pending'?'selected':'' }}>Pending</option>
                <option value="approved" {{ $status=='approved'?'selected':'' }}>Approved</option>
                <option value="declined" {{ $status=='declined'?'selected':'' }}>Declined</option>
            </select>
        </div>

        {{-- Sort --}}
        <div>
            <label class="text-xs font-semibold text-gray-600">Sort by</label>
            <select name="sort"
                onchange="this.form.submit()"
                class="w-full border rounded px-3 py-2 text-sm">
                <option value="newest" {{ $sort=='newest'?'selected':'' }}>Newest first</option>
                <option value="oldest" {{ $sort=='oldest'?'selected':'' }}>Oldest first</option>
                <option value="checkin_asc" {{ $sort=='checkin_asc'?'selected':'' }}>Check-in (earliest)</option>
                <option value="checkin_desc" {{ $sort=='checkin_desc'?'selected':'' }}>Check-in (latest)</option>
                <option value="name_asc" {{ $sort=='name_asc'?'selected':'' }}>Guest name (A–Z)</option>
                <option value="name_desc" {{ $sort=='name_desc'?'selected':'' }}>Guest name (Z–A)</option>
            </select>
        </div>

        {{-- Keyword search --}}
        <div class="col-span-2">
            <label class="text-xs font-semibold text-gray-600">Search</label>
            <input type="text"
                name="q"
                value="{{ $search }}"
                placeholder="Search guest or business..."
                class="w-full border rounded px-3 py-2 text-sm">
        </div>
    </form>

    {{-- Pagination info --}}
    <p class="text-sm text-gray-600 mb-2">
        Showing
        <span class="font-semibold">{{ $bookings->firstItem() }}</span>
        to
        <span class="font-semibold">{{ $bookings->lastItem() }}</span>
        of
        <span class="font-semibold">{{ $bookings->total() }}</span>
        bookings
    </p>

    <div class="bg-white shadow rounded overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-2">Guest</th>
                    <th class="px-4 py-2">Business</th>
                    <th class="px-4 py-2">Dates</th>
                    <th class="px-4 py-2">Guests</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Created</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @foreach($bookings as $booking)
                    <tr
                        @if(!$booking->check_in || !$booking->check_out)
                            class="bg-yellow-50"
                        @endif
                    >
                        {{-- Guest --}}
                        <td class="px-4 py-2">
                            {{ $booking->user->name ?? 'Guest' }}
                        </td>

                        {{-- Business --}}
                        <td class="px-4 py-2">
                            {{ $booking->business->name ?? 'Business deleted' }}
                        </td>

                        {{-- Dates (with missing highlight) --}}
                        <td class="px-4 py-2">
                            @if($booking->check_in && $booking->check_out)
                                <span class="text-gray-800">
                                    {{ $booking->check_in->format('M d, Y') }}
                                    →
                                    {{ $booking->check_out->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-red-600 font-semibold">
                                    Missing dates
                                </span>
                            @endif
                        </td>

                        {{-- Guests --}}
                        <td class="px-4 py-2">
                            {{ $booking->guests }}
                        </td>

                        {{-- Status badge --}}
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->status === 'approved') bg-green-100 text-green-800
                                @elseif($booking->status === 'declined') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>

                        {{-- Created --}}
                        <td class="px-4 py-2">
                            {{ $booking->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $bookings->links() }}
    </div>

</div>
@endsection
