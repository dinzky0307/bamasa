@php
    $business = $booking->business;
    $isPast = $isPast ?? false;

    // Status badge styles
    $status = $booking->status ?? 'pending';
    $statusLabel = ucfirst($status);
    $statusClasses = match($status) {
        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'declined', 'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
        'cancelled' => 'bg-slate-100 text-slate-600 border-slate-200',
        default => 'bg-amber-50 text-amber-700 border-amber-200'
    };
@endphp

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 flex gap-3">
    {{-- Thumbnail --}}
    <div class="w-24 h-24 rounded-lg overflow-hidden bg-slate-200 flex-shrink-0">
        @if($business && $business->thumbnail)
            <img src="{{ asset('storage/'.$business->thumbnail) }}"
                 alt="{{ $business->name }}"
                 class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-[11px] text-slate-500">
                No image
            </div>
        @endif
    </div>

    {{-- Details --}}
    <div class="flex-1 flex flex-col">
        <div class="flex items-start justify-between gap-2">
            <div>
                <h3 class="text-sm font-semibold text-slate-900 leading-snug">
                    @if($business)
                        <a href="{{ route('businesses.show', $business->id) }}" class="hover:text-sky-700">
                            {{ $business->name }}
                        </a>
                    @else
                        <span>Business no longer available</span>
                    @endif
                </h3>
                <p class="text-[11px] text-slate-500">
                    {{ $business->municipality ?? 'Bantayan Island' }}
                    @if($business && $business->category)
                        · {{ ucfirst(str_replace('_', ' ', $business->category)) }}
                    @endif
                </p>
            </div>

            <span class="px-2 py-0.5 rounded-full border text-[11px] {{ $statusClasses }}">
                {{ $statusLabel }}
            </span>
        </div>

        <div class="mt-2 text-xs text-slate-700 space-y-1">
            @if($booking->check_in && $booking->check_out)
                <p>
                    <span class="font-semibold">Dates:</span>
                    <span class="ml-1">
                        {{ $booking->check_in->format('M d, Y') }}
                        –
                        {{ $booking->check_out->format('M d, Y') }}
                    </span>
                </p>
            @endif
            @if($booking->guests)
                <p>
                    <span class="font-semibold">Guests:</span>
                    <span class="ml-1">{{ $booking->guests }}</span>
                </p>
            @endif
            @if($booking->total_price)
                <p>
                    <span class="font-semibold">Estimated total:</span>
                    <span class="ml-1">₱{{ number_format($booking->total_price) }}</span>
                    <span class="ml-1 text-[10px] text-slate-400">(subject to confirmation)</span>
                </p>
            @endif
        </div>

        {{-- Actions --}}
        <div class="mt-3 flex items-center justify-between text-[11px] text-slate-500">
            <div>
                @if($booking->created_at)
                    Booked on {{ $booking->created_at->format('M d, Y') }}
                @endif
            </div>

            {{-- Optional: cancellation for upcoming pending bookings --}}
            @if(!$isPast && $status === 'pending')
                {{-- If you later add a cancel route, hook it up here --}}
                {{-- <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST"
                      onsubmit="return confirm('Cancel this booking request?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="px-3 py-1 rounded-full border border-slate-300 text-slate-700 hover:bg-slate-50">
                        Cancel request
                    </button>
                </form> --}}
            @endif
        </div>
    </div>
</div>
