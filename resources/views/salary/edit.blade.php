@extends('layouts.app')
@section('title','Edit Salary')

@section('content')
   <div class="card">
       <div class="card-body">
           <form action="{{ route('salary.update',$salary->id) }}" method="POST" id="edit-form">
            @method('PUT')
            @csrf
           
             <div class="md-form">
               <input type="text" class="form-control" value="{{ $employee->name }}">
               <input type="hidden" name="user_id" class="form-control" value="{{ $employee->id }}">
            </div>

             <div class="form-group">
                <label for="month">Month</label>
                <select name="month" class="form-control select-aries" id="month">
                    <option value="">---- Please Choose (Month) -----</option>
                    <option value="01" @if( $salary->month == '01') selected @endif>January</option>
                    <option value="02" @if( $salary->month == '02') selected @endif>February</option>
                    <option value="03" @if( $salary->month == '03') selected @endif>March</option>
                    <option value="04" @if( $salary->month == '04') selected @endif>April</option>
                    <option value="05" @if( $salary->month == '05') selected @endif>May</option>
                    <option value="06" @if( $salary->month == '06') selected @endif>June</option>
                    <option value="07" @if( $salary->month == '07') selected @endif>July</option>
                    <option value="08" @if( $salary->month == '08') selected @endif>August</option>
                    <option value="09" @if( $salary->month == '09') selected @endif>September</option>
                    <option value="10" @if( $salary->month == '10') selected @endif>October</option>
                    <option value="11" @if( $salary->month == '11') selected @endif>November</option>
                    <option value="12" @if( $salary->month == '12') selected @endif>December</option>
                </select>
            </div>

            <div class="form-group">
                <label for="year">Year</label>
                <select name="year" class="form-control select-aries" id="year">
                    <option value="">---- Please Choose (Year) -----</option>
                    @for($i=0; $i<15; $i++)
                    <option value="{{ now()->addYears(5)->subYears($i)->format('Y') }}" 
                            @if( $salary->year == now()->addYears(5)->subYears($i)->format('Y')) selected @endif >
                            {{ now()->addYears(5)->subYears($i)->format('Y') }}
                    </option>
                    @endfor
                </select>
            </div>

            <div class="md-form">
                <label for="amount">Amount (MMK)</label>
                <input type="text" 
                       name="amount" 
                       id="amount" 
                       class="form-control" 
                       value="{{ old('amount',$salary->amount) }}"
                >
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-sm btn-theme">Update</button>
            </div>

           </form>
       </div>
   </div>
@endsection

@section('extra-js')
   {!! JsValidator::formRequest('App\Http\Requests\Updatesalary' ,'#edit-form') !!}
   <script>
       $(document).ready(function(){
    
        });
   </script>
@endsection