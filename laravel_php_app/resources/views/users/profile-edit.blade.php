@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      @if (Session::has('message'))
      <div class="alert alert-success d-flex align-items-center alert-dismissible fade show mx-auto" style="max-width: 25%;" role="alert">
        <div>
          <span><i class="fa-solid fa-check"></i></span>
          {{session::get('message')}}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
      @endif
      <div class="card">
        <div class="card-header text-bg-success">
          <h5>{{ __('Profile Edit') }}</h5>
        </div>

        <div class="card-body">
          <form method="POST" action="{{ route('profile.edit') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group row">
              <label for="name" class="col-md-4 col-form-label text-md-right required">{{ __('Name') }}</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required autocomplete="name" autofocus>

                @error('name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="email" class="col-md-4 col-form-label text-md-right required">{{ __('E-Mail Address') }}</label>
              <div class="col-md-6">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required autocomplete="email">
                @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="type" class="col-md-4 col-form-label text-md-right required" id="type">{{ __('Type') }}</label>
              <div class="col-md-6">
                @if(Auth::user()->type == '0')
                <select class="form-control @error('type') is-invalid @enderror" name="type">
                  @foreach (config('usertype.user_types') as $index => $type)
                  @if($user->type == $index)
                  <option value="{{ $index }}" selected>{{ $type }}</option>
                  @else
                  <option value="{{ $index }}">{{ $type }}</option>
                  @endif
                  @endforeach
                </select>
                @else
                <select class="form-control @error('type') is-invalid @enderror" name="type" disabled>
                  <option value="0" {{ $user->type == '0' ? 'selected' : '' }}>Admin</option>
                  <option value="1" {{ $user->type == '1' ? 'selected' : '' }}>User</option>
                </select>
                @endif
                @error('type')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>
              <div class="col-md-6">
                <input id="phone" type="number" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{$user->phone}}" autocomplete="phone">
                @error('phone')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="dob" class="col-md-4 col-form-label text-md-right">{{ __('Date of Birth') }}</label>

              <div class="col-md-6">
                <input id="dob" type="date" class="form-control @error('dob') is-invalid @enderror" name="dob" value="{{ $user->dob }}" autocomplete="dob">

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
                <textarea id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address">{{ $user->address }}</textarea>
                @error('address')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-4 col-form-label text-md-right">{{ __('New Profile') }}</label>
              <div class="col-md-6">
                <input id="profile" type="file" class="profile form-control @error('profile') is-invalid @enderror" name="profile" autocomplete="profile" />
                @error('profile')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="mt-2" id="img-preview"></div>
              </div>
            </div>
            <div class="form-group row">
              <label for="old-profile" class="col-md-4 col-form-label text-md-right">{{ __('Old Profile') }}</label>
              <div class="col-md-6">
                <div class="ratio ratio-1x1 rounded-circle overflow-hidden">
                  <img class="preview-profile card-img-top object-fit-cover" src="{{Storage::url('profiles/') . $user->profile}}" />
                </div>
              </div>
            </div>

            <div class="form-group row mb-0">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-success">
                  {{ __('Edit') }}
                </button>
                <button type="reset" class="btn btn-secondary">
                  {{ __('Clear') }}
                </button>
                <a class="btn btn-link" href="/user/change-password">
                  {{ __('Change Password') }}
                </a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection