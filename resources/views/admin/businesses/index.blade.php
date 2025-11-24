@extends('layouts.admin')

@section('content')

<div 
    x-data="businessTable()"
    x-init="loadTable()"
    class="space-y-4"
>

    <h1 class="text-2xl font-bold">Manage Businesses</h1>

    {{-- Search Bar --}}
    <input type="text" placeholder="Search businesses..."
        x-model="search"
        @input.debounce.300ms="loadTable()"
        class="px-4 py-2 border rounded w-full">

    {{-- Table Container --}}
    <div class="bg-white shadow rounded p-4 min-h-[300px]">
        <template x-if="loading">
            <p class="text-gray-500">Loading...</p>
        </template>

        <div x-html="tableHtml"></div>
    </div>

</div>


{{-- Alpine.js --}}
<script src="https://unpkg.com/alpinejs" defer></script>

<script>
function businessTable() {
    return {
        search: '',
        sortBy: 'created_at',
        direction: 'desc',
        loading: false,
        tableHtml: '',

        loadTable() {
            this.loading = true;

            fetch(`/admin/ajax/businesses?search=${this.search}&sortBy=${this.sortBy}&direction=${this.direction}`)
                .then(res => res.text())
                .then(html => {
                    this.tableHtml = html;
                    this.loading = false;
                });
        },

        sort(field) {
            this.sortBy = field;
            this.direction = this.direction === 'asc' ? 'desc' : 'asc';
            this.loadTable();
        }
    }
}
</script>

@endsection
