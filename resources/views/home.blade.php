@extends('layouts.app')
@section('title','MY HR')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="info-left">
                        <img src="{{ $user->profile() }}" alt="{{ $user->name .'-profile'}}" class="profile-image">
                        <div class="px-3">
                            <h4>{{ $user->name }}</h4>
                            <p class="text-muted mb-2">
                                {{ $user->employee_id }} | 
                                <span class="color-theme">{{ $user->phone }}</span>
                            </p>
                            <p class="text-muted mb-2">
                            <span class="badge badge-pill badge-light">{{ $user->department ? $user->department->name : '-'}}</span>
                            </p>
                            <p class="text-muted mb-2">
                                @foreach ($user->roles as $role)
                                    <span class="badge badge-pill badge-primary">{{ $role->name }}</span>
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection