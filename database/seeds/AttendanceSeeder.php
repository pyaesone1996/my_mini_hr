<?php

namespace Database\Seeders;

use App\User;
use App\CheckinCheckout;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users =User::all();
        foreach($users as $user){
            $periods = new CarbonPeriod('2016-01-01', '2021-12-31');
            foreach($periods as $period){
                if($period->format('D') !='Sat' && $period->format('D') != 'Sun'){
                    $attendance = new CheckinCheckout();
                    $attendance->user_id =$user->id;
                    $attendance->date =$period;
                    $attendance->checkin_time = Carbon::parse($period->format('Y-m-d'). " " ."09:20:00")->subMinute(rand(1,55));
                    $attendance->checkout_time = Carbon::parse($period->format('Y-m-d'). " " ."17:55:00")->addMinute(rand(1,55));
                    $attendance->save();
                }
            }
        }   
    }
}
