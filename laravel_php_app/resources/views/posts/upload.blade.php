@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header text-bg-success">
          Upload CSV File
        </div>
      </div>
      <div class="card">
        @if(session('error'))
        <div class="alert alert-danger">
          {{session('error')}}
        </div>
        @endif
        @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
        @endif
        @if (Session::has('message'))
        <div class="auto-close alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
          <div>
            <span><i class="fa-solid fa-check"></i></span>
            {{session::get('message')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        </div>
        @endif
        <form action="{{'/post/upload'}}" enctype="multipart/form-data" method="POST">
          @csrf
          <div class="form-group row card-body">
            <label for="csv_file" class="col-md-4 col-form-label text-md-right required">{{__('CSV FILE')}}</label>
            <div class="col-md-6">
              <input type="file" id="csv_file" class="csv-file form-control @error('csv_file') is-invalid @enderror" name="csv_file" value="{{ old('csv_file')}}" autocomplete="csv_file" autofocus>
              @error('csv_file')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
          </div>
          <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
              <button type="submit" class="btn btn-success">{{__('Upload')}}</button>
              <button type="reset" class="btn btn-secondary">{{__('Clear')}}</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection