@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header text-bg-success">
          <h3>{{ __('Profile Edit Confirm') }}</h3>
        </div>

        <div class="card-body">
          <form method="POST" action="{{ route('profile.edit.confirm') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group row">
              <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" autocomplete="name" readonly="readonly">
              </div>
            </div>

            <div class="form-group row">
              <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

              <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" readonly="readonly">
              </div>
            </div>

            <div class="form-group row">
              <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>
              <div class="col-md-6">
                @if(old('type') == '0')
                <input id="type" type="text" class="form-control" name="type" value="Admin" readonly="readonly" />
                @else(old('type') == '1')
                <input id="type" type="text" class="form-control" name="type" value="User" readonly="readonly" />
                @endif
                
              </div>
            </div>

            <div class="form-group row">
              <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>

              <div class="col-md-6">
                <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" readonly="readonly">
              </div>
            </div>

            <div class="form-group row">
              <label for="dob" class="col-md-4 col-form-label text-md-right">{{ __('Date of Birth') }}</label>

              <div class="col-md-6">
                <input id="dob" type="date" class="form-control" name="dob" value="{{ old('dob') }}" readonly="readonly">

                @error('dob')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

              <div class="col-md-6">
                <textarea d="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" readonly="readonly">{{old('address')}}</textarea>
                @error('address')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-4 col-form-label text-md-right">{{ __('Profile') }}</label>
              <div class="col-md-6">
                <div class="ratio ratio-1x1 rounded-circle overflow-hidden">
                  <input id="profile" type="text" class="form-control hide-input" name="profile" required value="{{ session('profile') }}" autocomplete="profile" readonly="readonly" />
                  <img class="preview-profile card-img-top object-fit-cover" src="{{Storage::url('profiles/') . Session::get('profile')}}" />
                </div>

              </div>
            </div>

            <div class="form-group row mb-0">
              <div class="col-md-6 offset-md-4 d-flex justify-content-around">
                <button type="submit" class="btn btn-success">
                  {{ __('Confirm') }}
                </button>
                <a class="cancel-btn btn btn-secondary" onClick="window.history.back()">{{ __('Cancel') }}</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection