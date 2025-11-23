@component('mail::message')
# New Booking Request

You have received a new booking request for **{{ $booking->business->name ?? 'your business' }}**.

**Guest:** {{ $booking->user->name ?? 'Unknown' }}  
**Check-in:** {{ $booking->check_in->format('M d, Y') }}  
**Check-out:** {{ $booking->check_out->format('M d, Y') }}  
**Guests:** {{ $booking->guests }}  
**Estimated Total:** ₱{{ number_format($booking->total_price, 2) }}

@if($booking->notes)
**Guest Notes:**
> {{ $booking->notes }}
@endif

Please log in to your dashboard to approve or decline this request (once we build it 😉).

Thanks,<br>
{{ config('app.name') }}
@endcomponent
