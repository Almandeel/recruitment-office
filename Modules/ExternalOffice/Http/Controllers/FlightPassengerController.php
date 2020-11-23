<?php

namespace Modules\ExternalOffice\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ExternalOffice\Models\Cv;
use Modules\ExternalOffice\Models\CvFlight;
use Modules\ExternalOffice\Models\Flight;

class FlightPassengerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $flights = Flight::all();
        // dd($flights[0]->passengers[0]->cv);
        return view('externaloffice::flights.index', compact('flights'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $cvs = Cv::/* whereStatus(Cv::STATUS_CONTRACTED)-> */with('profession')->get();

        return view('externaloffice::flights.create', compact('cvs'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'departure_date' => ['required', 'date'],
            'arrival_date' => ['required', 'date'],
            'departure_time' => ['required'],
            'arrival_time' => ['required'],
            'departure_airport' => ['required', 'string'],
            'arrival_airport' => ['required', 'string'],
            'trip_number' => ['required', 'numeric'],
            'airline_name' => ['required', 'string'],
            'cv_id' => ['required', 'array'],
        ]);

        $flight = Flight::create([
            'departure_at' => Carbon::parse("{$request->departure_date} {$request->departure_time}"),
            'arrival_at' => Carbon::parse("{$request->arrival_date} {$request->arrival_time}"),
            'departure_airport' => $request->departure_airport,
            'arrival_airport' => $request->arrival_airport,
            'trip_number' => $request->trip_number,
            'airline_name' => $request->airline_name,
        ]);

        foreach ($request->cv_id as $cvId) {
            $flight->passengers()->create(['cv_id' => $cvId]);
        }

        return redirect()->route('office.flights.show', $flight)->with('success', __('global.operation_success'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Flight $flight, CvFlight $passenger)
    {
        return view('externaloffice::flights.show', compact('flight', 'passenger'));
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

        if ($request->status == $passenger::STATUS_ARRIVING) {
            $flight->update(['status' => $flight::STATUS_ARRIVING]);

            $passenger->status = $passenger::STATUS_ARRIVING;
            $passenger->save();
        }

        return back()->with('success', __('global.operation_success'));
    }
}
