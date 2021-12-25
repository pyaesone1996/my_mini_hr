<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Department;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreEmployee;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateEmployee;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        if(!auth()->user()->can('view_employee')){
            abort('403','Unauthorized Action');
        }

        return view('employee.index');
    }
    public function ssd(Request $request)
    {
        if(!auth()->user()->can('view_employee')){
            abort('403','Unauthorized Action');
        }
        $employees = User::with('department');
        return Datatables::of($employees)
        ->filterColumn('department_name',function($query,$keyword){
            $query->whereHas('department',function($q) use ($keyword){
                $q->where('name','like','%'.$keyword.'%');
            });
        })
         ->addColumn('role_name',function($each){
            $output ='';
            foreach($each->roles as $role){
                $output .= "<span class='badge badge-pill badge-primary py-1 mt-1 small'>$role->name</span>";
            }
            return $output;
         })
         ->editColumn('profile_image',function($each){
           return '<img src="'.$each->profile().'" alt="'.$each->name.'" class="profile-thumbnail"><p class="my-1">'.$each->name.'</p>';
        })
        ->addColumn('department_name',function($each){
           return $each->department ? $each->department->name : '-';
        })
        ->editColumn('updated_at',function($each){
            return Carbon::parse($each->updated_at)->format('y-m-d H:i:s');
        })
        ->addColumn('plus-icon',function($each){
            return null;
        })
        ->addColumn('action',function($each){

            $edit_button = '';
            $info_button = '';
            $delete_button = '';

            if(auth()->user()->can('edit_employee')){
                $edit_button = '<a href="'.route('employee.edit',$each->id).'" class="text-warning"><i class="far fa-edit"></i></a>';
            }
            if(auth()->user()->can('view_employee')){
                 $info_button = '<a href="'.route('employee.show',$each->id).'" class="text-primary"><i class="fa fa-info-circle"></i></a>';
            }
            if(auth()->user()->can('delete_employee')){
                $delete_button = '<a href="#" class="text-danger delete-btn" data-id="'.$each->id.'"><i class="fas fa-trash-alt"></i></a>';
            }
            return "<div class='action-icon'>".$edit_button . $info_button. $delete_button. 
            "</div>";
        })
        ->editColumn('is_present',function($each){
            if($each->is_present == 1){
                return '<span class="badge badge-success badge-pill py-1">Present</span>';
            }else{
                return '<span class="badge badge-danger badge-pill">Leave</span>';
            }
        })

        ->rawColumns(['is_present','action','profile_image','role_name'])
        ->make(true);
    }

    public function create()
    {
        if(!auth()->user()->can('create_employee')){
            abort('403','Unauthorized Action');
        }

        $departments = Department::orderBy('name')->get();
        $roles = Role::all();
        return view('employee.create',compact('departments','roles'));
    }

    public function store(StoreEmployee $request)
    {
        if(!auth()->user()->can('create_employee')){
            abort('403','Unauthorized Action');
        }

        $profile_image_name = null;
        if(request()->hasFile('profile_image')){
            $profile_image_file = $request->file('profile_image');
            $profile_image_name = time(). '.' . uniqid() . '.' .$profile_image_file->getClientOriginalExtension();
            Storage::disk('public')->put('employee/' .$profile_image_name , file_get_contents($profile_image_file));
        }
        $employee = new User();
        $employee->employee_id = $request->employee_id;
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->nrc_number = $request->nrc_number;
        $employee->gender = $request->gender;
        $employee->birthday = $request->birthday;
        $employee->address = $request->address;
        $employee->department_id = $request->department_id;
        $employee->date_of_joined = $request->date_of_joined;
        $employee->profile_image = $profile_image_name;
        $employee->pin_code = $request->pin_code;
        $employee->password = Hash::make($request->password);
        $employee->save();
        $employee->syncRoles(request()->roles);

        return redirect()->route('employee.index')->with('create','Employee data successfully created!');
    }

    public function edit($id)
    {

        if(!auth()->user()->can('edit_employee')){
            abort('403','Unauthorized Action');
        }
        $employee = User::findOrFail($id);
        $old_roles = $employee->roles->pluck('id')->all();
        $departments = Department::orderBy('name')->get();
        $roles = Role::all();

        return view('employee.edit',compact('employee','old_roles','departments','roles'));
    }

    public function update($id,UpdateEmployee $request)
    {   
        if(!auth()->user()->can('edit_employee')){
            abort('403','Unauthorized Action');
        }

        $employee = User::findOrFail($id);

        $profile_image_name = $employee->profile_image;
        if(request()->hasFile('profile_image')){

            Storage::disk('public')->delete('employee/'.$employee->profile_image );

            $profile_image_file = $request->file('profile_image');
            $profile_image_name = time(). '.' . uniqid() . '.' .$profile_image_file->getClientOriginalExtension();
            Storage::disk('public')->put('employee/' .$profile_image_name , file_get_contents($profile_image_file));
        }

        $employee->employee_id = $request->employee_id;
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->nrc_number = $request->nrc_number;
        $employee->gender = $request->gender;
        $employee->birthday = $request->birthday;
        $employee->address = $request->address;
        $employee->department_id = $request->department_id;
        $employee->date_of_joined = $request->date_of_joined;
        $employee->profile_image = $profile_image_name;
        $employee->pin_code = $request->pin_code ? $request->pin_code : $employee->pin_code;
        $employee->password = $request->password ? Hash::make($request->password) : $employee->password;
        $employee->update();
        $employee->syncRoles(request()->roles);
        return redirect()->route('employee.index')->with('update','Employee data successfully updated!');
    }

    public function show($id)
    {
        if(!auth()->user()->can('view_employee')){
            abort('403','Unauthorized Action');
        }

        $employee = User::findOrFail($id);
        return view('employee.show',compact('employee'));
    }

    public function destroy($id)
    {
        if(!auth()->user()->can('delete_employee')){
            abort('403','Unauthorized Action');
        }
        
        $employee = User::findOrFail($id);
        $employee->delete();

        return 'success';
    }

}