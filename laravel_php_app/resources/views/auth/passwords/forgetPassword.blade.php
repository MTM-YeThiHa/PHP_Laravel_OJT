@extends('layouts.app')
@section('content')
<!-- Styles -->
<link rel="stylesheet" href="{{ asset('css/email.css') }}">

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Reset Password</div>
        <div class="card-body">
          @if (Session::has('message'))
          <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
            <div>
              <span><i class="fa-solid fa-check"></i></span>
              {{session::get('message')}}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>
          @endif

          <form action="{{route('forget.password.post')}}" method="POST">
            @csrf
            <div class="form-group row">
              <label for="email" class="col-md-4 col-form-label text-md-right">E-mail Address</label>
              <div class="col-md-6">
                <input type="text" id="email" class="form-control" name="email" require autofocus>
                @if ($errors->has('email'))
                <span class="text-danger">
                  <strong>{{$errors->first('email')}}</strong>
                </span>
                @endif
              </div>
            </div>
            <div class="col-md-6 offset-md-4">
              <button type="submit" class="btn btn-primary">
                Send Password Reset Link
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection