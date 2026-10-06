<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with('charges')->withCount('events')->latest()->paginate(20);

        return view('admin.dashboard', compact('shipments'));
    }

    public function create()
    {
        return view('admin.shipments.form', ['shipment' => new Shipment()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['tracking_no'] = Shipment::normalizeTrackingNo($data['tracking_no']);

        $shipment = Shipment::create($data);

        return redirect()->route('admin.shipments.edit', $shipment)->with('success', 'Shipment created.');
    }

    public function edit(Shipment $shipment)
    {
        $shipment->load('events', 'charges');

        return view('admin.shipments.form', compact('shipment'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $data = $this->validated($request, $shipment->id);
        $data['tracking_no'] = Shipment::normalizeTrackingNo($data['tracking_no']);

        $shipment->update($data);

        return back()->with('success', 'Shipment updated.');
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Shipment deleted.');
    }

    public function addEvent(Request $request, Shipment $shipment)
    {
        $data = $request->validate([
            'status' => 'required|string|max:120',
            'location' => 'nullable|string|max:160',
            'description' => 'nullable|string|max:500',
            'happened_at' => 'nullable|date',
        ]);

        $shipment->events()->create($data);

        return back()->with('success', 'Tracking event added.');
    }

    public function deleteEvent(Shipment $shipment, int $eventId)
    {
        $shipment->events()->where('id', $eventId)->delete();

        return back()->with('success', 'Tracking event removed.');
    }

    public function addCharge(Request $request, Shipment $shipment)
    {
        $data = $request->validate([
            'label' => 'required|string|max:160',
            'amount' => 'required|numeric|min:0',
        ]);

        $data['sort_order'] = ($shipment->charges()->max('sort_order') ?? 0) + 1;
        $shipment->charges()->create($data);

        return back()->with('success', 'Charge added.');
    }

    public function deleteCharge(Shipment $shipment, int $chargeId)
    {
        $shipment->charges()->where('id', $chargeId)->delete();

        return back()->with('success', 'Charge removed.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'tracking_no' => [
                'required', 'string', 'max:60',
                Rule::unique('shipments', 'tracking_no')->ignore($ignoreId),
            ],
            'sender_name' => 'nullable|string|max:160',
            'sender_address' => 'nullable|string|max:255',
            'sender_delivery' => 'nullable|string|max:160',
            'consignee_name' => 'nullable|string|max:160',
            'consignee_phone' => 'nullable|string|max:60',
            'consignee_address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'delivery_location' => 'nullable|string|max:160',
            'status' => 'required|string|max:120',
            'shipment_date' => 'nullable|date',
        ]);
    }
}
