@extends('layouts.app')

@section('content')
<!-- Styles -->
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-bg-success">
                    <h3>{{__('Profile')}}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-6 text-align-center mx-auto">
                            <div class="ratio ratio-1x1 rounded-circle overflow-hidden">
                                <img class="card-img-top object-fit-cover" src="{{ asset('storage/images/'. $user->profile) }}" alt="Profile Image" class="preview-profile">
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-12 col-sm-6">
                            <div class="row">
                                <label class="col-md-3 text-md-left">{{ __('Name') }}</label>
                                <label class="col-md-9 text-md-left">
                                    <i class="profile-text">{{$user->name}}</i>
                                </label>
                            </div>
                            <div class="row">
                                <label class="col-md-3 text-md-left">{{ __('Type') }}</label>
                                @if($user->type == '0')
                                <label class="col-md-9 text-md-left">
                                    <i class="profile-text">Admin</i>
                                </label>
                                @else
                                <label class="col-md-9 text-md-left">
                                    <i class="profile-text">User</i>
                                </label>
                                @endif
                            </div>
                            <div class="row">
                                <label class="col-md-3 text-md-left">{{ __('Email') }}</label>
                                <label class="col-md-9 text-md-left">
                                    <i class="profile-text">{{$user->email}}</i>
                                </label>
                            </div>
                            <div class="row">
                                <label class="col-md-3 text-md-left">{{ __('Phone') }}</label>
                                <label class="col-md-9 text-md-left">
                                    <i class="profile-text">{{$user->phone}}</i>
                                </label>
                            </div>
                            <div class="row">
                                <label class="col-md-3 text-md-left">{{ __('Date of Birth') }}</label>
                                <label class="col-md-9 text-md-left">
                                    <i class="profile-text">{{date('Y/m/d', strtotime($user->dob))}}</i>
                                </label>
                            </div>
                            <div class="row">
                                <label class="col-md-3 text-md-left">{{ __('Address') }}</label>
                                <label class="col-md-9 text-md-left">
                                    <i class="profile-text">{{$user->address}}</i>
                                </label>
                            </div>
                            <div class="">
                                <a type="button" class="btn btn-primary" href="/user/profile/edit">
                                    {{ __('Edit Profile') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection