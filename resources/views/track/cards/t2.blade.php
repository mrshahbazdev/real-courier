<div class="inv inv-t2">
    <div class="t2-head">
        <div class="brand">
            @if (!empty($settings['logo']))
                <img src="{{ asset($settings['logo']) }}" alt="{{ $settings['company_name'] }} logo">
            @endif
            <span>
                <span class="brand-name">{{ $settings['company_name'] }}</span>
                <span class="brand-tag">{{ $settings['tagline'] }}</span>
            </span>
        </div>
        <div class="t2-meta">
            {{ $settings['head_office'] }}<br>
            {{ $settings['email'] }} · {{ $settings['phone'] }}
        </div>
    </div>

    <div class="t2-track">
        <div class="label">Tracking No</div>
        <div class="no">{{ $shipment->formattedTrackingNo() }}</div>
        <span class="status-pill">{{ $shipment->status }}</span>
        <div class="barcode">{!! $barcodeSvg !!}</div>
        <div class="cap">{{ $shipment->tracking_no }}</div>
    </div>

    <div class="t2-sec">
        <div class="t2-cols">
            <div>
                <span class="sec-label">Sender</span>
                <p><b>{{ $shipment->sender_name ?? '—' }}</b><br>{{ $shipment->sender_address ?? '—' }}
                @if ($shipment->sender_delivery)<br>{{ $shipment->sender_delivery }}@endif</p>
            </div>
            <div>
                <span class="sec-label">Consignee</span>
                <p><b>{{ $shipment->consignee_name ?? '—' }}</b><br>{{ $shipment->consignee_phone ?? '—' }}<br>{{ $shipment->consignee_address ?? '—' }}</p>
            </div>
        </div>
    </div>

    <div class="t2-sec">
        <span class="sec-label">Package</span>
        <div class="t2-row"><span>Description</span><b>{{ $shipment->description ?? '—' }}</b></div>
        <div class="t2-row"><span>Delivery Location</span><b>{{ $shipment->delivery_location ?? '—' }}</b></div>
        <div class="t2-row"><span>Status</span><b>{{ $shipment->status }}</b></div>
        <div class="t2-row"><span>Date</span><b>{{ $shipment->shipment_date?->format('d/m/Y') ?? '—' }}</b></div>
    </div>

    <div class="t2-sec">
        <span class="sec-label">Charges</span>
        @foreach ($shipment->charges as $charge)
            <div class="t2-row"><span>{{ $charge->label }}</span><b>${{ number_format((float) $charge->amount, 0) }}</b></div>
        @endforeach
        <div class="t2-row t2-total"><span>Total Due</span><b>${{ number_format($shipment->totalCharges(), 0) }}</b></div>
        @php $methods = \App\Models\Setting::enabledPaymentMethods(); @endphp
        @if (count($methods))
            <div class="pay-methods t2-pay">
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
    </div>

    <div class="t2-sign">
        <div class="signature">
            @if (!empty($settings['signature']))
                <img src="{{ asset($settings['signature']) }}" alt="Authorized signature">
            @endif
            <div class="sig-name">{{ $settings['authorized_signature_name'] ?: 'Authorized Signature' }}</div>
        </div>
        <div class="qr-block">
            {!! $qrSvg !!}
            <div class="cap">Scan to verify</div>
        </div>
    </div>

    @if ($shipment->events->count())
        <div class="t2-sec">
            <span class="sec-label">Tracking History</span>
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

    <div class="t2-foot">*** {{ $settings['company_name'] }} — {{ $settings['tagline'] }} ***</div>
</div>
