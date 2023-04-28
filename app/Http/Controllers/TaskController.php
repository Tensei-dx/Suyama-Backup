<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\UpdateRequest;
use App\Models\Task;

class TaskController extends Controller
{
    /****************************************************************/
    /* Processing Heirarchy                                         */
    /****************************************************************/
    // index        (1.0) Display a listing og the resource
    // show         (2.0) Display the specified resource
    // update       (3.0) Update the specified resource in storage

    /**
     * <Layer number> (1.0)
     * 
     * <Processing name> index <br>
     * <Function> Display a listing of the resource <br>
     *          METHOD: GET
     *          URI: /tasks/
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Task::query();

        return response($query->get());
    }

    /**
     * <Layer number> (2.0)
     * 
     * <Processing name> show <br>
     * <Function> Display the specified resource <br>
     *          METHOD: GET
     *          URI: /tasks/:task
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return response($task);
    }

    /**
     * <Layer number> (3.0)
     * 
     * <Processing name> update <br>
     * <Function> Update the specified resource in storage <br>
     *          METHOD: PUT
     *          URI: /tasks/:task
     *
     * @param  \App\Http\Requests\Task\UpdateRequest  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Task $task)
    {
        $task->update($request->validated());

        return response()->noContent();
    }
}
