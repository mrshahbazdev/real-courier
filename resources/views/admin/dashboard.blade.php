@extends('layouts.admin')

@section('title', 'Shipments')

@section('content')
<div class="admin-topbar">
    <h1>Dashboard</h1>
    <div style="display:flex;gap:10px">
        <a class="btn btn-outline" href="{{ route('admin.shipments.export') }}">Export CSV</a>
        <a class="btn btn-accent" href="{{ route('admin.shipments.create') }}">+ New Shipment</a>
    </div>
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

<div class="bulk-bar" id="bulkBar" style="display:none">
    <form method="POST" action="{{ route('admin.shipments.bulk') }}" id="bulkForm" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
        @csrf
        <span id="bulkCount" style="font-weight:700;font-size:13px"></span>
        <input name="action" type="hidden" id="bulkAction" value="status">
        <input name="status" list="quick-status" placeholder="Set status…" class="bulk-status-input" style="min-width:180px">
        <button class="btn btn-primary btn-sm" type="submit" onclick="document.getElementById('bulkAction').value='status'">Apply Status</button>
        <button class="btn btn-danger btn-sm" type="submit" onclick="if(!confirm('Delete selected shipments?')){event.preventDefault();return;} document.getElementById('bulkAction').value='delete'">Delete Selected</button>
    </form>
</div>

<div class="panel" style="padding:0">
    <table class="table">
        <thead>
            <tr>
                <th><input type="checkbox" id="selAll"></th>
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
                    <td><input type="checkbox" class="sel-row" value="{{ $s->id }}"></td>
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
                            <a class="btn btn-outline btn-sm" href="{{ route('admin.shipments.preview', $s) }}" target="_blank" title="Invoice preview">Invoice</a>
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
                <tr><td colspan="8" style="text-align:center;color:var(--muted);padding:30px">No shipments found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $shipments->links() }}

<script>
document.querySelectorAll('.status-input').forEach(function (el) {
    el.addEventListener('change', function () { el.closest('form').submit(); });
});

var selAll = document.getElementById('selAll');
var rows = document.querySelectorAll('.sel-row');
var bar = document.getElementById('bulkBar');
var form = document.getElementById('bulkForm');
function syncBulk() {
    var ids = [];
    rows.forEach(function (r) { if (r.checked) ids.push(r.value); });
    bar.style.display = ids.length ? 'block' : 'none';
    document.getElementById('bulkCount').textContent = ids.length + ' selected';
    form.querySelectorAll('input[name="ids[]"]').forEach(function (i) { i.remove(); });
    ids.forEach(function (id) {
        var h = document.createElement('input');
        h.type = 'hidden'; h.name = 'ids[]'; h.value = id;
        form.appendChild(h);
    });
}
if (selAll) selAll.addEventListener('change', function () {
    rows.forEach(function (r) { r.checked = selAll.checked; });
    syncBulk();
});
rows.forEach(function (r) { r.addEventListener('change', syncBulk); });
</script>
@endsection
