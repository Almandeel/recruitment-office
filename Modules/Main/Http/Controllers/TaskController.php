<?php

namespace Modules\Main\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Main\Models\Task;
use Modules\Main\Models\TaskUser;
use Illuminate\Routing\Controller;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $tasks = TaskUser::where('user_id', auth()->user()->getKey())->get();
        $users = User::all();
        return view('main::tasks.index', compact('tasks', 'users'));
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
            'name'      => 'required | string',
            'user_id'   => 'required',
        ]);

        $task = Task::create([
            'name' => $request->name,
            'user_id' => auth()->user()->getKey()
        ]);

        for ($index = 0; $index < count($request->user_id); $index++) { 
            $taskuser = TaskUser::create([
                'task_id' => $task->id,
                'user_id' => $request->user_id[$index]
            ]);
        }

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
        $task = Task::find($id);

        if($request->type == 'done') {
            $task->update([
                'status' => 1
            ]);
        }else {
            $request->validate([
                'name'      => 'required | string',
                'user_id'   => 'required',
            ]);
    
            $task->update([
                'name' => $request->name
            ]);
    
            foreach($task->taskUser as $user) {
                $user->delete();
            }
    
            for ($index = 0; $index < count($request->user_id); $index++) { 
                $taskuser = TaskUser::create([
                    'task_id' => $task->id,
                    'user_id' => $request->user_id[$index]
                ]);
            }
        }

        return back()->with('success', 'تمت العملية بنجاح');

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
