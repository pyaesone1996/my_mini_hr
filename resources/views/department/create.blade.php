@extends('layouts.app')
@section('title','department Create')

@section('content')
   <div class="card">
       <div class="card-body">
           <form action="{{ route('department.store') }}" 
                 method="POST" 
                 autocomplete="off" 
                 id="create-form">
            @csrf
            <div class="md-form">
                <label for="name">Department Name</label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       class="form-control" 
                       value="{{ old('name') }}"
                >
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-sm btn-theme">Confirm</button>
            </div>

           </form>
       </div>
   </div>
@endsection

@section('extra-js')
   {!! JsValidator::formRequest('App\Http\Requests\StoreDepartment' ,'#create-form') !!}
   <script>
       $(document).ready(function(){
          
        });
   </script>
@endsection