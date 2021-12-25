@extends('layouts.app')
@section('title','Edit Project')

@section('extra-css')
    <style>
        .pdf
        {
            color: red;
            border: 1px solid #ddd;
            padding: 3px 5px;
            border-radius: 5px;
            margin:0px 3px;
            font-size: 24px;
        }
    </style>
@endsection

@section('content')
   <div class="card">
       <div class="card-body">
           <form action="{{ route('project.update',$project->id) }}" method="POST" autocomplete="off" id="edit-form">
            @method('PUT')
            @csrf
           
            <div class="md-form">
                <label for="title">Title</label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       class="form-control" 
                       value="{{ old('title',$project->title) }}"
                >
            </div>

            <div class="md-form">
                <label for="description">Description</label>
                <textarea type="text" 
                       name="description" 
                       id="description" 
                       class="form-control w-100 md-textarea" 
                       rows="4"
                >{{ $project->description }}</textarea>
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
                <div class="preview_image my-2">
                    @if ($project->images)
                        @foreach ($project->images as $image)
                            <img src="{{ asset('storage/project/image/'.$image) }}" alt="{{ config('app.name')}}" style="height:100px;object-fit:cover;">
                        @endforeach
                    @endif
                </div>
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
                <div>
                    @if ($project->files)
                        @foreach ($project->files as $file)
                            <a href="{{ asset('storage/project/pdf/'.$file) }}" target="_blank" class="d-block mt-2">
                                <i class="fas fa-file-pdf pdf"></i>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="md-form">
                <label for="start_date">Start Date</label>
                <input type="text" 
                       name="start_date" 
                       id="start_date" 
                       class="form-control date_picker"
                       value="{{ old('start_date',$project->start_date) }}"
                >
            </div>

            <div class="md-form">
                <label for="deadline">Deadline</label>
                <input type="text" 
                       name="deadline" 
                       id="deadline" 
                       class="form-control date_picker"
                       value="{{ old('deadline',$project->deadline) }}"
                >
            </div>
            <div class="form-group">
                <label for="leaders">Leaders</label>
                <select name="leaders[]" id="leaders" class="form-control select-aries" multiple>
                    <option value="null"> --- Choose leaders --- </option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @if($project->leaders->pluck('id')->contains($user->id)) selected @endif>{{ $user->name }} </option>
                   @endforeach
                </select>
            </div>

             <div class="form-group">
                <label for="members">Members</label>
                <select name="members[]" id="members" class="form-control select-aries" multiple>
                    <option value="null"> --- Choose member --- </option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}" @if($project->members->pluck('id')->contains($user->id)) selected @endif >{{ $user->name }} </option>
                   @endforeach
                </select>
            </div>

             <div class="form-group">
                <label for="priority">Priority</label>
                <select name="priority" id="priority" class="form-control select-aries">
                    <option value=""> --- Choose Priority --- </option>
                    <option value="high" @if ($project->priority == 'high') selected @endif >Hight</option>
                    <option value="middle" @if ($project->priority == 'middle') selected @endif >Middle</option>
                    <option value="low" @if ($project->priority == 'low') selected @endif >Low</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control select-aries">
                    <option value=""> --- Choose Status --- </option>
                    <option value="pending" @if ($project->status == 'pending') selected @endif >Pending</option>
                    <option value="in_progress" @if ($project->status == 'in_progress') selected @endif >In Progress</option>
                    <option value="complete" @if ($project->status == 'complete') selected @endif >Complete</option>
                </select>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-sm btn-theme">Update</button>
            </div>

           </form>
       </div>
   </div>
@endsection

@section('extra-js')
   {!! JsValidator::formRequest('App\Http\Requests\UpdateProject' ,'#edit-form') !!}
   <script>
       $(document).ready(function(){
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
        });
   </script>
@endsection