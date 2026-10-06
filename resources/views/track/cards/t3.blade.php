<div class="inv inv-t3">
    <div class="t3-head">
        <div class="brand">
            @if (!empty($settings['logo']))
                <img src="{{ asset($settings['logo']) }}" alt="{{ $settings['company_name'] }} logo">
            @endif
            <span>
                <span class="brand-name">{{ $settings['company_name'] }}</span>
                <span class="brand-tag">{{ $settings['tagline'] }}</span>
            </span>
        </div>
        <div class="t3-meta">
            <div><b>Head Office:</b> {{ $settings['head_office'] }}</div>
            <div>{{ $settings['email'] }} · {{ $settings['phone'] }}</div>
        </div>
    </div>

    <div class="t3-track">
        <div>
            <div class="label">Tracking No</div>
            <div class="no">{{ $shipment->formattedTrackingNo() }}</div>
        </div>
        <span class="t3-pill">{{ $shipment->status }}</span>
    </div>

    <div class="t3-body">
        <div class="t3-cols">
            <div class="t3-box">
                <span class="sec-label">Sender Details</span>
                <div class="kv"><div class="k">Name</div><div class="v">{{ $shipment->sender_name ?? '—' }}</div></div>
                <div class="kv"><div class="k">Address</div><div class="v">{{ $shipment->sender_address ?? '—' }}</div></div>
                @if ($shipment->sender_delivery)
                    <div class="kv"><div class="k">Delivery</div><div class="v">{{ $shipment->sender_delivery }}</div></div>
                @endif
            </div>
            <div class="t3-box">
                <span class="sec-label">Consignee Details</span>
                <div class="kv"><div class="k">Name</div><div class="v">{{ $shipment->consignee_name ?? '—' }}</div></div>
                <div class="kv"><div class="k">Phone</div><div class="v">{{ $shipment->consignee_phone ?? '—' }}</div></div>
                <div class="kv"><div class="k">Address</div><div class="v">{{ $shipment->consignee_address ?? '—' }}</div></div>
            </div>
        </div>

        <div class="t3-box">
            <span class="sec-label">Package Information</span>
            <div class="t3-grid">
                <div class="kv"><div class="k">Description</div><div class="v">{{ $shipment->description ?? '—' }}</div></div>
                <div class="kv"><div class="k">Delivery Location</div><div class="v">{{ $shipment->delivery_location ?? '—' }}</div></div>
                <div class="kv"><div class="k">Status</div><div class="v">{{ $shipment->status }}</div></div>
                <div class="kv"><div class="k">Date</div><div class="v">{{ $shipment->shipment_date?->format('d/m/Y') ?? '—' }}</div></div>
            </div>
        </div>

        <div class="t3-cols">
            <div class="t3-box">
                <span class="sec-label">Charges</span>
                <table class="charges-table">
                    <tbody>
                        @foreach ($shipment->charges as $charge)
                            <tr><td>{{ $charge->label }}</td><td class="amt">${{ number_format((float) $charge->amount, 0) }}</td></tr>
                        @endforeach
                        <tr class="total-row"><td><b>Total Due</b></td><td class="amt"><b>${{ number_format($shipment->totalCharges(), 0) }}</b></td></tr>
                    </tbody>
                </table>
                @php $methods = \App\Models\Setting::enabledPaymentMethods(); @endphp
                @if (count($methods))
                    <div class="pay-methods" style="margin-top:12px">
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
            <div class="t3-box t3-signbox">
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
        </div>

        @if ($shipment->events->count())
            <div class="t3-box">
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
    </div>

    <div class="t3-foot">
        <div class="barcode">{!! $barcodeSvg !!}</div>
        <div class="cap">{{ $shipment->tracking_no }}</div>
    </div>
</div>
