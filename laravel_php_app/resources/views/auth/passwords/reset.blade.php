@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Reset Password</div>
        <div class="card-body">
          <form action="{{route('reset.password.post')}}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group row">
              <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
              <div class="col-md-6">
                <input type="text" id="email" class="form-control" name="email" require autofocus>
                @if ($errors->has('email'))
                <span class="text-danger">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group row">
              <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
              <div class="col-md-6">
                <input type="password" class="form-control" id="password" name="password" require autofocus>
                @if ($errors->has('password'))
                  <span class="text-danger">
                    <strong>{{$errors->first('password')}}</strong>
                  </span>
                @endif
              </div>
            </div>

            <div class="form-group row">
              <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
              <div class="col-md-6">
                <input type="password" class="form-control" id="password-confirm" name="password_confirmation" require autofocus>
                @if ($errors->has('password_confirmation'))
                  <span class="text-danger">
                    <strong>{{$errors->first('password_confirmation')}}</strong>
                  </span>
                @endif
              </div>
            </div>

            <div class="col-md-6 offset-md-4">
              <button type="submit" class="btn btn-primary">
                Reset Password
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection