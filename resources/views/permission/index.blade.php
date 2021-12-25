@extends('layouts.app')
@section('title','Permissions')
@section('content')
    <div>
        @can('create_permission')
            <a href="{{ route('permission.create') }}" class="btn btn-theme btn-sm btn-success">
                <i class="fas fa-plus-circle pr-1"></i>
                Create Permission
            </a>         
        @endcan
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered Datatable w-100">
                <thead>
                    <th class="text-center no-sort no-search"></th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Created at</th>
                    <th class="text-center">Updated at</th>
                    <th class="text-center no-sort">Action</th>
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
                ajax: '/permission/datable/ssd',
                columns: [
                    {data: 'plus-icon', name: 'plus-icon' , class:'text-center'},
                    {data: 'name', name: 'name' , class:'text-center'},
                    {data: 'created_at', name: 'created_at' , class:'text-center'},
                    {data: 'updated_at', name: 'updated_at' , class:'text-center'},
                    {data: 'action', name: 'action', class:'text-center'},
                ],
                order:[[3,"desc"]],
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
                            url:`/permission/${id}`,
                        }).done(function(res){
                            table.ajax.reload();
                        });
                    } 
                });
            });
        
         });

    </script>
@endsection