@extends('layouts.app_plain')
@section('title','Login')
@section('content')
<div class="container">
    <div class="row justify-content-center align-content-center" style="height:100vh;">
        <div class="col-md-6">
            <div class="text-center mb-3">
                <img src={{ asset('images/logo.png') }} style="width:80px;">
            </div>

            <div class="card">
                <div class="card-body minheight_40">
                    <h5 class="text-center">Login</h5>
                    <p class="text-center text-muted">Please fill login form!</p>

                    <form method="GET" action="{{ route('login-option') }}">
                        <div class="md-form mb-5">
                             <input id="phone" type="phone" class="text-center form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}"  autofocus placeholder="Enter Phone">
                             @error('phone')
                                 <p class="text-danger text-center">{{ $message }}</p>
                             @enderror
                        </div>

                        <button type="submit" class="btn btn-theme btn-block mt-4">Continue</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
