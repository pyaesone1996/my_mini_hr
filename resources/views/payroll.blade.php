
@extends('layouts.app')
@section('title','Payrolls')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" id="name" class="form-control" placeholder="Employee Name">
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                         <div class="form-group">
                            <select name="year" class="form-control select-aries" id="year">
                                <option value="">---- Please Choose (Year) -----</option>
                                @for($i=0;$i<5;$i++)
                                <option value="{{ now()->subYears($i)->format('Y') }}" @if(now()->format('Y') == now()->subYears($i)->format('Y')) selected @endif >{{ now()->subYears($i)->format('Y') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-theme btn-sm btn-block search-btn"><i class="fas fa-search">Search</i></button>
                    </div>
                </div>
            </div>
            <div class="payroll_table"></div>
        </div>
    </div>
@endsection

@section('extra-js')
    <script>
         $(document).ready(function(){
             $('.select-aries').select2({
                allowClear: true,
                theme: "bootstrap4"
            });
            
            payrollTable();
            function payrollTable()
            {
                var name = $('#name').val();
                var month = $('#month').val();
                var year = $('#year').val();

               $.ajax({
                   url:`/payroll-table?name=${name}&month=${month}&year=${year}`,
                   type:'GET',
                   success:function(res){
                        $('.payroll_table').html(res);
                   }
               })
            }

            $('.search-btn').on('click',function(event){
                event.preventDefault();
                payrollTable();
            });

         });
    </script>
@endsection