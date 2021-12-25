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

class MyProjectController extends Controller
{
    
    public function index()
    {
        return view('my_project');
    }

    public function show($id)
    {
        $project = Project::with('leaders','members','tasks')
        ->where('id',$id)
        ->where(function($query){
            $query->whereHas('leaders',function($q1) {
            $q1->where('user_id',auth()->id());
        })->orWhereHas('members',function($q1){
            $q1->where('user_id',auth()->id());
        });
        })->firstOrFail();
        
        return view('my_project_show',compact('project'));
    }

    public function ssd(Request $request)
    {
        $projects = Project::with('leaders','members')
            ->whereHas('leaders',function($query) {
            $query->where('user_id',auth()->id());
        })
        ->orWhereHas('members',function($query){
            $query->where('user_id',auth()->id());
        });
        
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

            if(auth()->user()->can('view_project')){
                $info_button = '<a href="'.route('my-project.show',$each->id).'" class="text-info"><i class="fas fa-info-circle"></i></a>';
            }
           
            return "<div class='action-icon'>". $info_button .
            "</div>";
        })
        ->rawColumns(['leaders','members','action','priority','status'])
        ->make(true);
    }


}
