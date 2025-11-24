@extends('layouts.owner')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-4">Business Owner Dashboard</h1>

    {{-- Filters --}}
    <form method="GET" action="{{ route('owner.bookings.index') }}" class="mb-4 flex items-center gap-3">
        <label for="status" class="font-semibold">Filter by status:</label>
        <select name="status" id="status" class="border rounded px-3 py-2"
                onchange="this.form.submit()">
            @php
                $statuses = ['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'declined' => 'Declined', 'cancelled' => 'Cancelled'];
            @endphp

            @foreach($statuses as $value => $label)
                <option value="{{ $value }}" {{ $currentStatus === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </form>

    @if($bookings->isEmpty())
        <p class="text-gray-500">No bookings found for this filter.</p>
    @else
        <div class="bg-white shadow rounded">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Guest</th>
                        <th class="px-4 py-2 text-left">Dates</th>
                        <th class="px-4 py  -2 text-left">Guests</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Created</th>
                        <th class="px-4 py-2 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($bookings as $booking)
                        <tr>
                            <td class="px-4 py-2">
                                {{ $booking->user->name ?? 'Guest' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $booking->check_in->format('M d, Y') }} &rarr;
                                {{ $booking->check_out->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $booking->guests }}
                            </td>
                            <td class="px-4 py-2">
                                <span class="uppercase text-xs px-2 py-1 rounded
                                    @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status === 'approved') bg-green-100 text-green-800
                                    @elseif($booking->status === 'declined') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $booking->status }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                {{ $booking->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-4 py-2 text-right">
                                <a href="{{ route('owner.bookings.show', $booking->id) }}"
                                   class="text-blue-600 text-sm">
                                    View &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection
