<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Shipment;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Picqer\Barcode\BarcodeGeneratorSVG;

class TrackingController extends Controller
{
    public function index(): View
    {
        return view('track.index', ['settings' => Setting::allSettings()]);
    }

    public function track(Request $request): View
    {
        $request->validate(['tracking_no' => 'required|string|max:60']);

        $normalized = Shipment::normalizeTrackingNo($request->input('tracking_no'));
        $shipment = Shipment::with('events', 'charges')
            ->where('tracking_no', $normalized)
            ->first();

        return view('track.show', [
            'settings' => Setting::allSettings(),
            'shipment' => $shipment,
            'query' => $request->input('tracking_no'),
            'barcodeSvg' => $shipment ? $this->barcode($shipment->tracking_no) : null,
            'qrSvg' => $shipment ? $this->qr(route('track', ['tracking_no' => $shipment->tracking_no])) : null,
            'template' => $shipment
                ? ($shipment->invoice_template ?: Setting::get('default_invoice_template') ?: 't1')
                : 't1',
        ]);
    }

    private function barcode(string $value): string
    {
        $generator = new BarcodeGeneratorSVG();

        return $generator->getBarcode($value, $generator::TYPE_CODE_128, 2, 60);
    }

    private function qr(string $value): string
    {
        $renderer = new ImageRenderer(new RendererStyle(160), new SvgImageBackEnd());
        $writer = new Writer($renderer);

        return $writer->writeString($value);
    }
}
