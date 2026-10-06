<div class="inv inv-t4">
    <div class="t4-head">
        <div class="t4-head-left">
            @if (!empty($settings['logo']))
                <img class="t4-logo" src="{{ asset($settings['logo']) }}" alt="{{ $settings['company_name'] }} logo">
            @endif
            <div>
                <div class="t4-company">{{ $settings['company_name'] }}</div>
                <div class="t4-sub">{{ $settings['head_office'] }}</div>
                <div class="t4-sub">{{ $settings['email'] }} · {{ $settings['phone'] }}</div>
            </div>
        </div>
        <div class="t4-invno">
            <div class="t4-invtitle">SHIPMENT INVOICE</div>
            <div class="t4-invmeta">No. {{ $shipment->tracking_no }}</div>
            <div class="t4-invmeta">Date: {{ $shipment->shipment_date?->format('d/m/Y') ?? now()->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="t4-parties">
        <div class="t4-party">
            <div class="t4-ph">From (Sender)</div>
            <b>{{ $shipment->sender_name ?? '—' }}</b><br>
            {{ $shipment->sender_address ?? '—' }}
            @if ($shipment->sender_delivery)<br>{{ $shipment->sender_delivery }}@endif
        </div>
        <div class="t4-party">
            <div class="t4-ph">To (Consignee)</div>
            <b>{{ $shipment->consignee_name ?? '—' }}</b><br>
            {{ $shipment->consignee_phone ?? '—' }}<br>
            {{ $shipment->consignee_address ?? '—' }}
        </div>
        <div class="t4-party">
            <div class="t4-ph">Shipment</div>
            <b>{{ $shipment->description ?? '—' }}</b><br>
            Destination: {{ $shipment->delivery_location ?? '—' }}<br>
            Status: <b>{{ $shipment->status }}</b>
        </div>
    </div>

    <table class="t4-table">
        <thead>
            <tr><th>#</th><th>Description of Charges</th><th class="amt">Amount (USD)</th></tr>
        </thead>
        <tbody>
            @foreach ($shipment->charges as $i => $charge)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $charge->label }}</td>
                    <td class="amt">${{ number_format((float) $charge->amount, 0) }}</td>
                </tr>
            @endforeach
            <tr class="t4-total">
                <td colspan="2"><b>TOTAL DUE</b></td>
                <td class="amt"><b>${{ number_format($shipment->totalCharges(), 0) }}</b></td>
            </tr>
        </tbody>
    </table>

    <div class="t4-bottom">
        <div>
            @php $methods = \App\Models\Setting::enabledPaymentMethods(); @endphp
            @if (count($methods))
                <div class="t4-ph">Accepted Payment Methods</div>
                <div class="pay-methods">
                    @foreach ($methods as $m)
                        @if ($m === 'visa')<span class="pay-badge" style="color:#1a1f71;font-style:italic">VISA</span>@endif
                        @if ($m === 'paypal')<span class="pay-badge" style="color:#003087">PayPal</span>@endif
                        @if ($m === 'mastercard')<span class="pay-badge"><span class="dots"><span class="dot" style="background:#eb001b"></span><span class="dot" style="background:#f79e1b"></span></span>Mastercard</span>@endif
                        @if ($m === 'stripe')<span class="pay-badge" style="color:#635bff">stripe</span>@endif
                        @if ($m === 'gpay')<span class="pay-badge">G Pay</span>@endif
                        @if ($m === 'applepay')<span class="pay-badge">&#63743; Pay</span>@endif
                    @endforeach
                </div>
            @endif
            <div class="signature" style="margin-top:26px">
                @if (!empty($settings['signature']))
                    <img src="{{ asset($settings['signature']) }}" alt="Authorized signature">
                @endif
                <div class="sig-name">{{ $settings['authorized_signature_name'] ?: 'Authorized Signature' }}</div>
            </div>
        </div>
        <div class="qr-block">
            {!! $qrSvg !!}
            <div class="cap">Scan to verify &amp; track</div>
        </div>
    </div>

    @if ($shipment->events->count())
        <div class="t4-hist">
            <div class="t4-ph">Tracking History</div>
            <ul class="timeline">
                @foreach ($shipment->events as $event)
                    <li>
                        <div class="t-status">{{ $event->status }}</div>
                        <div class="t-meta">
                            {{ $event->happened_at?->format('d M Y, H:i') }}
                            @if ($event->location) &middot; {{ $event->location }} @endif
                        </div>
                        @if ($event->description)<div class="t-desc">{{ $event->description }}</div>@endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
