<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <th class="text-center">Employees</th>
            <th class="text-center">Role</th>
            <th class="text-center">Day of Month</th>
            <th class="text-center">Working Day</th>
            <th class="text-center">Off Day</th>
            <th class="text-center">Attendance</th>
            <th class="text-center">Leave</th>
            <th class="text-center">Per Day (MMK)</th>
            <th class="text-center">Net Total</th>
        </thead>
        @foreach ($employees as $employee)
            @php 
                $attendanceDays = 0; 
                $salary = collect($employee->salaries)->where('month',$month)->where('year',$year)->first();
                $perDays = $salary ? $salary->amount / $workingDays : 0 ;
            @endphp
        @foreach ($periods as $period)
                @php
                    $office_start_time = $period->format("Y-m-d") . ' ' . $setting->office_start_time;
                    $office_end_time = $period->format("Y-m-d") . ' ' . $setting->office_end_time;
                    $break_start_time = $period->format("Y-m-d") . ' ' . $setting->break_start_time;
                    $break_end_time = $period->format("Y-m-d") . ' ' . $setting->break_end_time;
                    
                    
                    $attendance = collect($payrolls)
                    ->where('user_id',$employee->id)
                    ->where('date',$period->format("Y-m-d"))
                    ->first();
                    
                    if($attendance)
                    {
                        if(!is_null($attendance->checkin_time)){
                            if($attendance->checkin_time < $office_start_time)
                            {
                                $attendanceDays += 0.5;
                            }
                            else if($attendance->checkin_time > $office_start_time && $attendance->checkin_time < $break_start_time)
                            {
                                $attendanceDays += 0.5;
                            }else
                            {
                                $attendanceDays += 0;
                            }
                        }else {
                             $attendanceDays += 0;
                        }

                        if(!is_null($attendance->checkout_time)){
                            if($attendance->checkout_time < $break_end_time ){
                                $attendanceDays += 0;
                            }elseif ($attendance->checkout_time > $break_end_time && $attendance->checkout_time < $office_end_time) {
                                $attendanceDays += 0.5;
                            }else {
                                $attendanceDays += 0.5;
                            }
                        }else {
                             $attendanceDays += 0;
                        }
                    }
                @endphp
            @endforeach
                @php 
                    $leaveDays = $workingDays - $attendanceDays ; 
                    $net_total = $workingDays * $perDays ; 
                @endphp
                <tr>
                    <td class="text-center">{{ $employee->name}}</td>
                    <td class="text-center">{{ $employee->roles->pluck('name')->first()}}</td>
                    <td class="text-center">{{ $dayInMonth }}</td>
                    <td class="text-center">{{ $workingDays }}</td>
                    <td class="text-center">{{ $offDays }}</td>
                    <td class="text-center">{{ $attendanceDays }}</td>
                    <td class="text-center">{{ $leaveDays }}</td>
                    <td class="text-center">{{ number_format($perDays) }}</td>
                    <td class="text-center">{{ number_format($net_total) . " KS"}}</td>
                </tr>
        @endforeach
    </table>
</div>