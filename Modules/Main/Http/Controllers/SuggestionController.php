<?php

namespace Modules\Main\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Main\Models\Suggestion;

class SuggestionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $suggestions =  Suggestion::where('seen', 0)->get();
        return view('main::suggestions.index', compact('suggestions'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('main::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'content' => 'required | string'
        ]);

        Suggestion::create([
            'content' => $request->content,
            'user_id' => auth()->user()->getKey()
        ]);

        return back()->with('success', 'تمت العملية بنجاح');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('main::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('main::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $suggestion = Suggestion::find($id);

        $suggestion->update([
            'seen' => 1,
            'useful' => 1,
        ]);

        return back()->with('success', 'تمت العملية بنجاح');

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $suggestion = Suggestion::find($id);
        $suggestion->delete();

        return back()->with('success', 'تمت العملية بنجاح');

    }
}
