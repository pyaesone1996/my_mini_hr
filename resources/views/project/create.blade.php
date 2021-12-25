@extends('layouts.app')
@section('title','Project Create')

@section('content')
   <div class="card">
       <div class="card-body">
           <form action="{{ route('project.store') }}" 
                 method="POST" 
                 autocomplete="off" 
                 enctype="multipart/form-data"
                 id="create-form">
                 @csrf

            <div class="md-form">
                <label for="title">Title</label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       class="form-control" 
                       value="{{ old('title') }}"
                >
            </div>

            <div class="md-form">
                <label for="description">Description</label>
                <textarea type="text" 
                       name="description" 
                       id="description" 
                       class="form-control w-100 md-textarea" 
                       rows="4"
                ></textarea>
            </div>

            <div class="form-group">
                <label for="images">Project Image</label>
                <input type="file" 
                       name="images[]" 
                       id="images" 
                       class="form-control p-1" 
                       multiple
                       accept="image/*"

                >
                <div class="preview_image my-2"></div>
            </div>

            <div class="form-group">
                <label for="files">Files (Only PDF)</label>
                <input type="file" 
                       name="files[]" 
                       id="files" 
                       class="form-control p-1" 
                       multiple
                       accept="application/pdf"
                >
            </div>

            <div class="md-form">
                <label for="start_date">Start Date</label>
                <input type="text" 
                       name="start_date" 
                       id="start_date" 
                       class="form-control date_picker"
                >
            </div>

            <div class="md-form">
                <label for="deadline">Deadline</label>
                <input type="text" 
                       name="deadline" 
                       id="deadline" 
                       class="form-control date_picker"
                >
            </div>

             <div class="form-group">
                <label for="leaders">Leaders</label>
                <select name="leaders[]" id="leaders" class="form-control select-aries" multiple>
                    <option value="null"> --- Choose leaders --- </option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} </option>
                   @endforeach
                </select>
            </div>

             <div class="form-group">
                <label for="members">Members</label>
                <select name="members[]" id="members" class="form-control select-aries" multiple>
                    <option value="null"> --- Choose member --- </option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} </option>
                   @endforeach
                </select>
            </div>

             <div class="form-group">
                <label for="priority">Priority</label>
                <select name="priority" id="priority" class="form-control select-aries">
                    <option value=""> --- Choose Priority --- </option>
                    <option value="high">Hight</option>
                    <option value="middle">Middle</option>
                    <option value="low">Low</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control select-aries">
                    <option value=""> --- Choose Status --- </option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="complete">Complete</option>
                </select>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-sm btn-theme">Confirm</button>
            </div>

           </form>
       </div>
   </div>
@endsection

@section('extra-js')
   {!! JsValidator::formRequest('App\Http\Requests\Storeproject' ,'#create-form') !!}
   <script>
       $(document).ready(function(){
            $('#images').on('change',function()
        {
                var file_length = document.getElementById('images').files.length;
                $('.preview_image').html('');
                for(var i= 0; i < file_length; i++)
                {
                    $('.preview_image').append(`<img src="${URL.createObjectURL(event.target.files[i])}"/>`);
                }
            });

            $('.date_picker').daterangepicker({
                "singleDatePicker": true,
                "autoApply": true,
                "locale": {
                    "format": "YYYY-MM-DD",
                } 
            });

            $('.select-aries').select2({
                allowClear: true,
                theme: "bootstrap4"
            });
        });
   </script>
@endsection