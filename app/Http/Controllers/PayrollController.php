<?php

namespace App\Http\Controllers;


use App\payroll;
use App\CheckinCheckout;
use App\CompanySetting;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Requests\Storepayroll;
use App\Http\Requests\Updatepayroll;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PayrollController extends Controller
{
    
    public function payroll(Request $request)
    {
        if(!auth()->user()->can('view_payroll')){
            abort(403,'Unauthorized Action');
        }
        return view('payroll');
    }

    public function payrollTable(Request $request)
    {
        if(!auth()->user()->can('view_payroll')){
            abort(403,'Unauthorized Action');
        }
        
        $name = $request->name;
        $month = $request->month;
        $year = $request->year;

        $startMonth = $year . '-'. $month . '-01';
        $endMonth = Carbon::parse($startMonth)->endOfMonth()->format("Y-m-d");
        
        $dayInMonth = Carbon::parse($startMonth)->daysInMonth;

        $offDays = Carbon::parse($startMonth)->diffInDaysFiltered(function(Carbon $date) {
            return $date->isWeekend();
        }, Carbon::parse($endMonth));

        $workingDays = $dayInMonth - $offDays;

        $periods = new CarbonPeriod($startMonth , $endMonth);
        $employees = User::OrderBy('name')->where('name','like','%'.$name.'%')->get();
        $payrolls = CheckinCheckout::whereMonth('date',$month)->whereYear('date',$year)->get();
        $setting = CompanySetting::find(1);

        return view('components.payroll_table',compact('month','year','periods','employees','payrolls','setting','dayInMonth','offDays','workingDays'))->render();
    }

}
