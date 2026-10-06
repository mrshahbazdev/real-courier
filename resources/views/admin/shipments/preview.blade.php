@extends('layouts.admin')

@section('title', 'Invoice Preview — '.$shipment->tracking_no)

@section('content')
<div class="page-head" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
    <h1>Invoice Preview — {{ $shipment->tracking_no }}</h1>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
        <form method="GET" style="display:flex;gap:8px;align-items:center">
            <label style="font-size:13px;color:var(--muted);font-weight:600">Template:</label>
            <select name="template" onchange="this.form.submit()" style="padding:8px 12px;border:1.5px solid var(--line);border-radius:8px;font:inherit">
                @foreach ($templates as $key => $name)
                    <option value="{{ $key }}" @selected($template === $key)>{{ $name }}{{ $shipment->invoice_template === $key ? ' (saved)' : '' }}</option>
                @endforeach
            </select>
        </form>
        <button onclick="window.print()" class="btn">Print</button>
        <a class="btn btn-accent" href="{{ route('admin.shipments.edit', $shipment) }}">Edit Shipment</a>
    </div>
</div>

<div style="max-width:860px;margin:20px auto">
    @include('track.cards.'.$template)
</div>

<style>
@media print {
    .admin-sidebar, .page-head, .flash { display:none !important; }
    .admin-main, .admin-content { margin:0 !important; padding:0 !important; }
    .inv { box-shadow:none !important; }
}
</style>
@endsection
