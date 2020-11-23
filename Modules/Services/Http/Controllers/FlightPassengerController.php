<?php

namespace Modules\Services\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ExternalOffice\Models\Cv;
use Modules\ExternalOffice\Models\CvFlight;
use Modules\ExternalOffice\Models\Flight;
use Modules\Warehouse\Models\Warehouse;
use Modules\Warehouse\Models\WarehouseCv;

class FlightPassengerController extends Controller
{
    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Flight $flight, CvFlight $passenger)
    {
        return view('services::flights.show', compact('flight', 'passenger'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Flight $flight)
    {
        $passenger = $flight->passengers()->find($request->passenger_id);

        switch ($request->status) {
            case $passenger::STATUS_ARRIVING:
                $flight->update(['status' => $flight::STATUS_ARRIVING]);

                $passenger->status = $passenger::STATUS_ARRIVING;
                break;

            case $passenger::STATUS_ARRIVED:
                $flight->update(['status' => $flight::STATUS_ARRIVED]);

                $passenger->status = $passenger::STATUS_ARRIVED;
                break;

            case $passenger::STATUS_NOT_ARRIVED:
                $passenger->status = $passenger::STATUS_NOT_ARRIVED;
                $flight->update(['status' => $flight::STATUS_ARRIVED]);
                break;

            case $passenger::STATUS_HOUSED:
                $warehouse = Warehouse::firstOrCreate(['name' => 'First', 'address' => 'Main Address', 'phone' => '132456789']);
                $warehouse->warehouseCv()->create([
                    'cv_id' => $passenger->cv_id,
                    'entry_date' => now(),
                ]);

                $passenger->status = $passenger::STATUS_HOUSED;
                break;

            case $passenger::STATUS_RECIVED:

                if ($passenger->status == $passenger::STATUS_HOUSED) {
                    $warehouse = WarehouseCv::where('cv_id', $passenger->cv_id)->where('status', 0)->latest()->first();
                    $warehouse->update([
                        'exit_date' => now(),
                        'status' => 1
                    ]);
                }

                $passenger->status = $passenger::STATUS_RECIVED;
                $passenger->customer_status = $passenger::STATUS_CUSTOMER_RECIVED;
                break;
        }

        $passenger->save();

        return back()->with('success', __('global.operation_success'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function updateCustomerStatus(Request $request, Flight $flight, CvFlight $passenger)
    {
        $passenger = $flight->passengers()->find($request->passenger_id);

        $passenger->customer_status = $request->status;
        $passenger->save();

        return back()->with('success', __('global.operation_success'));
    }
}
