@csrf

<div class="space-y-4">
    <div>
        <label class="block text-xs font-semibold text-slate-600 mb-1">Title</label>
        <input type="text" name="title"
               value="{{ old('title', $announcement->title ?? '') }}"
               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
        @error('title') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 mb-1">Scope / Audience</label>
        <select name="municipality_scope"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            <option value="">Island-wide (default)</option>
            @php
                $scope = old('municipality_scope', $announcement->municipality_scope ?? '');
            @endphp
            <option value="Bantayan"   {{ $scope === 'Bantayan' ? 'selected' : '' }}>Bantayan</option>
            <option value="Santa Fe"   {{ $scope === 'Santa Fe' ? 'selected' : '' }}>Santa Fe</option>
            <option value="Madridejos" {{ $scope === 'Madridejos' ? 'selected' : '' }}>Madridejos</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
        @php
            $statusValue = old('status', $announcement->status ?? 'published');
        @endphp
        <select name="status"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            <option value="draft" {{ $statusValue === 'draft' ? 'selected' : '' }}>Draft (not visible)</option>
            <option value="published" {{ $statusValue === 'published' ? 'selected' : '' }}>Published (visible)</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 mb-1">Content</label>
        <textarea name="body" rows="8"
                  class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">{{ old('body', $announcement->body ?? '') }}</textarea>
        @error('body') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-2">
    <a href="{{ route('admin.announcements.index') }}"
       class="px-3 py-1.5 text-xs rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50">
        Cancel
    </a>

    <div>
    <label class="block text-xs font-semibold text-slate-600 mb-1">Thumbnail Image (optional)</label>
    <input type="file" name="image"
           accept="image/*"
           class="w-full border rounded-lg px-3 py-2 text-sm bg-white">
    @error('image') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror

    @if(!empty($announcement->image_path))
        <p class="text-[11px] text-slate-500 mt-2">Current image:</p>
        <img src="{{ asset('storage/'.$announcement->image_path) }}"
             alt="Current thumbnail"
             class="mt-1 h-24 rounded-lg object-cover border border-slate-200">
    @endif
</div>


    <button class="px-4 py-1.5 text-xs rounded-lg bg-sky-600 text-white hover:bg-sky-700">
        Save
    </button>
</div>
