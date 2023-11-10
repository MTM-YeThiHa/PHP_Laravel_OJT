@extends('layouts.app')
@section('content')

<!-- Bootstrap Link -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h5 class="card-header text-bg-success">Create Post</h5>
                    <div class="card-body">
                        <form method="POST" action="{{ route('create.post') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row mb-2">
                                <label for="title" class="col-md-4 col-form-label text-md-right required">Title:</label>
                                <div class="col-md-6">
                                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" autocomplete="title" autofocus />
                                    @error('title')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror('title')
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="description" class="col-md-4 col-form-label text-md-right required">Description</label>
                                <div class="col-md-6">
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" autocomplete="description">{{old('description')}}</textarea>
                                    @error('description')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror('description')
                                </div>
                            </div>
                            <div class="form-group row mb-0 mt-2">
                                <div class="col-md-6 offset-md-4 d-flex justify-content-around">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                    <button type="reset" class="btn btn-secondary">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap js link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
@endsection