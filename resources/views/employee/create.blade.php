@extends('layouts.app')
@section('title','Employee Create')

@section('content')
   <div class="card">
       <div class="card-body">
           <form action="{{ route('employee.store') }}" 
                 method="POST" 
                 autocomplete="off" 
                 enctype="multipart/form-data"
                 id="create-form">
            @csrf
            <div class="md-form">
                <label for="employee_id">Employee Id</label>
                <input type="text" 
                       name="employee_id" 
                       id="employee_id" 
                       class="form-control" 
                       value="{{ old('employee_id') }}"
                >
            </div>

            <div class="md-form">
                <label for="name">Name</label>
                <input type="text" 
                       name="name" 
                       id="name" class="form-control" 
                       value="{{ old('name') }}">
            </div>

            <div class="md-form">
                <label for="phone">Phone</label>
                <input type="number" 
                       name="phone" 
                       id="phone" 
                       class="form-control"
                       value="{{ old('phone') }}">
            </div>

            <div class="md-form">
                <label for="email">Email</label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       class="form-control"
                       value="{{ old('email') }}">
            </div>

            <div class="md-form">
                <label for="nrc_number">NRC Number</label>
                <input type="text" 
                       name="nrc_number" 
                       id="nrc_number" 
                       class="form-control"
                       value="{{ old('nrc_number') }}">
            </div>

            <div class="md-form">
                <label for="birthday">Birthday</label>
                <input type="text" 
                       name="birthday" 
                       id="birthday" 
                       class="form-control birthday"
                       value="{{ old('birthday') }}">
            </div>

            <div class="md-form">
                <label for="address">Address</label>
                <textarea name="address" 
                          id="address" 
                          class="md-textarea form-control" 
                          rows="3"
                          value="{{ old('address') }}">
                          
                </textarea>
            </div>

            <div class="md-form">
                <p for="birthday" class="pb-2 mb-0 text-muted">Gender</p>
                <select name="gender" id="gender" class="custom-select">
                    <option selected value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>

            <div class="md-form">
                <p class="pb-2 mb-0 text-muted">Department</p>
                <select name="department_id" class="custom-select">
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md-form">
                <p class="pb-2 mb-0 text-muted">Role </p>
                <select name="roles[]" class="custom-select select-aries " multiple>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md-form">
                <label for="date_of_joined">Date Of Join</label>
                <input type="text" 
                       name="date_of_joined" 
                       id="date_of_joined" 
                       class="form-control date_of_joined"
                       value="{{ old('date_of_joined') }}">
            </div>

            <div class="form-group">
                <label for="profile_image">Employee Profile</label>
                <input type="file" name="profile_image" id="profile_image" class="form-control p-1">
                <div class="preview_image"></div>
            </div>

            <div class="md-form">
                <label for="pin_code">Pin Code</label>
                <input type="number" name="pin_code" id="pin_code" class="form-control">
            </div>

            <div class="md-form">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-sm btn-theme">Confirm</button>
            </div>

           </form>
       </div>
   </div>
@endsection

@section('extra-js')
   {!! JsValidator::formRequest('App\Http\Requests\StoreEmployee' ,'#create-form') !!}
   <script>
       $(document).ready(function(){
            $('.birthday').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "drops": "down",
                "autoApply": true,
                "maxDate":moment(),
                "maxYear": 2001,
                "locale": {
                    "format": "YYYY-MM-DD",
                } 
            });

            $('.date_of_joined').daterangepicker({
                "singleDatePicker": true,
                "autoApply": true,
                "locale": {
                    "format": "YYYY-MM-DD",
                } 
            });

            $('#profile_image').on('change',function()
            {
                var file_length = document.getElementById('profile_image').files.length;
                $('.preview_image').html('');
                for(var i= 0; i < file_length; i++)
                {
                    $('.preview_image').append(`<img src="${URL.createObjectURL(event.target.files[i])}"/>`);
                }
            });
        });
   </script>
@endsection