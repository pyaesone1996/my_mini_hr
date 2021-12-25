<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Requests\StorePermission;
use App\Http\Requests\UpdatePermission;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    
    public function index()
    {
        if(!auth()->user()->can('view_permission')){
            abort('403','Unauthorized Action');
        }
        return view('permission.index');
    }
    public function ssd(Request $request)
    {
        if(!auth()->user()->can('view_permission')){
            abort('403','Unauthorized Action');
        }

        $permissions = Permission::query();
        return Datatables::of($permissions)
        ->addColumn('plus-icon',function($each){
            return null;
        })
        ->editColumn('created_at',function($each){
           return $each->created_at->toDateTimeString();
        })
        ->editColumn('updated_at',function($each){
           return $each->updated_at->toDateTimeString();
        })
        ->addColumn('action',function($each){
            $edit_button = '';
            $delete_button = '';

            if(auth()->user()->can('edit_permission')){
                 $edit_button = '<a href="'.route('permission.edit',$each->id).'" class="text-warning"><i class="far fa-edit"></i></a>';
            }
            if(auth()->user()->can('delete_permission')){
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
        if(!auth()->user()->can('create_permission')){
            abort('403','Unauthorized Action');
        }
        return view('permission.create');
    }

    public function store(StorePermission $request)
    {
        if(!auth()->user()->can('create_permission')){
            abort('403','Unauthorized Action');
        }
        $permission = new permission();
        $permission->name = $request->name;
        $permission->save();

        return redirect()->route('permission.index')->with('create','permission data successfully created!');
    }

    public function edit($id)
    {
        if(!auth()->user()->can('edit_permission')){
            abort('403','Unauthorized Action');
        }
        $permission = permission::findOrFail($id);
        return view('permission.edit',compact('permission'));
    }

    public function update($id,UpdatePermission $request)
    {   
        if(!auth()->user()->can('edit_permission')){
            abort('403','Unauthorized Action');
        }
        $permission = permission::findOrFail($id);
        $permission->name = $request->name;
        $permission->update();

        return redirect()->route('permission.index')->with('update','permission data successfully updated!');
    }

    public function destroy($id)
    {
        if(!auth()->user()->can('delete_permission')){
            abort('403','Unauthorized Action');
        }
        $permission = permission::findOrFail($id);
        $permission->delete();

        return 'success';
    }

}
