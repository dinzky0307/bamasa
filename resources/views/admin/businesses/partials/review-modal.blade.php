<div id="review-modal-{{ $business->id }}" tabindex="-1" aria-hidden="true"
     class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-screen bg-black/50">

    <div class="relative p-4 w-full max-w-2xl">
        <div class="relative bg-white rounded-lg shadow">

            {{-- Modal header --}}
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-xl font-semibold">
                    Review Business: {{ $business->name }}
                </h3>
                <button type="button"
                        class="text-gray-400 hover:text-gray-900"
                        data-modal-hide="review-modal-{{ $business->id }}">
                    ✕
                </button>
            </div>

            {{-- Modal body --}}
            <div class="p-4 space-y-3">
                <p><strong>Owner:</strong> {{ $business->user->name }}</p>
                <p><strong>Category:</strong> {{ ucfirst($business->category) }}</p>
                <p><strong>Description:</strong> {{ $business->description }}</p>
                <p><strong>Municipality:</strong> {{ $business->municipality }}</p>
                <p><strong>Price Range:</strong> ₱{{ $business->min_price }} – ₱{{ $business->max_price }}</p>
                <p><strong>Contact:</strong> {{ $business->email }} | {{ $business->phone }}</p>

                @if($business->thumbnail)
                    <img src="{{ asset('storage/'.$business->thumbnail) }}"
                         class="rounded shadow w-48" alt="Thumbnail">
                @endif
            </div>

            {{-- Modal footer --}}
            <div class="flex justify-end p-4 border-t space-x-3">

                {{-- Reject --}}
<form action="{{ route('admin.businesses.reject', $business->id) }}" method="POST">
    @csrf
    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">
        Reject
    </button>
</form>

{{-- Approve --}}
<form action="{{ route('admin.businesses.approve', $business->id) }}" method="POST">
    @csrf
    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">
        Approve
    </button>
</form>


            </div>

        </div>
    </div>

</div>
