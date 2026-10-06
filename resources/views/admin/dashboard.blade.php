@extends('layouts.admin')

@section('title', 'Shipments')

@section('content')
<div class="admin-topbar">
    <h1>Shipments</h1>
    <a class="btn btn-accent" href="{{ route('admin.shipments.create') }}">+ New Shipment</a>
</div>

<div class="panel" style="padding:0">
    <table class="table">
        <thead>
            <tr>
                <th>Tracking No</th>
                <th>Consignee</th>
                <th>Destination</th>
                <th>Status</th>
                <th>Date</th>
                <th>Fee</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($shipments as $s)
                <tr>
                    <td class="mono">{{ $s->tracking_no }}</td>
                    <td>{{ $s->consignee_name }}</td>
                    <td>{{ $s->delivery_location }}</td>
                    <td><span class="status-pill">{{ $s->status }}</span></td>
                    <td>{{ $s->shipment_date?->format('d/m/Y') }}</td>
                    <td>${{ number_format($s->totalCharges(), 0) }}</td>
                    <td>
                        <div class="table-actions">
                            <a class="btn btn-outline btn-sm" href="{{ route('track', ['tracking_no' => $s->tracking_no]) }}" target="_blank">View</a>
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.shipments.edit', $s) }}">Edit</a>
                            <form class="inline-form" method="POST" action="{{ route('admin.shipments.destroy', $s) }}" onsubmit="return confirm('Delete this shipment?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:30px">No shipments yet. Create your first one.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $shipments->links() }}
@endsection
