@extends('layouts.admin')

@section('title', 'Shipments')

@section('content')
<div class="admin-topbar">
    <h1>Dashboard</h1>
    <a class="btn btn-accent" href="{{ route('admin.shipments.create') }}">+ New Shipment</a>
</div>

<div class="stat-cards">
    <div class="stat-card"><div class="sc-num">{{ $stats['total'] }}</div><div class="sc-lbl">Total Shipments</div></div>
    <div class="stat-card"><div class="sc-num" style="color:var(--blue)">{{ $stats['in_transit'] }}</div><div class="sc-lbl">In Transit</div></div>
    <div class="stat-card"><div class="sc-num" style="color:#d97706">{{ $stats['on_hold'] }}</div><div class="sc-lbl">On Hold</div></div>
    <div class="stat-card"><div class="sc-num" style="color:var(--green)">{{ $stats['delivered'] }}</div><div class="sc-lbl">Delivered</div></div>
    <div class="stat-card"><div class="sc-num">${{ number_format($stats['charges_sum'], 0) }}</div><div class="sc-lbl">Total Charges</div></div>
</div>

<form class="filter-bar" method="GET" action="{{ route('admin.dashboard') }}">
    <input type="text" name="q" placeholder="Search tracking no, consignee, sender, destination…" value="{{ request('q') }}">
    <select name="status">
        <option value="">All statuses</option>
        @foreach ($statusOptions as $opt)
            <option value="{{ $opt }}" {{ request('status') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
        @endforeach
    </select>
    <button class="btn btn-primary btn-sm" type="submit">Filter</button>
    @if (request('q') || request('status'))
        <a class="btn btn-outline btn-sm" href="{{ route('admin.dashboard') }}">Clear</a>
    @endif
</form>

<div class="panel" style="padding:0">
    <table class="table">
        <thead>
            <tr>
                <th>Tracking No</th>
                <th>Consignee</th>
                <th>Destination</th>
                <th>Status</th>
                <th>Date</th>
                <th>Charges</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($shipments as $s)
                <tr>
                    <td class="mono">{{ $s->tracking_no }}</td>
                    <td>{{ $s->consignee_name }}</td>
                    <td>{{ $s->delivery_location }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.shipments.status', $s) }}" class="inline-status">
                            @csrf
                            <input name="status" list="quick-status" value="{{ $s->status }}" class="status-input">
                            <datalist id="quick-status">
                                @foreach ($statusOptions as $opt)<option value="{{ $opt }}">@endforeach
                            </datalist>
                        </form>
                    </td>
                    <td>{{ $s->shipment_date?->format('d/m/Y') }}</td>
                    <td class="mono">${{ number_format($s->totalCharges(), 0) }}</td>
                    <td>
                        <div class="table-actions">
                            <a class="btn btn-outline btn-sm" href="{{ route('track', ['tracking_no' => $s->tracking_no]) }}" target="_blank">View</a>
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.shipments.edit', $s) }}">Edit</a>
                            <form class="inline-form" method="POST" action="{{ route('admin.shipments.duplicate', $s) }}">
                                @csrf
                                <button class="btn btn-outline btn-sm" type="submit" title="Duplicate">Clone</button>
                            </form>
                            <form class="inline-form" method="POST" action="{{ route('admin.shipments.destroy', $s) }}" onsubmit="return confirm('Delete this shipment?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:30px">No shipments found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $shipments->links() }}

<script>
document.querySelectorAll('.status-input').forEach(function (el) {
    el.addEventListener('change', function () { el.closest('form').submit(); });
});
</script>
@endsection
