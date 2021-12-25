@extends('layouts.app')
@section('title','MY HR')
@section('content')

    {{-- Search Form --}}
    <div class="card mb-4">
        <div class="card-body text-center">
            <img src="{{ asset('images/qr-scan.png') }}" class="w-25 mx-auto d-block" alt="qr-scan">
            <p class="mb-1 text-muted">Please Scan Attendance Qr</p>
        
            <button type="button" class="btn btn-theme mt-3 btn-sm" 
                    data-toggle="modal"     
                    data-target="#scanModal">
            Scan
            </button>

        </div>
    </div>

    {{-- Attendance Overview --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <select name="month" class="form-control select-aries" id="month">
                                <option value="">---- Please Choose (Month) -----</option>
                                <option value="01" @if( now()->format('m') == '01') selected @endif>January </option>
                                <option value="02" @if( now()->format('m') == '02') selected @endif>February </option>
                                <option value="03" @if( now()->format('m') == '03') selected @endif>March</option>
                                <option value="04" @if( now()->format('m') == '04') selected @endif>April</option>
                                <option value="05" @if( now()->format('m') == '05') selected @endif>May</option>
                                <option value="06" @if( now()->format('m') == '06') selected @endif>June</option>
                                <option value="07" @if( now()->format('m') == '07') selected @endif>July</option>
                                <option value="08" @if( now()->format('m') == '08') selected @endif>August</option>
                                <option value="09" @if( now()->format('m') == '09') selected @endif>September</option>
                                <option value="10" @if( now()->format('m') == '10') selected @endif>October</option>
                                <option value="11" @if( now()->format('m') == '11') selected @endif>November</option>
                                <option value="12" @if( now()->format('m') == '12') selected @endif>December</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                         <div class="form-group">
                            <select name="year" class="form-control select-aries" id="year">
                                <option value="">---- Please Choose (Year) -----</option>
                                @for($i=0;$i<5;$i++)
                                <option value="{{ now()->subYears($i)->format('Y') }}" @if(now()->format('Y') == now()->subYears($i)->format('Y')) selected @endif >{{ now()->subYears($i)->format('Y') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-theme btn-sm btn-block search-btn"><i class="fas fa-search">Search</i></button>
                    </div>
                </div>
            </div>
            <h5>Attendance Overview</h5>
            <div class="attendance_overview_table"></div>
        </div>
    </div>

    {{-- Payroll Records --}}
    <div class="card mb-4">
         <div class="card-body">
             <h5>Payroll Records</h5>
             <div class="payroll_table"></div>
         </div>
    </div>

    {{-- Attendance Records --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5>Attendance Record</h5>
            <table class="table table-bordered attendances w-100">
                <thead>
                    <th class="text-center no-sort no-search"></th>
                    <th class="text-center no-sort">Employee</th>
                    <th class="text-center no-sort">Date</th>
                    <th class="text-center no-sort">Check In</th>
                    <th class="text-center no-sort">Check Out</th>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" 
            id="scanModal" 
            tabindex="-1" 
            role="dialog"
            aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Attendance Qr</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <video id="video" class="w-100"></video>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-js')
    <script src="{{ asset('js/qr-scanner.umd.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            var videoElem = document.getElementById('video');
            const qrScanner = new QrScanner(videoElem,function(res){
                if(res){
                    $('#scanModal').hide();
                    $('.modal-backdrop').hide();
                    qrScanner.stop();

                    $.ajax({
                        url:'/attendance-scan/store',
                        method:'POST',
                        data:
                        {
                            "hash_value" : res,
                        },
                        success: function (res) {
                            if(res.status == 'success'){
                                Toast.fire({
                                    icon: 'success',
                                    title: res.message,
                                });
                            }else{
                                Toast.fire({
                                    icon: 'error',
                                    title: res.message,
                                });
                            }
                        }
                    });
                }
            });
            
            $('#scanModal').on('show.bs.modal', function (e) {
                qrScanner.start();
            })

            $('#scanModal').on('hidden.bs.modal', function (e) {
                qrScanner.stop();
            })

            var table = $('.attendances').DataTable({
                ajax: '/my-attendance/datable/ssd',
                columns: [
                    {data: 'plus-icon', name: 'plus-icon' , class:'text-center'},
                    {data: 'employee_name', name: 'employee_name' , class:'text-center'},
                    {data: 'date', name: 'date' , class:'text-center'},
                    {data: 'checkin_time', name: 'checkin_time' , class:'text-center'},
                    {data: 'checkout_time', name: 'checkout_time' , class:'text-center'},
                ],
                order:[[3,'desc']]
            });

            $('.select-aries').select2({
                allowClear: true,
                theme: "bootstrap4"
            });

            attendancOverviewTable();
            function attendancOverviewTable()
            {

                var month = $('#month').val();
                var year = $('#year').val();

               $.ajax({
                   url:`/my-attendance-overview-table?month=${month}&year=${year}`,
                   type:'GET',
                   success:function(res){
                        $('.attendance_overview_table').html(res);
                   }
               });

               table.ajax.url(`/my-attendance/datable/ssd?month=${month}&year=${year}`).load();
            }

            payrollTable();
            function payrollTable()
            {
                var month = $('#month').val();
                var year = $('#year').val();

               $.ajax({
                   url:`/my-payroll-table?month=${month}&year=${year}`,
                   type:'GET',
                   success:function(res){
                        $('.payroll_table').html(res);
                   }
               })
            }

            $('.search-btn').on('click',function(event){
                event.preventDefault();
                attendancOverviewTable();
                payrollTable();
            });


        });
    </script>
@endsection