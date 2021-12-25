@extends('layouts.app')
@section('title','Attendance Edit')

@section('content')
   <div class="card">
       <div class="card-body">
           @include('layouts.errors')
           <form action="{{ route('attendance.update',$attendance->id) }}" 
                 method="POST" 
                 autocomplete="off" 
                 id="update-form">
            @csrf
            @method('PUT')

            <div class="md-form">
                <p class="text-muted">Employee</p>
                <select name="user_id" class="form-control select-aries">
                    <option value="" >Choose Employee</option>
                    @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" @if(old('user_id',$attendance->user_id ) == $employee->id) selected @endif>{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md-form">
                <label for="date">Date</label>
                <input type="text" 
                       name="date" 
                       id="date" 
                       class="form-control date"
                       value="{{ old('date',$attendance->date) }}">
            </div>

            <div class="md-form">
                <label for="checkin">Check In</label>
                <input type="text" 
                       name="checkin_time" 
                       id="checkin" 
                       class="form-control time-picker"
                       value="{{ old('checkin_time',Carbon\carbon::parse($attendance->checkin_time)->format('H:i:s')) }}">
            </div>

            <div class="md-form">
                <label for="date">Check Out</label>
                <input type="text" 
                       name="checkout_time" 
                       id="date" 
                       class="form-control time-picker"
                       value="{{ old('checkout_time',Carbon\carbon::parse($attendance->checkout_time)->format('H:i:s')) }}">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-sm btn-theme">Confirm</button>
            </div>

           </form>
       </div>
   </div>
@endsection

@section('extra-js')
   {!! JsValidator::formRequest('App\Http\Requests\UpdateAttendance' ,'#update-form') !!}
   <script>
       $(document).ready(function(){
            $('.date').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "drops": "down",
                "autoApply": true,
                "locale": {
                    "format": "YYYY-MM-DD",
                } 
            });

            $('.time-picker').daterangepicker({
                "singleDatePicker" :true,
                "autoApply": true,
                "timePicker": true,
                "timePicker24Hour": true,
                "timePickerSeconds": true,
                "locale": {
                    "format": "HH:mm:ss",
                } 
            }).on('show.daterangepicker',function(ev,picker) {
                picker.container.find('.calendar-table').hide();
            });
          
        });
   </script>
@endsection