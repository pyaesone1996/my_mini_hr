<div class="row">

    <div class="col-sm-4">
        <div class="card">

            <div class="card-header bg-warning">
                <h5 class="text-white mb-0"><i class="fas fa-tasks"></i> Pending</h5>
            </div>

            <div class="card-body alert-warning">

                <div id="pendingTasks">
                    @foreach (collect($project->tasks)->where('status','pending')->sortBy('serial_number') ?? [] as $pending_task)
                        <div class="task-item" data-id="{{ $pending_task->id }}">

                            <div class="d-flex justify-content-between align-items-start">
                                <h6>{{ $pending_task->title }}</h6>
                                <div class="task-action">
                                    <a href="" class="edit_task_btn text-info"
                                       data-task="{{ base64_encode(json_encode($pending_task)) }}"
                                       data-task-members="{{ base64_encode(json_encode(collect($pending_task->members)->pluck('id')->toArray())) }}"
                                    >
                                            <i class="far fa-edit"></i></a>
                                    <a href="" class="delete_task_btn text-danger" data-id="{{ $pending_task->id }}"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-end">
                                <div>
                                    <p class="mb-1"><i class="fas fa-clock"></i> {{Carbon\Carbon::parse($pending_task->start_date)->format('M d') }}</p>
                                    @if ($pending_task->priority == 'high')
                                        <span class="badge badge-pill badge-danger py-0">{{ $pending_task->priority }}</span>
                                    @elseif ($pending_task->priority == 'middle')
                                        <span class="badge badge-pill badge-info py-0">{{ $pending_task->priority }}</span>
                                    @else
                                        <span class="badge badge-pill badge-dark py-0">{{ $pending_task->priority }}</span>
                                    @endif
                                </div>
                                <div>
                                    @foreach ($pending_task->members as $member)
                                        <img src='{{ $member->profile() }}' class="profile-thumbnail3" alt='{{ $member->name }}'/>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            
                <div class="text-center">
                    <a href="" class="add_task pending_task_button btn"><i class="fas fa-plus-circle"></i> Add Task</a>
                </div>

            </div>

        </div>
    </div>

    <div class="col-sm-4">
        <div class="card">

            <div class="card-header bg-info"> 
                <h5 class="text-white mb-0"><i class="fas fa-tasks"></i> In Progress</h5>
            </div>

            <div class="card-body alert-info">
               
                <div id="inProgressTasks">
                    @foreach (collect($project->tasks)->where('status','in_progress')->sortBy('serial_number') ?? [] as $in_progress_task)
                        <div class="task-item"  data-id="{{ $in_progress_task->id }}">

                            <div class="d-flex justify-content-between align-items-start">
                                <h6>{{ $in_progress_task->title }}</h6>
                                <div class="task-action">
                                    <a href="" class="edit_task_btn text-info"
                                       data-task="{{ base64_encode(json_encode($in_progress_task)) }}"
                                       data-task-members="{{ base64_encode(json_encode(collect($in_progress_task->members)->pluck('id')->toArray())) }}"
                                    >
                                            <i class="far fa-edit"></i></a>
                                    <a href="" class="delete_task_btn text-danger" data-id="{{ $in_progress_task->id }}"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-end">
                                <div>
                                    <p class="mb-1"><i class="fas fa-clock"></i> {{Carbon\Carbon::parse($in_progress_task->start_date)->format('M d') }}</p>
                                    @if ($in_progress_task->priority == 'high')
                                        <span class="badge badge-pill badge-danger py-0">{{ $in_progress_task->priority }}</span>
                                    @elseif ($in_progress_task->priority == 'middle')
                                        <span class="badge badge-pill badge-info py-0">{{ $in_progress_task->priority }}</span>
                                    @else
                                        <span class="badge badge-pill badge-dark py-0">{{ $in_progress_task->priority }}</span>
                                    @endif
                                </div>
                                <div>
                                    @foreach ($in_progress_task->members as $member)
                                        <img src='{{ $member->profile() }}' class="profile-thumbnail3" alt='{{ $member->name }}'/>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                <div class="text-center">
                    <a href="" class="add_task in_progress_task_button btn"><i class="fas fa-plus-circle"></i> Add Task</a>
                </div>

            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="card">

            <div class="card-header bg-success"> 
                 <h5 class="text-white mb-0"><i class="fas fa-tasks"></i> Complete</h5>
            </div>

            <div class="card-body alert-success">
               
                <div  id="completeTasks">
                    @foreach (collect($project->tasks)->where('status','complete')->sortBy('serial_number') ?? [] as $completed_task)
                        <div class="task-item"  data-id="{{ $completed_task->id }}">

                            <div class="d-flex justify-content-between align-items-start">
                                <h6>{{ $completed_task->title }}</h6>
                                <div class="task-action">
                                    <a href="" class="edit_task_btn text-info"
                                       data-task="{{ base64_encode(json_encode($completed_task)) }}"
                                       data-task-members="{{ base64_encode(json_encode(collect($completed_task->members)->pluck('id')->toArray())) }}"
                                    >
                                            <i class="far fa-edit"></i></a>
                                    <a href="" class="delete_task_btn text-danger" data-id="{{ $completed_task->id }}"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-end">
                                <div>
                                    <p class="mb-1"><i class="fas fa-clock"></i> {{Carbon\Carbon::parse($completed_task->start_date)->format('M d') }}</p>
                                    @if ($completed_task->priority == 'high')
                                        <span class="badge badge-pill badge-danger py-0">{{ $completed_task->priority }}</span>
                                    @elseif ($completed_task->priority == 'middle')
                                        <span class="badge badge-pill badge-info py-0">{{ $completed_task->priority }}</span>
                                    @else
                                        <span class="badge badge-pill badge-dark py-0">{{ $completed_task->priority }}</span>
                                    @endif
                                </div>
                                <div>
                                    @foreach ($completed_task->members as $member)
                                        <img src='{{ $member->profile() }}' class="profile-thumbnail3" alt='{{ $member->name }}'/>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
               
                <div class="text-center">
                    <a href="" class="add_task complete_task_button btn"><i class="fas fa-plus-circle"></i> Add Task</a>
                </div>

            </div>
        </div>
    </div>
    
</div>