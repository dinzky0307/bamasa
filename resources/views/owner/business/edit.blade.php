@extends('layouts.owner')

@section('content')
<div class="max-w-6xl mx-auto py-6 px-4">

    {{-- Page header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">
                Business Overview
            </p>
            <h1 class="text-2xl md:text-3xl font-bold leading-tight">
                {{ $business->name ?? 'Your Business' }}
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                {{ $business->municipality ?? 'Bantayan Island' }} ·
                {{ $business->category ? ucfirst(str_replace('_', ' ', $business->category)) : 'Category not set' }}
            </p>
        </div>

        <div class="flex flex-col items-start md:items-end gap-2 text-xs">
            <div class="inline-flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full border 
                    @if($business->status === 'approved')
                        bg-emerald-50 text-emerald-700 border-emerald-200
                    @elseif($business->status === 'pending')
                        bg-amber-50 text-amber-700 border-amber-200
                    @elseif($business->status === 'rejected')
                        bg-rose-50 text-rose-700 border-rose-200
                    @else
                        bg-slate-50 text-slate-600 border-slate-200
                    @endif
                ">
                    <span class="font-semibold">Status:</span>
                    <span class="ml-1">{{ ucfirst($business->status ?? 'draft') }}</span>
                </span>

                <span class="px-2.5 py-1 rounded-full bg-slate-50 text-slate-600 border border-slate-200">
                    Wizard:
                    <span class="font-semibold ml-1">
                        {{ $business->wizard_completed ? 'Completed' : 'In progress' }}
                    </span>
                </span>
            </div>

            <p class="text-[11px] text-slate-500">
                Last updated: {{ $business->updated_at?->format('M d, Y H:i') ?? 'Not yet updated' }}
            </p>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-4 bg-emerald-50 text-emerald-800 border border-emerald-200 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-rose-50 text-rose-800 border border-rose-200 px-4 py-3 rounded-lg text-sm">
            <p class="font-semibold mb-1">Please fix the following:</p>
            <ul class="list-disc list-inside text-xs">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Layout: main form + side summary --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- MAIN FORM --}}
        <div class="lg:col-span-2 space-y-5">

            <form
                action="{{ route('owner.business.update') }}"
                method="POST"
                class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 space-y-5"
            >
                @csrf
                {{-- If your route expects PUT/PATCH, uncomment: --}}
                {{-- @method('PUT') --}}

                {{-- Section: Basic details --}}
                <div>
                    <h2 class="text-sm font-semibold text-slate-800 mb-1">
                        Basic details
                    </h2>
                    <p class="text-xs text-slate-500 mb-3">
                        This information appears on your public listing.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">
                                Business name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $business->name) }}"
                                   class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                                   required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">
                                Category <span class="text-rose-500">*</span>
                            </label>
                            <select name="category"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                                    required>
                                <option value="">Select a category</option>
                                <option value="resort" @selected(old('category', $business->category) === 'resort')>Resort</option>
                                <option value="homestay" @selected(old('category', $business->category) === 'homestay')>Homestay</option>
                                <option value="restaurant" @selected(old('category', $business->category) === 'restaurant')>Restaurant</option>
                                <option value="tour_operator" @selected(old('category', $business->category) === 'tour_operator')>Tour operator</option>
                                <option value="other" @selected(old('category', $business->category) === 'other')>Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">
                                Municipality <span class="text-rose-500">*</span>
                            </label>
                            <select name="municipality"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                                    required>
                                <option value="">Select</option>
                                <option value="Santa Fe" @selected(old('municipality', $business->municipality) === 'Santa Fe')>Santa Fe</option>
                                <option value="Bantayan" @selected(old('municipality', $business->municipality) === 'Bantayan')>Bantayan</option>
                                <option value="Madridejos" @selected(old('municipality', $business->municipality) === 'Madridejos')>Madridejos</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">
                                Exact address
                            </label>
                            <input type="text"
                                   name="address"
                                   value="{{ old('address', $business->address) }}"
                                   class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">
                            Short description <span class="text-rose-500">*</span>
                        </label>
                        <textarea
                            name="description"
                            rows="4"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                            required
                        >{{ old('description', $business->description) }}</textarea>
                        <p class="text-[11px] text-slate-500 mt-1">
                            Describe what makes your place special (beachfront, near town, family-friendly, etc.).
                        </p>
                    </div>
                </div>

                {{-- Section: Contact & online presence --}}
                <div class="pt-4 border-t border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-800 mb-1">
                        Contact & online presence
                    </h2>
                    <p class="text-xs text-slate-500 mb-3">
                        Make it easy for guests to reach you.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">
                                Contact phone
                            </label>
                            <input type="text"
                                   name="phone"
                                   value="{{ old('phone', $business->phone) }}"
                                   class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">
                                Contact email
                            </label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email', $business->email) }}"
                                   class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">
                                Website
                            </label>
                            <input type="url"
                                   name="website"
                                   value="{{ old('website', $business->website) }}"
                                   placeholder="https://example.com"
                                   class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">
                                Facebook page
                            </label>
                            <input type="url"
                                   name="facebook_page"
                                   value="{{ old('facebook_page', $business->facebook_page) }}"
                                   placeholder="https://facebook.com/your-page"
                                   class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        </div>
                    </div>
                </div>

                {{-- Section: Pricing & optional coordinates --}}
                <div class="pt-4 border-t border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-800 mb-1">
                        Pricing & location details
                    </h2>
                    <p class="text-xs text-slate-500 mb-3">
                        This helps tourists understand your typical rates and approximate location.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex gap-3">
                            <div class="flex-1">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">
                                    Min price per night (₱)
                                </label>
                                <input type="number" min="0"
                                       name="min_price"
                                       value="{{ old('min_price', $business->min_price) }}"
                                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">
                                    Max price per night (₱)
                                </label>
                                <input type="number" min="0"
                                       name="max_price"
                                       value="{{ old('max_price', $business->max_price) }}"
                                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="flex-1">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">
                                    Latitude (optional)
                                </label>
                                <input type="text"
                                       name="latitude"
                                       value="{{ old('latitude', $business->latitude) }}"
                                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">
                                    Longitude (optional)
                                </label>
                                <input type="text"
                                       name="longitude"
                                       value="{{ old('longitude', $business->longitude) }}"
                                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3 text-xs">
                    <p class="text-slate-500">
                        Tip: Review your public listing after saving changes to see how tourists will view your business.
                    </p>
                    <div class="flex gap-2">
                        <a href="{{ route('owner.dashboard') }}"
                           class="px-3 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-4 py-2 rounded-lg bg-sky-600 text-white text-sm font-medium hover:bg-sky-700">
                            Save changes
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- SIDE PANEL: Snapshot & quick links --}}
        <div class="space-y-5">

            {{-- Snapshot --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 text-sm">
                <h2 class="text-sm font-semibold text-slate-800 mb-2">
                    Business snapshot
                </h2>

                <dl class="space-y-2 text-xs text-slate-600">
                    <div class="flex items-center justify-between">
                        <dt>Status</dt>
                        <dd class="font-semibold">
                            {{ ucfirst($business->status ?? 'draft') }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Municipality</dt>
                        <dd class="font-semibold">
                            {{ $business->municipality ?? 'Not set' }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Category</dt>
                        <dd class="font-semibold">
                            {{ $business->category ? ucfirst(str_replace('_', ' ', $business->category)) : 'Not set' }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Wizard</dt>
                        <dd class="font-semibold">
                            {{ $business->wizard_completed ? 'Completed' : 'In progress' }}
                        </dd>
                    </div>
                </dl>

                @if($business->status === 'pending')
                    <div class="mt-3 text-[11px] text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                        Your business has been submitted and is waiting for LGU approval.
                    </div>
                @elseif($business->status === 'rejected')
                    <div class="mt-3 text-[11px] text-rose-700 bg-rose-50 border border-rose-200 rounded-lg px-3 py-2">
                        Your listing was not approved. Please contact your LGU for details or update your profile and resubmit.
                    </div>
                @endif
            </div>

            {{-- Quick links --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 text-sm space-y-3">
                <h2 class="text-sm font-semibold text-slate-800 mb-1">
                    Quick actions
                </h2>

                <div class="flex flex-col gap-2 text-xs">
                    <a href="{{ route('businesses.show', $business->id) }}"
                       target="_blank"
                       class="inline-flex items-center justify-between px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50">
                        <span>View public listing</span>
                        <span class="text-slate-400">↗</span>
                    </a>

                    <a href="{{ route('owner.business.images') }}"
                       class="inline-flex items-center justify-between px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50">
                        <span>Manage gallery photos</span>
                        <span class="text-slate-400">🖼</span>
                    </a>

                    <a href="{{ route('owner.analytics') }}"
                       class="inline-flex items-center justify-between px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50">
                        <span>View analytics & bookings</span>
                        <span class="text-slate-400">📊</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
