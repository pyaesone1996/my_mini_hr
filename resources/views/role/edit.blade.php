@extends('layouts.app')
@section('title','Edit Role')

@section('content')
   <div class="card">
       <div class="card-body">
           <form action="{{ route('role.update',$role->id) }}" method="POST" autocomplete="off" id="edit-form">
            @method('PUT')
            @csrf
           
            <div class="md-form">
                <label for="name">Name</label>
                <input type="text" 
                       name="name" 
                       id="name" class="form-control" 
                       value="{{ $role->name }}">
            </div>
            
            <label>Permissions</label>
            <div class="row my-2">
                @foreach ($permissions as $permission)
                    <div class="col-md-3 col-6 my-1">
                        <div class="custom-control custom-checkbox">
                            <input name="permissions[]" 
                                   type="checkbox" 
                                   class="custom-control-input"       
                                   id="{{ $permission->name }}" 
                                   value="{{ $permission->name }}"
                                @if (in_array($permission->id,$old_permissions))
                                    checked
                                @endif
                            >
                            <label class="custom-control-label" for="{{ $permission->name }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-sm btn-theme">Update</button>
            </div>

           </form>
       </div>
   </div>
@endsection

@section('extra-js')
   {!! JsValidator::formRequest('App\Http\Requests\Updaterole' ,'#edit-form') !!}
   <script>
       $(document).ready(function(){
    
        });
   </script>
@endsection