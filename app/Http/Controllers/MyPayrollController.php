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

class MyPayrollController extends Controller
{
    
    public function ssd(Request $request)
    {
        return view('payroll');
    }

    public function payrollTable(Request $request)
    {
      
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
        $employees = User::OrderBy('name')->where('id',auth()->id())->get();
        $payrolls = CheckinCheckout::whereMonth('date',$month)->whereYear('date',$year)->get();
        $setting = CompanySetting::find(1);

        return view('components.payroll_table',compact('month','year','periods','employees','payrolls','setting','dayInMonth','offDays','workingDays'))->render();
    }

}
