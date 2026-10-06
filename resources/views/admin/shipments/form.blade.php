@extends('layouts.admin')

@section('title', $shipment->exists ? 'Edit Shipment' : 'New Shipment')

@section('content')
<div class="admin-topbar">
    <h1>{{ $shipment->exists ? 'Edit Shipment '.$shipment->tracking_no : 'New Shipment' }}</h1>
    <a class="btn btn-outline" href="{{ route('admin.dashboard') }}">&larr; Back</a>
</div>

@if ($errors->any())
    <div class="notice-error" style="margin-bottom:20px">
        <strong>Please fix the following:</strong>
        <ul style="margin-left:18px">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ $shipment->exists ? route('admin.shipments.update', $shipment) : route('admin.shipments.store') }}">
    @csrf
    @if ($shipment->exists) @method('PUT') @endif

    <div class="panel">
        <h2>Shipment</h2>
        <div class="form-grid">
            <div class="field">
                <label>Tracking No *</label>
                <input name="tracking_no" required value="{{ old('tracking_no', $shipment->tracking_no) }}" placeholder="SHP48212398476">
                <div class="help">Auto-generate ho gaya hai — chahe to badal lo. Spaces/dashes khud remove ho jate hain.</div>
            </div>
            <div class="field">
                <label>Invoice Template</label>
                <select name="invoice_template">
                    <option value="">— Site default ({{ \App\Models\Setting::INVOICE_TEMPLATES[\App\Models\Setting::get('default_invoice_template') ?? 't1'] }}) —</option>
                    @foreach (\App\Models\Setting::INVOICE_TEMPLATES as $key => $name)
                        <option value="{{ $key }}" @selected(old('invoice_template', $shipment->invoice_template) === $key)>{{ $name }}</option>
                    @endforeach
                </select>
                <div class="help">Tracking page isi design me khulega. Default Settings page se set hota hai.</div>
            </div>
            <div class="field">
                <label>Status *</label>
                <input name="status" list="status-list" required value="{{ old('status', $shipment->status ?? 'In Transit') }}">
                <datalist id="status-list">
                    @foreach (\App\Models\Setting::lines('status_options') as $opt)
                        <option value="{{ $opt }}">
                    @endforeach
                </datalist>
                <div class="help">Dropdown se select karo ya khud likho. List Settings page se manage hoti hai.</div>
            </div>
            <div class="field">
                <label>Shipment Date</label>
                <input type="date" name="shipment_date" value="{{ old('shipment_date', $shipment->shipment_date?->format('Y-m-d')) }}">
            </div>
            <div class="field full">
                <label>Package Description</label>
                <input name="description" value="{{ old('description', $shipment->description) }}" placeholder="Undisclosed Box">
            </div>
            <div class="field full">
                <label>Delivery Location</label>
                <input name="delivery_location" value="{{ old('delivery_location', $shipment->delivery_location) }}" placeholder="Paraguay">
            </div>
        </div>
    </div>

    <div class="panel">
        <h2>Sender Details</h2>
        <div class="form-grid">
            <div class="field"><label>Name</label><input name="sender_name" value="{{ old('sender_name', $shipment->sender_name) }}"></div>
            <div class="field"><label>Address</label><input name="sender_address" value="{{ old('sender_address', $shipment->sender_address) }}"></div>
            <div class="field full"><label>Delivery (from sender card section)</label><input name="sender_delivery" value="{{ old('sender_delivery', $shipment->sender_delivery) }}" placeholder="Paraguay"></div>
        </div>
    </div>

    <div class="panel">
        <h2>Consignee Details</h2>
        <div class="form-grid">
            <div class="field"><label>Name</label><input name="consignee_name" value="{{ old('consignee_name', $shipment->consignee_name) }}"></div>
            <div class="field"><label>Phone</label><input name="consignee_phone" value="{{ old('consignee_phone', $shipment->consignee_phone) }}"></div>
            <div class="field full"><label>Address</label><input name="consignee_address" value="{{ old('consignee_address', $shipment->consignee_address) }}"></div>
        </div>
    </div>

    <button class="btn btn-accent" type="submit">{{ $shipment->exists ? 'Save Changes' : 'Create Shipment' }}</button>
</form>

@if ($shipment->exists)
    <div class="panel" style="margin-top:24px">
        <h2>Charges</h2>
        <form method="POST" action="{{ route('admin.shipments.charges.store', $shipment) }}" class="form-grid" style="margin-bottom:20px">
            @csrf
            <div class="field"><label>Charge Label *</label>
                <input name="label" list="charge-list" required placeholder="e.g. Income Tax">
                <datalist id="charge-list">
                    @foreach (\App\Models\Setting::lines('charge_options') as $opt)
                        <option value="{{ $opt }}">
                    @endforeach
                </datalist>
            </div>
            <div class="field"><label>Amount ($) *</label><input type="number" step="0.01" min="0" name="amount" required placeholder="5000"></div>
            <div class="full"><button class="btn btn-primary btn-sm" type="submit">Add Charge</button></div>
        </form>

        <table class="table">
            <thead><tr><th>Charge</th><th>Amount</th><th></th></tr></thead>
            <tbody>
                @forelse ($shipment->charges as $charge)
                    <tr>
                        <td colspan="2" style="padding:6px 12px">
                            <form method="POST" action="{{ route('admin.shipments.charges.update', [$shipment, $charge->id]) }}" style="display:flex;gap:10px;align-items:center">
                                @csrf @method('PUT')
                                <input name="label" value="{{ $charge->label }}" list="charge-list" style="flex:1;padding:8px 11px;border:1.5px solid var(--line);border-radius:7px;font:inherit;font-size:14px">
                                <input type="number" step="0.01" min="0" name="amount" value="{{ (float) $charge->amount }}" style="width:130px;padding:8px 11px;border:1.5px solid var(--line);border-radius:7px;font:inherit;font-size:14px">
                                <button class="btn btn-primary btn-sm" type="submit">Save</button>
                            </form>
                        </td>
                        <td>
                            <form class="inline-form" method="POST" action="{{ route('admin.shipments.charges.destroy', [$shipment, $charge->id]) }}" onsubmit="return confirm('Remove this charge?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="color:var(--muted)">No charges yet.</td></tr>
                @endforelse
                <tr>
                    <td><b>Total</b></td>
                    <td class="mono"><b>${{ number_format($shipment->totalCharges(), 0) }}</b></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="panel" style="margin-top:24px">
        <h2>Tracking History</h2>
        <form method="POST" action="{{ route('admin.shipments.events.store', $shipment) }}" class="form-grid" style="margin-bottom:20px">
            @csrf
            <div class="field"><label>Status *</label>
                <input name="status" list="status-list" required placeholder="Arrived at facility">
            </div>
            <div class="field"><label>Location</label><input name="location" placeholder="Madrid, Spain"></div>
            <div class="field"><label>Date/Time</label><input type="datetime-local" name="happened_at"></div>
            <div class="field"><label>Description</label><input name="description" placeholder="Optional note"></div>
            <div class="full"><button class="btn btn-primary btn-sm" type="submit">Add Event</button></div>
        </form>

        <table class="table">
            <thead><tr><th>Status</th><th>Location</th><th>When</th><th>Description</th><th></th></tr></thead>
            <tbody>
                @forelse ($shipment->events as $event)
                    <tr>
                        <td><b>{{ $event->status }}</b></td>
                        <td>{{ $event->location }}</td>
                        <td>{{ $event->happened_at?->format('d M Y H:i') }}</td>
                        <td>{{ $event->description }}</td>
                        <td>
                            <form class="inline-form" method="POST" action="{{ route('admin.shipments.events.destroy', [$shipment, $event->id]) }}" onsubmit="return confirm('Remove this event?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="color:var(--muted)">No tracking events yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif
@endsection
