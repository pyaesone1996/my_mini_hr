<?php

namespace App\Http\Controllers;

use App\User;
use App\Project;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Requests\StoreProject;
use App\Http\Requests\UpdateProject;
use App\ProjectLeader;
use App\ProjectMember;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    
    public function index()
    {
        if(!auth()->user()->can('view_project')){
            abort(403,'Unauthorized Action');
        }
        return view('project.index');
    }
    public function ssd(Request $request)
    {
        if(!auth()->user()->can('view_project')){
            abort(403,'Unauthorized Action');
        }
      
        $projects = Project::with('leaders','members');
        return Datatables::of($projects)
        ->addColumn('leaders',function($each){
            $output = '';
            foreach($each->leaders as $leader){
                $output .='<img src="'.$leader->profile().'" alt="" class="profile-thumbnail2">';
            }
            return $output;
        })
        ->addColumn('members',function($each){
            $members = '';
            foreach($each->members as $member){
                $members .='<img src="'.$member->profile().'" alt="" class="profile-thumbnail2">';
            }
            return $members;
        })
        ->editColumn('description',function($each){
            return Str::limit($each->description,100);
        })
        ->editColumn('priority',function($each){
            if($each->priority == 'high'){
                return '<span class="badge badge-pill badge-danger">High</span>';
            }else if($each->priority == 'middle'){
                return '<span class="badge badge-pill badge-info">Middle</span>';
            }else if($each->priority == 'low'){
                return '<span class="badge badge-pill badge-warning">Low</span>';
            }
        })
        ->editColumn('status',function($each){
            if($each->status == 'pending'){
                return '<span class="badge badge-pill badge-warning">Pending</span>';
            }else if($each->status == 'in_progress'){
                return '<span class="badge badge-pill badge-info">In Progress</span>';
            }else if($each->status == 'complete'){
                return '<span class="badge badge-pill badge-success">Complete</span>';
            }
        })
        ->addColumn('plus-icon',function($each){
            return null;
        })
        ->addColumn('action',function($each){
            $info_button = '';
            $edit_button = '';
            $delete_button = '';

            if(auth()->user()->can('view_project')){
                $info_button = '<a href="'.route('project.show',$each->id).'" class="text-info"><i class="fas fa-info-circle"></i></a>';
            }
            if(auth()->user()->can('edit_project')){
                $edit_button = '<a href="'.route('project.edit',$each->id).'" class="text-warning"><i class="far fa-edit"></i></a>';
            }
            if(auth()->user()->can('delete_project')){
                $delete_button = '<a href="#" class="text-danger delete-btn" data-id="'.$each->id.'"><i class="fas fa-trash-alt"></i></a>';
            }

            return "<div class='action-icon'>". $info_button . $edit_button . $delete_button. 
            "</div>";
        })
        ->rawColumns(['leaders','members','action','priority','status'])
        ->make(true);
    }

    public function create()
    {
        if(!auth()->user()->can('create_project')){
            abort(403,'Unauthorized Action');
        }
        $users = User::orderBy('name')->get();
        return view('project.create',compact('users'));
    }

    public function store(StoreProject $request)
    {

        if(!auth()->user()->can('create_project')){
            abort(403,'Unauthorized Action');
        }

        $image_names = null;
        if(request()->hasFile('images')){
            $image_names = [];
            $image_files = $request->file('images');
            foreach($image_files as $image_file){
                $image_name = time(). '.' . uniqid() . '.' .$image_file->getClientOriginalExtension();
                Storage::disk('public')->put('project/image/' .$image_name , file_get_contents($image_file));
                $image_names[] = $image_name;
            }
        }

        $pdf_names = null;
        if(request()->hasFile('files')){
            $pdf_names = [];
            $pdf_files = $request->file('files');
            foreach($pdf_files as $pdf_file){
                $pdf_name = time(). '.' . uniqid() . '.' .$pdf_file->getClientOriginalExtension();
                Storage::disk('public')->put('project/pdf/' .$pdf_name , file_get_contents($pdf_file));
                $pdf_names[] = $pdf_name;
            }
        }

        $project = new Project();
        $project->title = $request->title;
        $project->description = $request->description;
        $project->images = $image_names;
        $project->files = $pdf_names;
        $project->start_date = $request->start_date;
        $project->deadline = $request->deadline;
        $project->priority = $request->priority;
        $project->status = $request->status;
        $project->save();

        $project->leaders()->sync($request->leaders);
        $project->members()->sync($request->members);

        return redirect()->route('project.index')->with('create','Project data successfully created!');
    }

    public function edit($id)
    {
        if(!auth()->user()->can('edit_project')){
            abort(403,'Unauthorized Action');
        }
        $project = Project::findOrFail($id);
        $users = User::all();
        return view('project.edit',compact('project','users'));
    }

    public function update($id,UpdateProject $request)
    {   
        if(!auth()->user()->can('edit_project')){
            abort(403,'Unauthorized Action');
        }

        $project = Project::findOrFail($id);

        $image_names = $project->images;
        if(request()->hasFile('images')){
            $image_names = [];
            $image_files = $request->file('images');
            foreach($image_files as $image_file){
                $image_name = time(). '.' . uniqid() . '.' .$image_file->getClientOriginalExtension();
                Storage::disk('public')->put('project/image/' .$image_name , file_get_contents($image_file));
                $image_names[] = $image_name;
            }
        }

        $pdf_names = $project->files;
        if(request()->hasFile('files')){
            $pdf_names = [];
            $pdf_files = $request->file('files');
            foreach($pdf_files as $pdf_file){
                $pdf_name = time(). '.' . uniqid() . '.' .$pdf_file->getClientOriginalExtension();
                Storage::disk('public')->put('project/pdf/' .$pdf_name , file_get_contents($pdf_file));
                $pdf_names[] = $pdf_name;
            }
        }

        
        $project->title = $request->title;
        $project->description = $request->description;
        $project->images = $image_names;
        $project->files = $pdf_names;
        $project->start_date = $request->start_date;
        $project->deadline = $request->deadline;
        $project->priority = $request->priority;
        $project->status = $request->status;
        $project->update();

        $project->leaders()->sync($request->leaders);
        $project->members()->sync($request->members);

        return redirect()->route('project.index')->with('update','Project data successfully updated!');
    }
    
    public function show($id)
    {
        $project = Project::findOrFail($id);
        return view('project.show',compact('project'));
    }

    public function destroy($id)
    {
        if(!auth()->user()->can('delete_project')){
            abort(403,'Unauthorized Action');
        }
        $project = Project::findOrFail($id);
        $project->delete();

        return 'success';
    }

}
