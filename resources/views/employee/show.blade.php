@extends('layouts.app')
@section('title','Employee Detail')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-left">
                        <img src="{{ $employee->profile() }}" alt="{{ $employee->name .'-profile'}}" class="profile-image">
                        <div class="px-3">
                            <h4>{{ $employee->name }}</h4>
                            <p class="text-muted mb-2">
                                {{ $employee->employee_id }} | 
                                <span class="color-theme">{{ $employee->phone }}</span>
                            </p>
                            <p class="text-muted mb-2">
                            <span class="badge badge-pill badge-light">{{ $employee->department ? $employee->department->name : '-'}}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 info-right">
                    <p class="mb-1">
                        <strong>Name</strong>:
                        <span class="text-muted">{{ $employee->name }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>Phone</strong>:
                        <span class="text-muted">{{ $employee->phone }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>Email</strong>:
                        <span class="text-muted">{{ $employee->email }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>Gender</strong>:
                        <span class="text-muted">{{ ucfirst($employee->gender) }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>Birthday</strong>:
                        <span class="text-muted">{{ $employee->birthday }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>NRC Number</strong>:
                        <span class="text-muted">{{ $employee->nrc_number }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>Address</strong>:
                        <span class="text-muted">{{ $employee->address }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>Date Of Joined</strong>:
                        <span class="text-muted">{{ $employee->date_of_joined }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>Is Present?</strong>:
                            @if ($employee->is_present == 1)
                                <span class="badge badge-pill badge-success py-2">Present</span>
                            @else
                                <span class="badge badge-pill badge-danger py-2">Leave</span>
                            @endif
                    </p>
                </div>
            </div>

        </div>
    </div>
@endsection