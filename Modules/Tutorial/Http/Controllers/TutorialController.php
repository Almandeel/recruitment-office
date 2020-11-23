<?php

namespace Modules\Tutorial\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Tutorial\Models\Category;
use Modules\Tutorial\Models\Tutorial;

class TutorialController extends Controller
{
    public function dashboard() {
        return view('tutorial::dashboard');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if($request->category) {
            $tutorials = Tutorial::orderBy('created_at', 'DESC')->where('category_id',$request->category)->paginate(10);
        }else {
            $tutorials = Tutorial::orderBy('created_at', 'DESC')->paginate(10);
        }
        $categories = Category::all();
        return view('tutorial::index', compact('tutorials', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('tutorial::create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required',
            'content'   => 'required',
        ]);

        $post_content = htmlentities(htmlspecialchars($request->content));
        
        // dd(auth()->user());
        // $mail_content_decode = html_entity_decode(htmlspecialchars_decode($mail_content));
        $category = Category::firstOrCreate(['name' => $request->category]);
        // dd($category);
        Tutorial::create([
            'title' => $request->title,
            'content' => $post_content,
            'category_id' => $category->id,
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
        $tutorials = Tutorial::where('id', '!=', $id)->orderBy('created_at', 'DESC')->get();
        $tutorial = Tutorial::find($id);
        $categories = Category::all();
        return view('tutorial::show', compact('tutorials', 'tutorial', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $tutorial = Tutorial::find($id);
        $categories = Category::all();
        return view('tutorial::edit', compact('tutorial', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $tutorial = Tutorial::find($id);

        $request->validate([
            'title'     => 'required',
            'content'   => 'required',
        ]);

        $post_content = htmlentities(htmlspecialchars($request->content));

        $tutorial->update([
            'title' => $request->title,
            'content' => $post_content,
            'category_id' => $request->category_id,
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
        $tutorial = Tutorial::find($id);
        
        $tutorial->delete(); 

        return redirect()->route('tutorials.index')->with('success', 'تمت العملية بنجاح');

    }
}
