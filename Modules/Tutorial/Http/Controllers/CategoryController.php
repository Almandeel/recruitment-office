<?php

namespace Modules\Tutorial\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Tutorial\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $categories = Category::all();
        return view('tutorial::categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('tutorial::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required | string'
        ]);

        Category::create($request->all());

        return back()->with('success', 'تمت العملية بنجاح');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('tutorial::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('tutorial::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        
        $request->validate([
            'name' => 'required | string'
        ]);

        $category->update($request->all());

        return back()->with('success', 'تمت العملية بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        
        if(count($category->posts)) {
            foreach($category->posts as $post) {
                $post->delete();
            }
        }

        $category->delete();


        return back()->with('success', 'تمت العملية بنجاح');


    }
}
