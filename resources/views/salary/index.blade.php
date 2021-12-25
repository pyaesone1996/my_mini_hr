@extends('layouts.app')
@section('title','Salaries')
@section('content')
    <div>
        @can('create_salary')
            <a href="{{ route('salary.create') }}" class="btn btn-theme btn-sm btn-success">
                <i class="fas fa-plus-circle pr-1"></i>
                Create salary
            </a>
        @endcan
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered Datatable w-100">
                <thead>
                    <th class="text-center no-sort no-search hidden"></th>
                    <th class="text-center ">Name</th>
                    <th class="text-center ">Month</th>
                    <th class="text-center ">Year</th>
                    <th class="text-center ">Amount</th>
                    <th class="text-center no-sort">Action</th>
                    <th class="text-center no-search hidden">Updated Time</th>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('extra-js')
    <script>
         $(document).ready(function(){
            var table = $('.Datatable').DataTable({
                ajax: '/salary/datable/ssd',
                columns: [
                    {data: 'plus-icon', name: 'plus-icon' , class:'text-center'},
                    {data: 'employee_name', name: 'employee_name' , class:'text-center'},
                    {data: 'month', name: 'month' , class:'text-center'},
                    {data: 'year', name: 'year' , class:'text-center'},
                    {data: 'amount', name: 'amount' , class:'text-center'},
                    {data: 'action', name: 'action', class:'text-center'},
                    {data: 'updated_at', name: 'updated_at', class:'text-center'},
                ],
                order:[[6,'desc']]
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
                            url:`/salary/${id}`,
                        }).done(function(res){
                            table.ajax.reload();
                        });
                    } 
                });
            });
        
         });

    </script>
@endsection