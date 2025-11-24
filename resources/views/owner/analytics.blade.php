@extends('layouts.owner')

@section('content')
<h1 class="text-2xl font-bold mb-6">Analytics Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div class="bg-white p-6 shadow rounded">
        <h2 class="text-lg font-semibold mb-2">Total Bookings</h2>
        <p class="text-4xl font-bold text-sky-600">{{ $bookingsCount }}</p>
    </div>

    <div class="bg-white p-6 shadow rounded">
        <h2 class="text-lg font-semibold mb-2">Bookings per Month</h2>
        <canvas id="bookingsChart"></canvas>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const bookingsData = @json($monthlyBookings);

    new Chart(document.getElementById('bookingsChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(bookingsData),
            datasets: [{
                label: 'Bookings',
                data: Object.values(bookingsData),
            }]
        }
    });
</script>

@endsection
