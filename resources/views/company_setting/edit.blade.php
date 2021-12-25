@extends('layouts.app')
@section('title','Edit Company Setting')

@section('content')
   <div class="card">
       <div class="card-body">
           <form action="{{ route('company_setting.update',1) }}"method="POST" autocomplete="on" id="edit-form">
            @method('PUT')
            @csrf
            <div class="md-form">
                <label for="employee_id">Company Name</label>
                <input type="text" 
                       name="company_name" 
                       id="company_name" 
                       class="form-control" 
                       value="{{ $setting->company_name }}"
                >
            </div>

            <div class="md-form">
                <label for="company_email">Company Phone</label>
                <input type="text" 
                       company_email="" 
                       id="company_email" class="form-control company_email" 
                       value="{{ $setting->company_email }}">
            </div>

            <div class="md-form">
                <label for="company_phone">Company Phone</label>
                <input type="number" 
                       name="company_phone" 
                       id="company_phone" 
                       class="form-control"
                       value="{{ $setting->company_phone }}">
            </div>

            <div class="md-form">
               <p class="company_address text-muted small">Company Address</p>
               <textarea name="company_address" id="company_address" class="w-100 pt-3" rows="6">{{ $setting->company_address }}
               </textarea>
                
            </div>

            <div class="md-form">
                <label for="office_start_time">Office Time From</label>
                <input type="text" 
                       name="office_start_time" 
                       id="office_start_time" 
                       class="form-control time-picker"
                       value="{{ $setting->office_start_time }}">
            </div>

            <div class="md-form">
                <label for="office_end_time">Office Time To</label>
                <input type="text" 
                       name="office_end_time" 
                       id="office_end_time" 
                       class="form-control time-picker"
                       value="{{ $setting->office_end_time }}">
            </div>

            <div class="md-form">
                <label for="break_start_time">Break Time From</label>
                <input type="text" 
                       name="break_start_time" 
                       id="break_start_time" 
                       class="form-control time-picker"
                       value="{{ $setting->break_start_time }}">
            </div>

            <div class="md-form">
                <label for="break_end_time">Break Time To</label>
                <input type="text" 
                       name="break_end_time" 
                       id="break_end_time" 
                       class="form-control time-picker"
                       value="{{ $setting->break_end_time }}">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-sm btn-theme">Update</button>
            </div>

           </form>
       </div>
   </div>
@endsection

@section('extra-js')
   {!! JsValidator::formRequest('App\Http\Requests\UpdateCompanySetting' ,'#edit-form') !!}
   <script>
       $(document).ready(function(){
            $('.time-picker').daterangepicker({
                "singleDatePicker" :true,
                "autoApply": true,
                "timePicker": true,
                "timePicker24Hour": true,
                "locale": {
                    "format": "HH:mm",
                } 
            }).on('show.daterangepicker',function(ev,picker) {
                picker.container.find('.calendar-table').hide();
            });
        });
   </script>
@endsection