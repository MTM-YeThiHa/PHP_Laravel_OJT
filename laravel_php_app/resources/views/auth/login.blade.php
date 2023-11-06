@extends('layouts.app')
@section('content')
<!--Style -->
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            @if (Session::has('message'))
            <div class="auto-close alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
                <div>
                    <span><i class="fa-solid fa-check"></i></span>
                    {{session::get('message')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif
            <div class="card">
                <form action="{{route('login')}}" method="POST" class="box">
                    @csrf
                    <h1>Login</h1>
                    <p class="text-info"> Please enter your login and password!</p>
                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email')}}" require autocomplete="email" autofocus placeholder="E-mail">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <input id="password" type="password" name="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" require autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <a class="text-info" href="{{ route('forget.password.get') }}">Forgot password?</a>
                    <div>
                        <button class="btn-submit" type="submit">
                            {{__('Login')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection