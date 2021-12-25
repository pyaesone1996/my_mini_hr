@extends('layouts.app')
@section('title','Permission Create')

@section('content')
   <div class="card">
       <div class="card-body">
           <form action="{{ route('permission.store') }}" 
                 method="POST" 
                 autocomplete="off" 
                 id="create-form">
            @csrf
            <div class="md-form">
                <label for="name">Permission Name</label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       class="form-control" 
                       value="{{ old('name') }}"
                       autofocus
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
   {!! JsValidator::formRequest('App\Http\Requests\Storepermission' ,'#create-form') !!}
   <script>
       $(document).ready(function(){
          
        });
   </script>
@endsection