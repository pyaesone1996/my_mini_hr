@extends('layouts.app')
@section('title','Role Create')

@section('content')
   <div class="card">
       <div class="card-body">
           <form action="{{ route('role.store') }}" 
                 method="POST" 
                 autocomplete="off" 
                 id="create-form">
            @csrf
            <div class="md-form">
                <label for="name">Role Name</label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       class="form-control" 
                       value="{{ old('name') }}"
                >
            </div>

            <label>Permissions</label>
            <div class="row my-2">
            @foreach ($permissions as $permission)
                    <div class="col-md-3 col-6 my-1">
                        <div class="custom-control custom-checkbox">
                            <input name="permissions[]" type="checkbox" class="custom-control-input" id="{{ $permission->name }}" value="{{ $permission->name }}">
                            <label class="custom-control-label" for="{{ $permission->name }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-sm btn-theme">Confirm</button>
            </div>

           </form>
       </div>
   </div>
@endsection

@section('extra-js')
   {!! JsValidator::formRequest('App\Http\Requests\StoreRole' ,'#create-form') !!}
   <script>
       $(document).ready(function(){
          
        });
   </script>
@endsection