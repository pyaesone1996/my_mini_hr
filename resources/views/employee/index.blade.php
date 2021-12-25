@extends('layouts.app')
@section('title','Employees')
@section('content')
    <div>
        @can('create_employee')
            <a href="{{ route('employee.create') }}" class="btn btn-theme btn-sm btn-success">
                <i class="fas fa-plus-circle pr-1"></i>
                Create Employee
        </a>
        @endcan
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered Datatable w-100">
                <thead>
                    <th class="text-center no-sort no-search"></th>
                    <th class="text-center no-sort">Employee</th>
                    <th class="text-center">ID</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Phone</th>
                    <th class="text-center">Department</th>
                    <th class="text-center">Role</th>
                    <th class="text-center hidden">Is Present?</th>
                    <th class="text-center no-sort min_w_100">Action</th>
                    <th class="text-center no-search hidden">Updated Time</th>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('extra-js')
    <script>
         let token = document.head.querySelector('meta[name="csrf-token"]');
         if(token){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token.content
                }
            });
         }else{
            console.log('csrf-not-found');
         }

         $(document).ready(function(){
            var table = $('.Datatable').DataTable({
                ajax: '/employee/datable/ssd',
                columns: [
                    {data: 'plus-icon', name: 'plus-icon' , class:'text-center'},
                    {data: 'profile_image', name: 'profile_image' , class:'text-center'},
                    {data: 'employee_id', name: 'employee_id' , class:'text-center'},
                    {data: 'email', name: 'email', class:'text-center'},
                    {data: 'phone', name: 'phone', class:'text-center'},
                    {data: 'department_name', name:'department_name', class:'text-center'},
                    {data: 'role_name', name:'role_name', class:'text-center'},
                    {data: 'is_present', name: 'is_present', class:'text-center'},
                    {data: 'action', name: 'action', class:'text-center'},
                    {data: 'updated_at', name: 'updated_at', class:'text-center'},
                ],
                order:[[9,'desc']],
            });

            $(document).on('click', '.delete-btn',function(e){
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
                            url:`/employee/${id}`,
                        }).done(function(res){
                            table.ajax.reload();
                        });
                    } 
                });
            });
        
         });

    </script>
@endsection