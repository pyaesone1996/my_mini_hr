@extends('layouts.app')
@section('title','Edit Department')

@section('content')
   <div class="card">
       <div class="card-body">
           <form action="{{ route('department.update',$department->id) }}" method="POST" autocomplete="off" id="edit-form">
            @method('PUT')
            @csrf
           
            <div class="md-form">
                <label for="name">Name</label>
                <input type="text" 
                       name="name" 
                       id="name" class="form-control" 
                       value="{{ $department->name }}">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-sm btn-theme">Update</button>
            </div>

           </form>
       </div>
   </div>
@endsection

@section('extra-js')
   {!! JsValidator::formRequest('App\Http\Requests\UpdateDepartment' ,'#edit-form') !!}
   <script>
       $(document).ready(function(){
    
        });
   </script>
@endsection