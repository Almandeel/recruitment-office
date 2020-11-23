<?php

namespace Modules\Services\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Services\Models\Customer;
use Modules\Services\Models\{Office};
use Modules\ExternalOffice\Models\{Country};
use Modules\ExternalOffice\Models\Profession;
use Modules\ExternalOffice\Models\{Advance, Pull, Cv};

class CvController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:cv-create')->only(['create', 'store']);
        $this->middleware('permission:cv-read')->only(['index', 'show']);
        $this->middleware('permission:cv-update')->only(['edit', 'update']);
        $this->middleware('permission:cv-delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $cvs = Cv::get();
        $customers = Customer::all();
        return view('services::cvs.index', compact('cvs', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $professions = Profession::all();
        $countries = Country::all();
        $customers = Customer::all();
        return view('services::cvs.create', compact('professions', 'countries', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required | string | max:140',
            'passport' => 'required | string | max:140',
            'amount' => 'required | numeric',
            'profession_id' => 'required | numeric',
            'gender' => 'required | numeric',
            'country_id' => 'required | numeric',
            'birth_date' => 'required | date',
            'procedure' => 'required | string | max:200',
            'children' => 'numeric','phone',

            'reference_number' => 'nullable',
            'religion' => 'nullable|string',
            'birthplace' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'phone' => 'nullable',
            'qualification' => 'nullable | string',
            'english_speaking_level' => 'nullable | string',
            'experince' => 'nullable | string',
            'weight' => 'nullable | numeric',
            'height' => 'nullable | numeric',
            'sewing' => 'nullable | boolean',
            'decor' => 'nullable | boolean',
            'cleaning' => 'nullable | boolean',
            'washing' => 'nullable | boolean',
            'cooking' => 'nullable | boolean',
            'babysitting' => 'nullable | boolean',
            'passport_place_of_issue' => 'nullable | string',
            'passport_issuing_date' => 'nullable | date',
            'passport_expiration_date' => 'nullable | date',
            'contract_period' => 'nullable | string',
            'contract_salary' => 'nullable | numeric',
            'bio' => 'nullable | string',
            'photo' => 'nullable | file',
            'passport_photo' => 'nullable | file',
        ]);

        $validatedData['user_id'] = auth()->user()->getKey();
        $validatedData['status'] = Cv::STATUS_ACCEPTED;

        // if ($request->has('photo')) {
        //     $file = $request->file('photo');
        //     $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        //     $newFileName = Str::random(16) . date('Ymdhis') . '.' . $extension;
        //     $file->move(public_path("/cvs_data"), $newFileName);

        //     array_merge($validatedData, ['photo' => $newFileName]);
        // }

        if ($request->has('photo')) {
            $fileName = time().'.'.$request->photo->extension();
            
            $request->photo->move(public_path('cvs_data'), $fileName);
            $validatedData['photo'] = $fileName;
        }

        if ($request->has('passport_photo')) {
            $fileName = time().'.'.$request->passport_photo->extension();
            
            $request->passport_photo->move(public_path('cvs_data'), $fileName);
            $validatedData['passport_photo'] = $fileName;
        }

        $cv = Cv::create($validatedData);

        if ($cv) {
            $cv->attach();
        }
        return redirect()->route('servicescvs.show', $cv)->with('success', __('global.operation_success'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $cv = Cv::find($id);
        $customers = Customer::all();
        // dd($cv->attachments);
        return view('services::cvs.show', compact('cv', 'customers'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $cv = Cv::find($id);
        $professions = Profession::all();
        $countries = Country::all();
        $customers = Customer::all();
        return view('services::cvs.edit', compact('cv', 'professions', 'countries', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $cv = Cv::find($id);

        if ($request->type == 'accept') {
            $cv->update([
                'status' => Cv::STATUS_ACCEPTED
            ]);
        } else {
            $request->validate([
                'name' => 'required | string | max:140',
                'passport' => 'required | string | max:140',
                'amount' => 'required | numeric',
                'profession_id' => 'required | numeric',
                'gender' => 'required | numeric',
                'country_id' => 'required | numeric',
                'birth_date' => 'required | date',
                'procedure' => 'required | string | max:200',
                'children' => 'numeric', 'phone',

                'reference_number' => 'nullable',
                'religion' => 'nullable|string',
                'birthplace' => 'nullable|string',
                'marital_status' => 'nullable|string',
                'phone' => 'nullable',
                'qualification' => 'nullable | string',
                'english_speaking_level' => 'nullable | string',
                'experince' => 'nullable | string',
                'weight' => 'nullable | numeric',
                'height' => 'nullable | numeric',
                'sewing' => 'nullable | boolean',
                'decor' => 'nullable | boolean',
                'cleaning' => 'nullable | boolean',
                'washing' => 'nullable | boolean',
                'cooking' => 'nullable | boolean',
                'babysitting' => 'nullable | boolean',
                'passport_place_of_issue' => 'nullable | string',
                'passport_issuing_date' => 'nullable | date',
                'passport_expiration_date' => 'nullable | date',
                'contract_period' => 'nullable | string',
                'contract_salary' => 'nullable | numeric',
                'bio' => 'nullable | string',
                'photo' => 'nullable | file',
                'passport_photo' => 'nullable | file',
            ]);

            $validatedData = $request->all();

            if ($request->has('photo')) {
                $fileName = time().'.'.$request->photo->extension();
                
                $request->photo->move(public_path('cvs_data'), $fileName);
                $validatedData['photo'] = $fileName;
            }
    
            if ($request->has('passport_photo')) {
                $fileName = time().'.'.$request->passport_photo->extension();
                
                $request->passport_photo->move(public_path('cvs_data'), $fileName);
                $validatedData['passport_photo'] = $fileName;
            }

            $cv->update($validatedData);
        }

        return back()->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $cv = Cv::find($id);
        $cv->delete();
        return redirect()->route('servicescvs.index')->with('success', __('global.operation_success'));
    }

    public function pull(Request $request, Pull $pull)
    {
        if (!$pull->cv->accepted) {
            return back()->with('error', 'error');
        }

        $pull->update([
            'damages' => $request->damages,
            'confirmed' => 1,
        ]);

        return redirect()->route('servicescvs.show', $pull->cv)->with('success', __('global.operation_success'));
    }

    public function returns(Request $request, Cv $cv)
    {
        // dd($cv->office->id);
        if (!$cv->accepted) {
            return back()->with('error', 'error');
        }

        $cv->return()->create([
            'cv_id' => $cv->id,
            'user_id' => $request->user()->id,
        ]);

        if ($request->withAdvance) {
            Advance::create([
                'amount' => $cv->payed(),
                'user_id' => $request->user()->id,
                'office_id' => $cv->office->id,
            ]);
        }

        return back()->with('success', __('global.operation_success'));
    }
}
