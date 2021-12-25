<?php

namespace App\Http\Controllers;

use App\Salary;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Requests\StoreSalary;
use App\Http\Requests\UpdateSalary;
use App\User;

class SalaryController extends Controller
{
    
    public function index()
    {
        if(!auth()->user()->can('view_salary')){
            abort(403,'Unauthorized Action');
        }
        return view('salary.index');
    }
    public function ssd(Request $request)
    {
        if(!auth()->user()->can('view_salary')){
            abort(403,'Unauthorized Action');
        }
        
        $salarys = Salary::with('employee');
        return Datatables::of($salarys)
        ->filterColumn('employee_name',function($user,$keyword){
            $user->whereHas('employee',function($name) use ($keyword){
                $name->where('name','like','%'.$keyword.'%');
            });
        })
        ->filterColumn('month',function($user,$keyword){
            $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            $user->whereHas('employee',function($each) use ($keyword,$months){
                $each->month = in_array($keyword,$months);
             });
        })
        ->editColumn('amount',function($each){
            return number_format($each->amount);
        })
        ->editColumn('month',function($each){
            return date("F", mktime($each->month));
        })
        ->addColumn('employee_name',function($each){
            return $each->employee ? $each->employee->name : '';
        })
        ->addColumn('plus-icon',function($each){
            return null;
        })
        ->addColumn('action',function($each){
            $edit_button = '';
            $delete_button = '';

            if(auth()->user()->can('edit_salary')){
                $edit_button = '<a href="'.route('salary.edit',$each->id).'" class="text-warning"><i class="far fa-edit"></i></a>';
            }
            if(auth()->user()->can('delete_salary')){
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
        if(!auth()->user()->can('create_salary')){
            abort(403,'Unauthorized Action');
        }
        $employees = User::all();
        return view('salary.create',compact('employees'));
    }

    public function store(StoreSalary $request)
    {
        if(!auth()->user()->can('create_salary')){
            abort(403,'Unauthorized Action');
        }

        $salary = new Salary();
        $salary->user_id = $request->user_id;
        $salary->month = $request->month;
        $salary->year = $request->year;
        $salary->amount = $request->amount;
        $salary->save();

        return redirect()->route('salary.index')->with('create','salary data successfully created!');
    }

    public function edit($id)
    {
        if(!auth()->user()->can('edit_salary')){
            abort(403,'Unauthorized Action');
        }
        $salary = Salary::findOrFail($id);
        $employee = User::findOrFail($salary->user_id);
        return view('salary.edit',compact('salary','employee'));
    }

    public function update($id,UpdateSalary $request)
    {   
        if(!auth()->user()->can('edit_salary')){
            abort(403,'Unauthorized Action');
        }

        $salary = Salary::findOrFail($id);
        $salary->user_id = $request->user_id;
        $salary->month = $request->month;
        $salary->year = $request->year;
        $salary->amount = $request->amount;
        $salary->update();

        return redirect()->route('salary.index')->with('update','salary data successfully updated!');
    }

    public function destroy($id)
    {
        if(!auth()->user()->can('delete_salary')){
            abort(403,'Unauthorized Action');
        }
        $salary = Salary::findOrFail($id);
        $salary->delete();

        return 'success';
    }

}
