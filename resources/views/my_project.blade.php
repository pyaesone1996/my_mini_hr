@extends('layouts.app')
@section('title','My Project')
@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered Datatable w-100">
                <thead>
                    <th class="text-center no-sort no-search"></th>
                    <th class="text-center">Title</th>
                    <th class="text-center">Description</th>
                    <th class="text-center no-sort" style="min-width:120px">Members</th>
                    <th class="text-center no-sort" style="min-width:120px">Leaders</th>
                    <th class="text-center">Start Date</th>
                    <th class="text-center">Deadline</th>
                    <th class="text-center">Priority</th>
                    <th class="text-center">Status</th>
                    <th class="text-center no-sort" style="min-width:90px">Action</th>
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
                ajax: '/my-project/datable/ssd',
                columns: [
                    {data: 'plus-icon', name: 'plus-icon' , class:'text-center'},
                    {data: 'title', name: 'title' , class:'text-center'},
                    {data: 'description', name: 'description' , class:'text-center'},
                    {data: 'members', name: 'members' , class:'text-center'},
                    {data: 'leaders', name: 'leaders' , class:'text-center'},
                    {data: 'start_date', name: 'start_date' , class:'text-center'},
                    {data: 'deadline', name: 'deadline' , class:'text-center'},
                    {data: 'priority', name: 'priority' , class:'text-center'},
                    {data: 'status', name: 'status' , class:'text-center'},
                    {data: 'action', name: 'action', class:'text-center'},
                    {data: 'updated_at', name: 'updated_at', class:'text-center'},
                ],
                order:[[10,'desc']]
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
                            url:`/project/${id}`,
                        }).done(function(res){
                            table.ajax.reload();
                        });
                    } 
                });
            });
        
         });

    </script>
@endsection