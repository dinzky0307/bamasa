@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-slate-900 mb-4">Edit Announcement</h1>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <form method="POST" action="{{ route('admin.announcements.update', $announcement->id) }}">
            @method('PUT')
            @include('admin.announcements._form', ['announcement' => $announcement])
        </form>
    </div>
</div>
@endsection
