<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreRole;
use Yajra\Datatables\Datatables;
use App\Http\Requests\UpdateRole;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    
    public function index()
    {
        if(!auth()->user()->can('view_role')){
            abort('403','Unauthorized Action');
        }
        return view('role.index');
    }
    public function ssd(Request $request)
    {
        if(!auth()->user()->can('view_role')){
            abort('403','Unauthorized Action');
        }

        $roles = Role::query();
        return Datatables::of($roles)
        ->addColumn('permissions',function($each){
            $output = '';
            foreach($each->permissions as $permission){
                $output .="<span class='badge badge-pill badge-light m-1'>".$permission->name."</span>";
            }
            return $output;
        })
        ->addColumn('plus-icon',function($each){
            return null;
        })
        ->addColumn('action',function($each){
            $edit_button = '';
            $delete_button = '';

             if(auth()->user()->can('edit_role')){
                $edit_button = '<a href="'.route('role.edit',$each->id).'" class="text-warning"><i class="far fa-edit"></i></a>';
             }

             if(auth()->user()->can('delete_role')){
               $delete_button = '<a href="#" class="text-danger delete-btn" data-id="'.$each->id.'"><i class="fas fa-trash-alt"></i></a>';
             }
            
            return "<div class='action-icon'>".$edit_button . $delete_button. 
            "</div>";
        })
        ->rawColumns(['permissions','action'])
        ->make(true);
    }

    public function create()
    {
        if(!auth()->user()->can('create_role')){
            abort('403','Unauthorized Action');
        }

        $permissions = Permission::orderBy('name','asc')->get();
        return view('role.create',compact('permissions'));
    }

    public function store(StoreRole $request)
    {
         if(!auth()->user()->can('create_role')){
            abort('403','Unauthorized Action');
        }

        $role = new role();
        $role->name = $request->name;
        $role->save();

        $role->givePermissionTo($request->permissions);

        return redirect()->route('role.index')->with('create','role data successfully created!');
    }

    public function edit($id)
    {
         if(!auth()->user()->can('edit_role')){
            abort('403','Unauthorized Action');
        }

        $role = role::findOrFail($id);
        $permissions = Permission::orderBy('created_at','desc')->get();
        $old_permissions = $role->permissions->pluck('id')->all();
        return view('role.edit',compact('role','permissions','old_permissions'));
    }

    public function update($id,UpdateRole $request)
    {   
         if(!auth()->user()->can('edit_role')){
            abort('403','Unauthorized Action');
        }
        $role = role::findOrFail($id);
        $role->name = $request->name;
        $role->update();

        $old_permissions = $role->permissions->pluck('name')->all();
        $role->revokePermissionTo($old_permissions);
        $role->givePermissionTo($request->permissions);

        return redirect()->route('role.index')->with('update','role data successfully updated!');
    }

    public function destroy($id)
    {
         if(!auth()->user()->can('delete_role')){
            abort('403','Unauthorized Action');
        }
        $role = role::findOrFail($id);
        $role->delete();

        return 'success';
    }

}
