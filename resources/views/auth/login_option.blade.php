@extends('layouts.app_plain')
@section('title','Login Option')
@section('extra_css')
    <style>
        .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
            color: #4cd195;
            background-color: #f5f5ff;
        }
        .nav-pills .nav-link{
            color: #000;
        }
    </style>
@endsection
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
                    <p class="text-center text-muted">Please choose login option!</p>
                    <ul class="nav nav-pills mb-3 nav-justified" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password"
                            aria-selected="true">Password</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="biometric-tab" data-toggle="tab" href="#biometric" role="tab" aria-controls="biometric"
                            aria-selected="false">Device Authentication</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="password" role="tabpanel" 
                         aria-labelledby="password-tab">
                     
                         <form action="{{ route('login') }}" method="POST">
                             @csrf
                             <input type="hidden" name="phone" value="{{ request('phone') }}" id="phone">
                             <div class="md-form">
                                 <input type="hidden" name="phone" value="{{ request('phone') }}">
                                 <input id="password" name="password" type="password" class="text-center form-control" autofocus placeholder="Enter Password">
                            </div>
                            @if ($errors->all())
                                @foreach ($errors->all() as $error)
                                    <p class="text-danger text-center">{{ $error }}</p>
                                @endforeach
                            @endif
                            <button type="submit" class="btn btn-theme btn-block mt-4">submit</button>
                         </form>
                    </div>
                    <div class="tab-pane fade" id="biometric" role="tabpanel"     
                         aria-labelledby="biometric-tab">

                        <div class="text-center mt-5">
                            <button type="submit" class="btn biometric-login-btn text-center">
                                <i class="fas fa-fingerprint"></i>
                            </button>
                            <p class="text-center text-muted mt-3">Device Authentication</p>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
    <script>
    const login = (event) => {
        event.preventDefault()
        new Larapass({
            login: 'webauthn/login',
            loginOptions: 'webauthn/login/options'
        }).login({
            phone: document.getElementById('phone').value
        }).then(function(response){
            window.location.replace('/');
        }).catch(function(error){
             console.log(error);
          });
    }
    $('.biometric-login-btn').on('click',function(event){
        login(event);
    })
</script>
@endsection