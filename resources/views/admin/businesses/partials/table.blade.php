<table class="min-w-full text-sm">
    <thead class="bg-gray-100">
        <tr>
            <th class="px-4 py-2 cursor-pointer" @click="sort('name')">Name</th>
            <th class="px-4 py-2">Owner</th>
            <th class="px-4 py-2 cursor-pointer" @click="sort('municipality')">Municipality</th>
            <th class="px-4 py-2 cursor-pointer" @click="sort('status')">Status</th>
            <th class="px-4 py-2 cursor-pointer" @click="sort('created_at')">Created</th>
            <th class="px-4 py-2 text-right">Actions</th>
        </tr>
    </thead>

    <tbody class="divide-y">
        @foreach($businesses as $biz)
            <tr>
                <td class="px-4 py-2">{{ $biz->name }}</td>
                <td class="px-4 py-2">
                    {{ $biz->user->name ?? 'N/A' }} <br>
                    <span class="text-xs text-gray-500">{{ $biz->user->email ?? '' }}</span>
                </td>
                <td class="px-4 py-2">{{ $biz->municipality }}</td>
                <td class="px-4 py-2">
    <span class="uppercase text-xs px-2 py-1 rounded
        @if($biz->status === 'pending') bg-yellow-100 text-yellow-800
        @elseif($biz->status === 'approved') bg-green-100 text-green-800
        @elseif($biz->status === 'rejected') bg-red-100 text-red-800
        @else bg-gray-100 text-gray-800
        @endif">
        {{ $biz->status }}
    </span>
</td>

                <td class="px-4 py-2">{{ $biz->created_at->format('M d, Y') }}</td>
                <td class="px-4 py-2 text-right">
    <button
        type="button"
        data-modal-target="review-modal-{{ $biz->id }}"
        data-modal-toggle="review-modal-{{ $biz->id }}"
        class="px-3 py-1 bg-blue-600 text-white rounded text-sm">
        Review
    </button>
</td>

                </td>
            </tr>

            @include('admin.businesses.partials.review-modal', ['business' => $biz])
        @endforeach
    </tbody>
</table>

<div class="mt-3">
    {{ $businesses->links() }}
</div>
