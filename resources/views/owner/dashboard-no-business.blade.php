@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold mb-4">Business Owner Dashboard</h1>

    <div class="bg-yellow-100 border border-yellow-300 text-yellow-900 px-4 py-3 rounded">
        <p class="font-semibold">No business profile found.</p>
        <p class="mt-2 text-sm">
            Your account is marked as a <strong>Business Owner</strong>, but you do not yet have an associated
            business in the system.
        </p>
        <p class="mt-2 text-sm">
            For now, please contact the system administrator to register or approve your business listing
            in the Bantayan Tourism Portal.
        </p>
    </div>
</div>
@endsection
