@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">

    <a href="{{ route('businesses.show', $business->id) }}" class="text-blue-600">&larr; Back to Business</a>

    <h1 class="text-3xl font-bold mt-4">Book: {{ $business->name }}</h1>

    <p class="text-gray-600 mt-2">
        {{ ucfirst($business->category) }} &middot;
        @if($business->min_price)
            From ₱{{ number_format($business->min_price) }} per night
        @endif
    </p>

    @if ($errors->any())
        <div class="mt-4 bg-red-100 text-red-800 p-3 rounded">
            <strong>There were some issues with your booking:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('bookings.store', $business->id) }}"
        method="POST"
        class="mt-6 space-y-4"
        id="booking-form"
    >
        @csrf

        <div>
            <label class="block font-semibold">Check-in Date</label>
            <input type="date" name="check_in" id="check_in"
                   value="{{ old('check_in') }}"
                   class="mt-1 w-full border rounded px-3 py-2"
                   required>
        </div>

        <div>
            <label class="block font-semibold">Check-out Date</label>
            <input type="date" name="check_out" id="check_out"
                   value="{{ old('check_out') }}"
                   class="mt-1 w-full border rounded px-3 py-2"
                   required>
        </div>

        <div>
            <label class="block font-semibold">Number of Guests</label>
            <input type="number" name="guests" id="guests"
                   value="{{ old('guests', 1) }}"
                   min="1"
                   class="mt-1 w-full border rounded px-3 py-2"
                   required>
        </div>

        <div>
            <label class="block font-semibold">Special Requests / Notes</label>
            <textarea name="notes" rows="4"
                      class="mt-1 w-full border rounded px-3 py-2"
                      placeholder="Any special requests or questions?">{{ old('notes') }}</textarea>
        </div>

        {{-- Live price / availability summary --}}
        <div id="availability-box" class="mt-4 p-4 border rounded bg-gray-50 text-sm text-gray-800">
            <p>Select your dates to see availability and estimated total price.</p>
        </div>

        <button type="submit"
                class="mt-4 px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700">
            Submit Booking Request
        </button>
    </form>
</div>

{{-- Simple inline JS (no Vite) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkInInput  = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const guestsInput   = document.getElementById('guests');
    const box           = document.getElementById('availability-box');

    const businessId = {{ $business->id }};
    const availabilityUrl = "{{ route('bookings.availability', $business->id) }}";

    function updateAvailability() {
        const checkIn  = checkInInput.value;
        const checkOut = checkOutInput.value;

        if (!checkIn || !checkOut) {
            box.innerHTML = '<p>Select your dates to see availability and estimated total price.</p>';
            return;
        }

        const params = new URLSearchParams({ check_in: checkIn, check_out: checkOut });

        fetch(availabilityUrl + '?' + params.toString())
            .then(response => response.json())
            .then(data => {
                if (!data.nights || data.nights <= 0) {
                    box.innerHTML = '<p class="text-red-700">Check-out date must be after check-in date.</p>';
                    return;
                }

                if (!data.available) {
                    box.innerHTML = `
                        <p class="text-red-700 font-semibold">These dates are not available.</p>
                        <p>There is already an existing booking overlapping these dates. Please choose another date range.</p>
                    `;
                } else {
                    const guests = parseInt(guestsInput.value || '1', 10);
                    const totalPrice = data.total_price; // already nights * nightly_rate

                    box.innerHTML = `
                        <p><strong>Nights:</strong> ${data.nights}</p>
                        <p><strong>Estimated price:</strong> ₱${totalPrice.toLocaleString()}</p>
                        <p class="text-green-700 mt-2 font-semibold">These dates are available. You may proceed with your booking.</p>
                    `;
                }
            })
            .catch(() => {
                box.innerHTML = '<p class="text-red-700">Unable to check availability at the moment.</p>';
            });
    }

    checkInInput.addEventListener('change', updateAvailability);
    checkOutInput.addEventListener('change', updateAvailability);
    guestsInput.addEventListener('change', updateAvailability);
});
</script>
@endsection
