<?php

namespace Modules\Services\Http\Controllers;

use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Main\Models\Office;
use Illuminate\Routing\Controller;
use Modules\ExternalOffice\Models\Cv;
use Modules\ExternalOffice\Models\Flight;
use Modules\ExternalOffice\Models\CvFlight;
use App\Notifications\NewFlightNotification;
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

        return view('services::flights.index', compact('flights'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $offices = Office::whereStatus(1)->get();
        $cvs = Cv::whereStatus(Cv::STATUS_CONTRACTED)->doesntHave('flgihts')->with('profession')->get();

        return view('services::flights.create', compact('offices', 'cvs'));
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
            'office_id' => ['nullable', 'numeric'],
        ]);

        $data['departure_at'] = Carbon::parse("{$request->departure_date} {$request->departure_time}");
        $data['arrival_at'] = Carbon::parse("{$request->departure_date} {$request->departure_time}");
        $data['user_id'] = $request->user()->id;
        $data['office_id'] = $request->office_id ?? null;

        $flight = Flight::create($data);

        foreach ($request->cv_id as $cvId) {
            $flight->passengers()->create(['cv_id' => $cvId]);
        }

        $flight->attach();

        // Notification::send(User::all(), new NewFlightNotification());

        return redirect()->route('services.flights.index')->with('success', __('global.operation_success'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Flight $flight)
    {
        return view('services::flights.flight', compact('flight'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Flight $flight)
    {
        $offices = Office::whereStatus(1)->get();

        $cvs = Cv::whereStatus(Cv::STATUS_CONTRACTED)->doesntHave('flgihts')->with('profession')->get();

        return view('services::flights.edit', compact('flight', 'cvs', 'offices'));
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
            'office_id' => ['nullable', 'numeric'],
        ]);

        $data['departure_at'] = Carbon::parse("{$request->departure_date} {$request->departure_time}");
        $data['arrival_at'] = Carbon::parse("{$request->departure_date} {$request->departure_time}");
        $data['office_id'] = $request->office_id ?? null;

        $flight->update($data);

        $flight->passengers()->delete();

        foreach ($request->cv_id as $cvId) {
            $flight->passengers()->create(['cv_id' => $cvId]);
        }

        return redirect()->route('services.flights.index')->with('success', __('global.operation_success'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
