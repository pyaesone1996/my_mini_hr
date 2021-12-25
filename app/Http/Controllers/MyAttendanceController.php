<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\CompanySetting;
use App\CheckinCheckout;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class MyAttendanceController extends Controller
{
    public function ssd(Request $request)
    {
       
        $attendances = CheckinCheckout::with('employee')->where('user_id',auth()->id());

        if($request->month){
           $attendances = $attendances->whereMonth('date',$request->month);
        }
        if($request->year){
           $attendances = $attendances->whereYear('date',$request->year);
        } 
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

        ->rawColumns([])
        ->make(true);
    }

    public function overviewTable(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        $startMonth = $year . '-'. $month . '-01';
        $endMonth = Carbon::parse($startMonth)->endOfMonth()->format("Y-m-d");

        $periods = new CarbonPeriod($startMonth , $endMonth);
        $employees = User::OrderBy('name')->where('id',auth()->id())->get();
        $attendances = CheckinCheckout::whereMonth('date',$month)->whereYear('date',$year)->get();
        $setting = CompanySetting::find(1);
        return view('components.attendance_view_table',compact('periods','employees','attendances','setting'))->render();
    }

}
