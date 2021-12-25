<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Requests\StoreDepartment;
use App\Http\Requests\UpdateDepartment;

class DepartmentController extends Controller
{
    
    public function index()
    {
        if(!auth()->user()->can('view_department')){
            abort(403,'Unauthorized Action');
        }
        return view('department.index');
    }
    public function ssd(Request $request)
    {
        if(!auth()->user()->can('view_department')){
            abort(403,'Unauthorized Action');
        }

        $departments = Department::query();
        return Datatables::of($departments)
        ->addColumn('plus-icon',function($each){
            return null;
        })
        ->addColumn('action',function($each){
            $edit_button = '';
            $delete_button = '';

            if(auth()->user()->can('edit_department')){
                $edit_button = '<a href="'.route('department.edit',$each->id).'" class="text-warning"><i class="far fa-edit"></i></a>';
            }
            if(auth()->user()->can('delete_department')){
                $delete_button = '<a href="#" class="text-danger delete-btn" data-id="'.$each->id.'"><i class="fas fa-trash-alt"></i></a>';
            }

            return "<div class='action-icon'>".$edit_button . $delete_button. 
            "</div>";
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function create()
    {
        if(!auth()->user()->can('create_department')){
            abort(403,'Unauthorized Action');
        }
        $departments = Department::orderBy('name')->get();
        return view('department.create',compact('departments'));
    }

    public function store(StoreDepartment $request)
    {
        if(!auth()->user()->can('create_department')){
            abort(403,'Unauthorized Action');
        }

        $department = new Department();
        $department->name = $request->name;
        $department->save();

        return redirect()->route('department.index')->with('create','department data successfully created!');
    }

    public function edit($id)
    {
        if(!auth()->user()->can('edit_department')){
            abort(403,'Unauthorized Action');
        }
        $department = Department::findOrFail($id);
        return view('department.edit',compact('department'));
    }

    public function update($id,UpdateDepartment $request)
    {   
        if(!auth()->user()->can('edit_department')){
            abort(403,'Unauthorized Action');
        }

        $department = Department::findOrFail($id);
        $department->name = $request->name;
        $department->update();

        return redirect()->route('department.index')->with('update','department data successfully updated!');
    }

    public function destroy($id)
    {
        if(!auth()->user()->can('delete_department')){
            abort(403,'Unauthorized Action');
        }
        $department = Department::findOrFail($id);
        $department->delete();

        return 'success';
    }

}
