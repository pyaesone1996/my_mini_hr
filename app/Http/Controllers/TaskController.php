<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function tasksData(Request $request)
    {
        $project = Project::with('tasks')->where('id',$request->project_id)->firstOrFail();

        return view('components.tasks',compact('project'))->render();
    }

    public function taskDraggable(Request $request)
    {
        $project = Project::with('tasks')->where('id',$request->project_id)->firstOrFail();

        if($request->pendingTasks){
            $pendingTasks = explode(',',$request->pendingTasks);
            foreach($pendingTasks as $key => $task){
               $task = collect($project->tasks)->where('id',$task)->first();
               if($task){
                   $task->serial_number = $key;
                   $task->status = 'pending';
                   $task->update();
               }
            }
        }

        if($request->inProgressTasks){
            $inProgressTasks = explode(',',$request->inProgressTasks);
            foreach($inProgressTasks as $key => $task){
               $task = collect($project->tasks)->where('id',$task)->first();
               if($task){
                   $task->serial_number = $key;
                   $task->status = 'in_progress';
                   $task->update();
               }
            }
        }

        if($request->completeTasks){
            $completeTasks = explode(',',$request->completeTasks);
            foreach($completeTasks as $key => $task){
               $task = collect($project->tasks)->where('id',$task)->first();
               if($task){
                   $task->serial_number = $key;
                   $task->status = 'complete';
                   $task->update();
               }
            }
        }

        return 'success';
    }

    public function store(Request $request)
    {
        $task = new Task();
        $task->project_id = $request->project_id;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->start_date = $request->start_date;
        $task->deadline = $request->deadline;
        $task->priority = $request->priority;
        $task->status = $request->status;
        $task->save();

        $task->members()->sync($request->members);

        return 'success';
    }

    public function update($id,Request $request)
    {
        $task = Task::findOrFail($id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->start_date = $request->start_date;
        $task->deadline = $request->deadline;
        $task->priority = $request->priority;
        $task->update();

         $task->members()->sync($request->members);

        return 'success';
    }
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        
        return 'success';
    }

    
}
