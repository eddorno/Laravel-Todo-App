<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::orderBy('due_date', 'asc')->paginate(5);

        return view('tasks.index')->with('tasks', $tasks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      //validate info
        $this->validate($request, [
          'name' => 'required|string|max:255|min:3',
          'description' => 'required|string|max:10000|min:10',
          'due_date' => 'required|date'
        ]);

        //create new task
        $task = new Task;

        //assign task data
        $task->name = $request->name;
        $task->description = $request->description;
        $task->due_date = $request->due_date;

        //save task
        $task->save();

        //flash session message w/success
        Session::flash('success', 'Created Task Successfully!');

        //return to index page
        return redirect()->route('task.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);

        $task->dueDateFormatting = false;

        return view('tasks.edit')->withTask($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      //validate info
        $this->validate($request, [
          'name' => 'required|string|max:255|min:3',
          'description' => 'required|string|max:10000|min:10',
          'due_date' => 'required|date'
        ]);

        //find task to update
        $task = Task::find($id);

        //assign task data
        $task->name = $request->name;
        $task->description = $request->description;
        $task->due_date = $request->due_date;

        //save task
        $task->save();

        //flash session message w/success
        Session::flash('success', 'Updated Task Successfully!');

        //return to index page
        return redirect()->route('task.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //find task to delete
        $task = Task::find($id);

        //delete task
        $task->delete();

        //show success message if deleted
        Session::flash('success', 'Task Deleted');

        //return to todo list
        return redirect()->route('task.index');
    }
}
