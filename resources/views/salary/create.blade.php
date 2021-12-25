@extends('layouts.app')
@section('title','Salary Create')

@section('content')
   <div class="card">
       <div class="card-body">
           <form action="{{ route('salary.store') }}" 
                 method="POST" 
                 autocomplete="off" 
                 id="create-form">
            @csrf
            
            <div class="md-form">
                <select name="user_id" id="user_id" class="select-aries form-control">
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>

             <div class="form-group">
                <label for="month">Month</label>
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

            <div class="form-group">
                <label for="year">Year</label>
                <select name="year" class="form-control select-aries" id="year">
                    <option value="">---- Please Choose (Year) -----</option>
                    @for($i=0;$i<15;$i++)
                    <option value="{{ now()->addYears(5)->subYears($i)->format('Y') }}" @if(now()->format('Y') == now()->addYears(5)->subYears($i)->format('Y')) selected @endif >{{ now()->addYears(5)->subYears($i)->format('Y') }}</option>
                    @endfor
                </select>
            </div>

            <div class="md-form">
                <label for="amount">Amount (MMK)</label>
                <input type="text" 
                       name="amount" 
                       id="amount" 
                       class="form-control" 
                       value="{{ old('amount') }}"
                >
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-sm btn-theme">Confirm</button>
            </div>

           </form>
       </div>
   </div>
@endsection

@section('extra-js')
   {!! JsValidator::formRequest('App\Http\Requests\Storesalary' ,'#create-form') !!}
   <script>
       $(document).ready(function(){
          
        });
   </script>
@endsection