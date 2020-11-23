<?php

namespace Modules\ExternalOffice\Http\Controllers;

use App\Notifications\NewFlightNotification;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ExternalOffice\Models\Cv;
use Modules\ExternalOffice\Models\Flight;
use Modules\ExternalOffice\Models\CvFlight;
use Illuminate\Support\Facades\Notification;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $flights = Flight::with('passengers', 'passengers.cv', 'passengers.cv.contracts')->get();

        return view('externaloffice::flights.index', compact('flights'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $cvs = Cv::whereStatus(Cv::STATUS_CONTRACTED)->doesntHave('flgihts')->with('profession')->get();

        return view('externaloffice::flights.create', compact('cvs'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'departure_date' => ['required', 'date'],
            'arrival_date' => ['required', 'date'],
            'departure_time' => ['required'],
            'arrival_time' => ['required'],
            'departure_airport' => ['required', 'string'],
            'arrival_airport' => ['required', 'string'],
            'trip_number' => ['required', 'string'],
            'airline_name' => ['required', 'string'],
            'cv_id' => ['required', 'array'],
        ]);

        $data['departure_at'] = Carbon::parse("{$request->departure_date} {$request->departure_time}");
        $data['arrival_at'] = Carbon::parse("{$request->departure_date} {$request->departure_time}");
        $data['office_id'] = $request->user()->office_id;

        $flight = Flight::create($data);

        foreach ($request->cv_id as $cvId) {
            $flight->passengers()->create(['cv_id' => $cvId]);
        }

        $flight->attach();

        Notification::send(User::all(), new NewFlightNotification());

        return redirect()->route('office.flights.index')->with('success', __('global.operation_success'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Flight $flight, CvFlight $passenger)
    {
        dd($passenger);
        return view('externaloffice::flights.show', [
            'passenger' => $passenger,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Flight $flight)
    {
        $cvs = Cv::whereStatus(Cv::STATUS_CONTRACTED)->doesntHave('flgihts')->with('profession')->get();

        return view('externaloffice::flights.edit', compact('flight', 'cvs'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Flight $flight)
    {
        $data = $request->validate([
            'departure_date' => ['required', 'date'],
            'arrival_date' => ['required', 'date'],
            'departure_time' => ['required'],
            'arrival_time' => ['required'],
            'departure_airport' => ['required', 'string'],
            'arrival_airport' => ['required', 'string'],
            'trip_number' => ['required', 'string'],
            'airline_name' => ['required', 'string'],
            'cv_id' => ['required', 'array'],
        ]);

        $data['departure_at'] = Carbon::parse("{$request->departure_date} {$request->departure_time}");
        $data['arrival_at'] = Carbon::parse("{$request->departure_date} {$request->departure_time}");

        $flight->update($data);

        $flight->passengers()->delete();

        foreach ($request->cv_id as $cvId) {
            $flight->passengers()->create(['cv_id' => $cvId]);
        }

        return redirect()->route('office.flights.index')->with('success', __('global.operation_success'));
    }
}
