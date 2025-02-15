@extends('layouts.main')

@section('main-section')

<div class="content-wrapper  " >

                {{-- <div class="container mt-5">
                    <div class="card shadow-lg p-3">
                        <div class="row g-3">
                            <!-- Profile Picture (Left) -->
                            <div class="col-md-4 text-center"> --}}
                                {{-- {{ dd(asset('storage/users/' . $user->profile_image)) }} --}}

                                {{-- <img src="{{ asset('/storage/' . $user->profile_image) }}" alt="Profile Picture" class="rounded-circle img-fluid" width="150">
                            </div>
                            <div class="col-md-8">
                                <h3 class="mb-1">{{ $user->name }}</h3>
                                <p class="text-muted">{{ $user->email }}</p>
                                <p><strong>Phone:</strong> {{ $user->phone }}</p>
                                <button class="btn btn-primary btn-sm">Edit Profile</button>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="row gutters-sm mt-5">
                    <div class="col-md-4 mb-3 ">
                        <div class="card">
                            <div class="card-body">
                                {{-- @foreach ($users as $user) --}}
                                <div class="d-flex flex-column align-items-center text-center">
                                      <img src="{{ asset('/storage/' . $user->profile_image) }}" alt="Profile Picture" class="rounded-circle img-fluid" width="150">
                                    <div class="mt-3">
                                            <h4> {{ $user->name }}</h4>
                                            <p class="text-secondary mb-1"></p>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Full Name</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $user->name }}
                                    </div>
                                </div>
                                <hr>

                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Email</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $user->email }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Mobile</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $user->phone }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Address</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $user->address }}
                                    </div>
                                </div>

                                <hr>
                                <div class="row">

                                    <div class="col-sm-3">
                                        <h6 class="mb-0">About Me</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $user->aboutme }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a class="btn btn-primary " href=""> <i
                                                class="fas fa-edit"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- @endforeach --}}
                    </div>
                </div>

        </div>
    </div>




@endsection









