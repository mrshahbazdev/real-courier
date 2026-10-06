<div class="inv inv-t5">
    <div class="t5-top">
        <div class="t5-trackcell">
            <div class="label">Tracking No</div>
            <div class="no">{{ $shipment->formattedTrackingNo() }}</div>
            <span class="t5-pill">{{ $shipment->status }}</span>
        </div>
        <div class="t5-brand">
            @if (!empty($settings['logo']))
                <img src="{{ asset($settings['logo']) }}" alt="{{ $settings['company_name'] }} logo">
            @endif
            <div class="t5-company">{{ $settings['company_name'] }}</div>
            <div class="t5-sub">{{ $settings['head_office'] }} · {{ $settings['email'] }}</div>
        </div>
    </div>

    <div class="t5-grid">
        <div class="t5-cell">
            <div class="t5-ch">Sender</div>
            <b>{{ $shipment->sender_name ?? '—' }}</b><br>
            <span>{{ $shipment->sender_address ?? '—' }}</span>
            @if ($shipment->sender_delivery)<br><span>{{ $shipment->sender_delivery }}</span>@endif
        </div>
        <div class="t5-cell">
            <div class="t5-ch">Consignee</div>
            <b>{{ $shipment->consignee_name ?? '—' }}</b><br>
            <span>{{ $shipment->consignee_phone ?? '—' }}</span><br>
            <span>{{ $shipment->consignee_address ?? '—' }}</span>
        </div>
        <div class="t5-cell">
            <div class="t5-ch">Package</div>
            <b>{{ $shipment->description ?? '—' }}</b><br>
            <span>To: {{ $shipment->delivery_location ?? '—' }}</span><br>
            <span>Date: {{ $shipment->shipment_date?->format('d/m/Y') ?? '—' }}</span>
        </div>
        <div class="t5-cell t5-barcell">
            <div class="barcode">{!! $barcodeSvg !!}</div>
            <div class="cap">{{ $shipment->tracking_no }}</div>
        </div>
    </div>

    <div class="t5-charges">
        <div class="t5-ch">Charges</div>
        <table class="charges-table">
            <tbody>
                @foreach ($shipment->charges as $charge)
                    <tr><td>{{ $charge->label }}</td><td class="amt">${{ number_format((float) $charge->amount, 0) }}</td></tr>
                @endforeach
                <tr class="total-row"><td><b>Total Due</b></td><td class="amt"><b>${{ number_format($shipment->totalCharges(), 0) }}</b></td></tr>
            </tbody>
        </table>
    </div>

    <div class="t5-foot">
        <div>
            @php $methods = \App\Models\Setting::enabledPaymentMethods(); @endphp
            @if (count($methods))
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
            <div class="signature" style="margin-top:16px">
                @if (!empty($settings['signature']))
                    <img src="{{ asset($settings['signature']) }}" alt="Authorized signature">
                @endif
                <div class="sig-name">{{ $settings['authorized_signature_name'] ?: 'Authorized Signature' }}</div>
            </div>
        </div>
        <div class="qr-block">
            {!! $qrSvg !!}
            <div class="cap">Scan to verify</div>
        </div>
    </div>

    @if ($shipment->events->count())
        <div class="t5-hist">
            <div class="t5-ch">Tracking History</div>
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
