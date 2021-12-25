<?php

namespace App\Http\Controllers;


use App\Attendance;
use App\CheckinCheckout;
use App\CompanySetting;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Requests\StoreAttendance;
use App\Http\Requests\UpdateAttendance;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AttendanceController extends Controller
{
    
    public function index()
    {
        if(!auth()->user()->can('view_attendance')){
            abort(403,'Unauthorized Action');
        }
        return view('attendance.index');
    }
    public function ssd(Request $request)
    {
        if(!auth()->user()->can('view_attendance')){
            abort(403,'Unauthorized Action');
        }

        $attendances = CheckinCheckout::with('employee');
        return Datatables::of($attendances)

        ->filterColumn('employee_name',function($each, $keyword) {
            $each->whereHas('employee', function($user) use ($keyword){
                $user->where('name','like', '%' .$keyword. '%');
            });
        })
        ->addColumn('employee_name',function($each){
            return $each->employee ?  $each->employee->name : '-';
        })
        ->addColumn('plus-icon',function($each){
            return null;
        })
        ->addColumn('action',function($each){
            $edit_button = '';
            $delete_button = '';

            if(auth()->user()->can('edit_attendance')){
                $edit_button = '<a href="'.route('attendance.edit',$each->id).'" class="text-warning"><i class="far fa-edit"></i></a>';
            }
            if(auth()->user()->can('delete_attendance')){
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
        if(!auth()->user()->can('create_attendance')){
            abort(403,'Unauthorized Action');
        }
        $employees = User::orderBy('name','desc')->get();
        return view('attendance.create',compact('employees'));
    }

    public function store(StoreAttendance $request)
    {
        if(!auth()->user()->can('create_attendance')){
            abort(403,'Unauthorized Action');
        }

        if(CheckinCheckout::where('user_id',$request->user_id)->where('date',$request->date)->exists()){
            return back()->withErrors(['fail'=>'Already Filled Attendance'])->withInput();
        }

        $attendance = new CheckinCheckout();
        $attendance->user_id = $request->user_id;
        $attendance->date = $request->date;
        $attendance->checkin_time = $request->date. ' ' .$request->checkin_time;
        $attendance->checkout_time = $request->date. ' ' .$request->checkout_time;
        $attendance->save();

        return redirect()->route('attendance.index')->with('create','attendance data successfully created!');
    }

    public function edit($id)
    {


        if(!auth()->user()->can('edit_attendance')){
            abort(403,'Unauthorized Action');
        }
        $employees = User::orderBy('name','desc')->get();
        $attendance = CheckinCheckout::findOrFail($id);
        return view('attendance.edit',compact('attendance','employees'));
    }

    public function update($id,UpdateAttendance $request)
    {   
        if(!auth()->user()->can('edit_attendance')){
            abort(403,'Unauthorized Action');
        }

        $attendance = CheckinCheckout::findOrFail($id);

        if(CheckinCheckout::where('user_id',$request->user_id)
        ->where('date',$request->date)
        ->where('id','!=',$attendance->id)
        ->exists()
        ){
            return back()->withErrors(['fail'=>'Already Filled Attendance'])->withInput();
        }

        $attendance->user_id = $request->user_id;
        $attendance->date = $request->date;
        $attendance->checkin_time = $request->date. ' ' .$request->checkin_time;
        $attendance->checkout_time = $request->date. ' ' .$request->checkout_time;
        $attendance->update();

        return redirect()->route('attendance.index')->with('update','attendance data successfully updated!');
    }

    public function destroy($id)
    {
        if(!auth()->user()->can('delete_attendance')){
            abort(403,'Unauthorized Action');
        }
        $attendance = CheckinCheckout::findOrFail($id);
        $attendance->delete();

        return 'success';
    }
    public function overview(Request $request)
    {
        if(!auth()->user()->can('view_attendance')){
            abort(403,'Unauthorized Action');
        }
        return view('attendance.overview');
    }
    public function overviewTable(Request $request)
    {
        if(!auth()->user()->can('view_attendance')){
            abort(403,'Unauthorized Action');
        }
        
        $name = $request->name;
        $month = $request->month;
        $year = $request->year;

        $startMonth = $year . '-'. $month . '-01';
        $endMonth = Carbon::parse($startMonth)->endOfMonth()->format("Y-m-d");

        $periods = new CarbonPeriod($startMonth , $endMonth);
        $employees = User::OrderBy('name')->where('name','like','%'.$name.'%')->get();
        $attendances = CheckinCheckout::whereMonth('date',$month)->whereYear('date',$year)->get();
        $setting = CompanySetting::find(1);
        return view('components.attendance_view_table',compact('periods','employees','attendances','setting'))->render();
    }

}
