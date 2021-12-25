@extends('layouts.app')
@section('title','Show Project')

@section('extra-css')
    <style>
        .project_images{
            width:100px;
            height:100px;
            object-fit:cover;
            border: 1px dashed #ddd;
            border-radius: 5px;
            padding: 4px;
            margin: 0px 1px;
            cursor: pointer;
        }
        .pdf{
            color: red;
            border: 1px solid #ddd;
            padding: 4px 5px;
            border-radius: 5px;
            margin:0px 3px;
            font-size: 45px;
        }
        .files{
            display: flex;
            width: 50px;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            align-content: flex-start;
            color: #000;
        }

        .alert-warning{
            background: #fff3cd88!important;
        }
        .alert-info{
            background: #d1ecf188!important;
        }
        .alert-success{
            background: #d4edda88!important;
        }
        h5{
            font-size: 18px;
        }

        .task-item{
            background: #fff;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 5px 0px;
        }
        .add_task{
            display: block!important;
            text-align: center;
            padding: 12px;
            margin-top: 30px;
            border-radius: 5px;
            border: 1px solid #ddd;
            background: #fff;
        }
        .select2-container{
            z-index: 9999;
        }
        .select2-container--bootstrap4 .select2-selection--multiple .select2-search__field {
            height: 38px;
            color: #495057;
            line-height: 30px;
            padding: 2px 0px;
            width: 90%!important;
        }
        .profile-thumbnail3{
            width: 30px;
            height: 30px;
            object-fit: contain;
            border: 1px solid #eee;
            padding: 1px;
            margin: 1px;
            border-radius: 6px;
        }
        .dropdown-toggle::after {
            display: none;
        }
        #pendingTasks,
        #inProgressTasks,
        #completeTasks{
            cursor: move;
        }
        .ghosh{
            background: #eee!important;
            border:2px dashed #444 !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-9">
            <div class="card mb-3">
                <div class="card-body">
                    <h4>{{ $project->title }}</h4>
                    <p>Start Date : <span class="text-muted"> {{ $project->start_date }}</span>, Deadline : <span class="text-muted"> {{ $project->deadline }}</span></p>
                        <p class="condition">
                            Priority :
                            @if ($project->priority == 'high')
                                <span class="badge badge-pill badge-danger">High</span>
                            @elseif ($project->priority == 'middle')
                                <span class="badge badge-pill badge-info">Middle</span>
                            @else
                                <span class="badge badge-pill badge-warning">Low</span>
                            @endif
                            ,
                            Status :
                            @if ($project->status == 'pending')
                                <span class="badge badge-pill badge-warning">Pending</span>
                            @elseif ($project->status == 'in_progress')
                                <span class="badge badge-pill badge-info">In Progress</span>
                            @else
                                <span class="badge badge-pill badge-success">Complete</span>
                            @endif
                        </p>

                        <div class="mb-4">
                            <h4 class="mt-2">Description</h4>
                            <p class="text-justify">{{ $project->description }}</p>
                        </div>

                        <div class="mb-4">
                            <h4>Leaders</h4>
                            @foreach ($project->leaders ?? [] as $leader)
                            <img src="{{ $leader->profile() }}" alt="{{ $leader->name."-photo" }}" title="{{ Str::replace(' ','_',$leader->name."profile") }}" class="profile-thumbnail2">
                            @endforeach
                        </div>

                        <div>
                            <h4>Members</h4>
                            @foreach ($project->members ?? [] as $member)
                            <img src="{{ $member->profile() }}" alt="{{ $member->name."-photo" }}" title="{{ Str::replace(' ','_',$member->name."profile") }}"  class="profile-thumbnail2">
                            @endforeach
                        </div>
                </div>
            </div>

        </div>

        <div class="col-sm-3">

           <div class="card mb-3">
                <div class="card-body">
                    <h4>Images</h4>
                    @if ($project->images)
                        <div id="images">
                            @foreach ($project->images as $image)
                                <img src="{{ asset('storage/project/image/'.$image) }}" class="project_images" />
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

           <div class="card mb-3">
                <div class="card-body">
                    <h4>Files</h4>
                    @if ($project->files)
                        @foreach ($project->files as $file)
                        <a href="{{ asset('storage/project/pdf/'.$file) }}" target="_blank" class="files">
                            <i class="fas fa-file-pdf pdf"></i>
                            <p class="mb-0">File {{ $loop->iteration }}</p>
                        </a>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>

        <div class="col-sm-12">
            <div id="taskData"></div>
        </div>
    </div>
    

@endsection

@section('extra-js')
    <script>
         $(document).ready(function(){
           

            new Viewer(document.getElementById('images'));

            var project_id = "{{$project->id}}";
            var leaders = @json($project->leaders);
            var members = @json($project->members);

            function iniateDrag(){
                var pendingTasks = document.getElementById('pendingTasks');
                var inProgressTasks = document.getElementById('inProgressTasks');
                var completeTasks = document.getElementById('completeTasks');

                Sortable.create(pendingTasks,{
                    group:'taskBoard',
                    draggable:".task-item",
                    ghostClass:'ghosh',
                    animation: 200,
                    store :{
                        set: function(sortable){
                            var order = sortable.toArray();
                            localStorage.setItem('pendingTasks', order.join(','));
                            }
                        },
                        onSort: function(evt){
                            setTimeout(function(){
                                var pendingTasks = localStorage.getItem('pendingTasks');
                                $.ajax({
                                    url:`/task-draggable?project_id=${project_id}&pendingTasks=${pendingTasks}`,
                                    type:'GET',
                                    success:function(res){
                                       
                                    }
                                })
                            }, 600);
                        },
                    });

                Sortable.create(inProgressTasks,{
                    group:'taskBoard',
                    draggable:".task-item",
                    ghostClass:'ghosh',
                    animation: 200,
                    store :{
                        set: function(sortable){
                            var order = sortable.toArray();
                            localStorage.setItem('inProgressTasks', order.join(','));
                            }
                        },
                        onSort: function(evt){
                            setTimeout(function(){
                                var inProgressTasks = localStorage.getItem('inProgressTasks');
                                $.ajax({
                                    url:`/task-draggable?project_id=${project_id}&inProgressTasks=${inProgressTasks}`,
                                    type:'GET',
                                    success:function(res){
                                       
                                    }
                                })
                            }, 600);
                        },
                });

                Sortable.create(completeTasks,{
                    group:'taskBoard',
                    draggable:".task-item",
                    ghostClass:'ghosh',
                    animation: 200,
                    store :{
                        set: function(sortable){
                            var order = sortable.toArray();
                            localStorage.setItem('completeTasks', order.join(','));
                            }
                        },
                        onSort: function(evt){
                            setTimeout(function(){
                                var completeTasks = localStorage.getItem('completeTasks');
                                $.ajax({
                                    url:`/task-draggable?project_id=${project_id}&completeTasks=${completeTasks}`,
                                    type:'GET',
                                    success:function(res){
                                        
                                    }
                                })
                            }, 600);
                        },
                });
            }

            function taskData(){
                $.ajax({
                    url:`/task-data?project_id=${project_id}`,
                    type:'GET',
                    success:function(res){
                        $('#taskData').html(res);
                        iniateDrag();
                    }
                })
            }

             taskData();

            $(document).on('click','.pending_task_button',function(event){
                event.preventDefault();

                var task_members = '';
                leaders.forEach(function(leader){
                    task_members += `<option value="${leader.id}">${leader.name}</option>`;
                });
                members.forEach(function(member){
                    task_members += `<option value="${member.id}">${member.name}</option>`;
                });

                Swal.fire({
                    title: 'Add Pending Task?',
                    html:`<form id="pendingTaskForm">
                        <input type="hidden" name="project_id" value="${project_id}">
                        <input type="hidden" name="status" value="pending">
                        <div class="md-form"> 
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" id="title">
                        </div>

                        <div class="md-form"> 
                            <label for="description">Description</label>
                            <textarea  name="description" class="form-control md-textarea" id="description" rows="4"></textarea>
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

                        <div class="form-group text-left">
                            <label for="members">Members</label>
                            <select name="members[]" id="members" class="form-control select-aries" multiple>
                                <option value="" disabled> --- Choose Member --- </option>
                                ${task_members}
                            </select>
                        </div>

                        <div class="form-group text-left">
                            <label for="priority">Priority</label>
                            <select name="priority" id="priority" class="form-control select-aries">
                                <option value=""> --- Choose Priority --- </option>
                                <option value="high">Hight</option>
                                <option value="middle">Middle</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        </form>`,
                    showCancelButton: false,
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                if (result.isConfirmed) {
                    var form_data = $('#pendingTaskForm').serialize();
                    $.ajax({
                        url:'/task',
                        type:'POST',
                        data:form_data,
                        success:function(res){
                           taskData();
                        }
                    });
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
                    theme: "bootstrap4",
                    placeholder:'---Please Choose---'
                });

            });

            $(document).on('click','.in_progress_task_button',function(event){
                event.preventDefault();

                var task_members = '';
                leaders.forEach(function(leader){
                    task_members += `<option value="${leader.id}">${leader.name}</option>`;
                });
                members.forEach(function(member){
                    task_members += `<option value="${member.id}">${member.name}</option>`;
                });

                Swal.fire({
                    title: 'Add Progress Task?',
                    html:`<form id="progressTaskForm">
                        <input type="hidden" name="project_id" value="${project_id}">
                        <input type="hidden" name="status" value="in_progress">
                        <div class="md-form"> 
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" id="title">
                        </div>

                        <div class="md-form"> 
                            <label for="description">Description</label>
                            <textarea  name="description" class="form-control md-textarea" id="description" rows="4"></textarea>
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

                        <div class="form-group text-left">
                            <label for="members">Members</label>
                            <select name="members[]" id="members" class="form-control select-aries" multiple>
                                <option value="" disabled> --- Choose Member --- </option>
                                ${task_members}
                            </select>
                        </div>

                        <div class="form-group text-left">
                            <label for="priority">Priority</label>
                            <select name="priority" id="priority" class="form-control select-aries">
                                <option value=""> --- Choose Priority --- </option>
                                <option value="high">Hight</option>
                                <option value="middle">Middle</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        </form>`,
                    showCancelButton: false,
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                if (result.isConfirmed) {
                    var form_data = $('#progressTaskForm').serialize();
                    $.ajax({
                        url:'/task',
                        type:'POST',
                        data:form_data,
                        success:function(res){
                           taskData();
                        }
                    });
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
                    theme: "bootstrap4",
                    placeholder:'---Please Choose---'
                });

            });

            $(document).on('click','.complete_task_button',function(event){
                event.preventDefault();

                var task_members = '';
                leaders.forEach(function(leader){
                    task_members += `<option value="${leader.id}">${leader.name}</option>`;
                });
                members.forEach(function(member){
                    task_members += `<option value="${member.id}">${member.name}</option>`;
                });

                Swal.fire({
                    title: 'Add Complete Task?',
                    html:`<form id="completeTaskForm">
                        <input type="hidden" name="project_id" value="${project_id}">
                        <input type="hidden" name="status" value="complete">
                        <div class="md-form"> 
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" id="title">
                        </div>

                        <div class="md-form"> 
                            <label for="description">Description</label>
                            <textarea  name="description" class="form-control md-textarea" id="description" rows="4"></textarea>
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

                        <div class="form-group text-left">
                            <label for="members">Members</label>
                            <select name="members[]" id="members" class="form-control select-aries" multiple>
                                <option value="" disabled> --- Choose Member --- </option>
                                ${task_members}
                            </select>
                        </div>

                        <div class="form-group text-left">
                            <label for="priority">Priority</label>
                            <select name="priority" id="priority" class="form-control select-aries">
                                <option value=""> --- Choose Priority --- </option>
                                <option value="high">Hight</option>
                                <option value="middle">Middle</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        </form>`,
                    showCancelButton: false,
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                if (result.isConfirmed) {
                    var form_data = $('#completeTaskForm').serialize();
                    $.ajax({
                        url:'/task',
                        type:'POST',
                        data:form_data,
                        success:function(res){
                           taskData();
                        }
                    });
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
                    theme: "bootstrap4",
                    placeholder:'---Please Choose---'
                });

            });

            $(document).on('click','.edit_task_btn',function(event){
               event.preventDefault();
               
               var task = JSON.parse(atob($(this).data('task')));
               var task_members = JSON.parse(atob($(this).data('task-members')));
            
                var task_members_options = '';
                leaders.forEach(function(leader){
                    task_members_options += `<option value="${leader.id}" ${(task_members.includes(leader.id) ? 'selected' : '')}>${leader.name}</option>`;
                });
                members.forEach(function(member){
                    task_members_options += `<option value="${member.id}" ${(task_members.includes(member.id) ? 'selected' : '')}>${member.name}</option>`;
                });
                
               Swal.fire({
                    title: 'Edit Task?',
                    html:`<form id="editTask">
                        <input type="hidden" name="project_id" value="${project_id}">
                        <div class="md-form"> 
                            <label for="title" class="active">Title</label>
                            <input type="text" name="title" class="form-control" id="title" value="${task.title}">
                        </div>

                        <div class="md-form"> 
                            <label for="description" class="active">Description</label>
                            <textarea  name="description" class="form-control md-textarea" id="description" rows="4">${task.description}</textarea>
                        </div>

                        <div class="md-form">
                            <label for="start_date" class="active">Start Date</label>
                            <input type="text" 
                                name="start_date" 
                                id="start_date" 
                                class="form-control date_picker"
                                value="${task.start_date}"
                            >
                        </div>

                        <div class="md-form">
                            <label for="deadline" class="active">Deadline</label>
                            <input type="text" 
                                name="deadline" 
                                id="deadline" 
                                class="form-control date_picker"
                                value="${task.deadline}"
                            >
                        </div>

                        <div class="form-group text-left">
                            <label for="members" class="active">Members</label>
                            <select name="members[]" id="members" class="form-control select-aries" multiple>
                                <option value="" disabled> --- Choose Member --- </option>
                                ${task_members_options}
                            </select>
                        </div>

                        <div class="form-group text-left">
                            <label for="priority">Priority</label>
                            <select name="priority" id="priority" class="form-control select-aries">
                                <option value=""> --- Choose Priority --- </option>
                                <option value="high" ${(task.priority == 'high' ? 'selected' : '')}>High</option>
                                <option value="middle" ${(task.priority == 'middle' ? 'selected' : '')}>Middle</option>
                                <option value="low" ${(task.priority == 'low' ? 'selected' : '')}>Low</option>
                            </select>
                        </div>
                        </form>`,
                    showCancelButton: false,
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                if (result.isConfirmed) {
                    var form_data = $('#editTask').serialize();
                    $.ajax({
                        url:`/task/${task.id}`,
                        type:'PUT',
                        data:form_data,
                        success:function(res){
                           taskData();
                        }
                    });
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
                    theme: "bootstrap4",
                    placeholder:'---Please Choose---'
                });

            });

            $(document).on('click', '.delete_task_btn',function(e){
              e.preventDefault();

              var id = $(this).data('id');
              swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this imaginary file!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            method:"DELETE",
                            url:`/task/${id}`,
                        }).done(function(res){
                             taskData();
                        });
                    } 
                });
            });

            


         });

            
    </script>
@endsection