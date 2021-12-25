@extends('layouts.app')
@section('title','Profile')
@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-left">
                        <img src="{{ $user->profile() }}" alt="{{ $user->name .'-profile'}}" class="profile-image">
                        <div class="px-3">
                            <h4>{{ $user->name }}</h4>
                            <p class="text-muted mb-2">
                                {{ $user->employee_id }} | 
                                <span class="color-theme">{{ $user->phone }}</span>
                            </p>
                            <p class="text-muted mb-2">
                                <span class="badge badge-pill badge-light">
                                    {{ $user->department ? $user->department->name : '-'}}
                                </span>
                            </p>
                            <p class="text-muted mb-2">
                                @foreach ($user->roles as $role)
                                    <span class="badge badge-pill badge-primary">{{ $role->name }}</span>
                                @endforeach
                            </p>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-6 info-right">
                    <p class="mb-1">
                        <strong>Name</strong>:
                        <span class="text-muted">{{ $user->name }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>Phone</strong>:
                        <span class="text-muted">{{ $user->phone }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>Email</strong>:
                        <span class="text-muted">{{ $user->email }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>Gender</strong>:
                        <span class="text-muted">{{ ucfirst($user->gender) }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>Birthday</strong>:
                        <span class="text-muted">{{ $user->birthday }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>NRC Number</strong>:
                        <span class="text-muted">{{ $user->nrc_number }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>Address</strong>:
                        <span class="text-muted">{{ $user->address }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>Date Of Joined</strong>:
                        <span class="text-muted">{{ $user->date_of_joined }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>Is Present?</strong>:
                            @if ($user->is_present == 1)
                                <span class="badge badge-pill badge-success py-2">Present</span>
                            @else
                                <span class="badge badge-pill badge-danger py-2">Leave</span>
                            @endif
                    </p>
                </div>
            </div>

        </div>
    </div>

     <div class="card mb-2">
        <div class="card-body">
            <h5>Device Authentication</h5>
            <span class="biometric-data-component"></span>
            <button type="submit" class="btn biometric-register-btn">
                <i class="fas fa-fingerprint"></i>
                <i class="fas fa-plus-circle"></i>
            </button>
        </div>
    </div>

    <div class="card mb-2">
        <div class="card-body">
            <a href="#" class="btn btn-theme btn-block logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </div>
@endsection

@section('extra-js')
    <script>
        $(document).ready(function() {

            $(document).on('click','.biometic-delete-btn',function(event){
                event.preventDefault();
                var id = $(this).data('id');
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this imaginary file!",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            method:"DELETE",
                            url:`/profile/biometric-data/delete/${id}`,
                        }).done(function(res){
                           biometricData();
                        });
                    } 
                });

            });

            biometricData();
            function biometricData() {
                $.ajax({
                    url: '/profile/biometric-data',
                    type: 'GET',
                    success:function(res){
                        $('.biometric-data-component').html(res);
                    }
                });
            }

            const register = (event) => {
                event.preventDefault()
                new Larapass({
                    register: 'webauthn/register',
                    registerOptions: 'webauthn/register/options'
                })
                .register()
                .then(function(response) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Fingerprint is successfully added!'
                    });
                    biometricData();

                }).catch(function(response) {
                    console.log(response);
                });
            }
           
            $('.biometric-register-btn').on('click',function (event) {
                register(event);
            })

            $('.logout-btn').on('click',function(e) 
                {
                    e.preventDefault();
                    swal({
                        text: "Are you sure , You want to logout?",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                        $.ajax({
                                url: '/logout',
                                method: 'POST',
                            }).done(function(res) {
                                window.location.reload();
                            });
                        } 
                    });
                });
                
        });
            
    </script>
@endsection