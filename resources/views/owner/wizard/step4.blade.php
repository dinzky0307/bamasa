@extends('layouts.owner')

@section('content')
<h1 class="text-2xl font-bold mb-4">Step 4: Images</h1>

<form action="{{ route('owner.wizard.step4') }}" method="POST" enctype="multipart/form-data"
      class="space-y-4 bg-white p-6 rounded shadow">
    @csrf

    <div>
        <label class="font-semibold block">Thumbnail Image</label>
        <input name="thumbnail" type="file" class="w-full border rounded p-2" required>
    </div>

    <button class="bg-green-600 text-white px-4 py-2 rounded">
        Finish Setup & Submit for Approval
    </button>
</form>
@endsection
