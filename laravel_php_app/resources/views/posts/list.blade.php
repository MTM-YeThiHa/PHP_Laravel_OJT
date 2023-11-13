@extends('layouts.app')
@section('content')

<!-- Style -->
<link rel="stylesheet" href="{{asset('css/post-list.css')}}">
<!-- <link href="{{ asset('css/lib/jquery.dataTables.min.css') }}" rel="stylesheet"> -->
<!-- Script -->
<script src="{{ asset('js/post-list.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script src="{{ asset('js/lib/jquery.dataTables.min.js') }}"></script> -->

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-16">
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
                    <h5>{{ __('Post List') }}</h5>
                </div>
                <div class="card-body">
                    <div class="container">
                        <div class="row d-flex justify-content-end">
                            <div class="col-auto d-flex">
                                <label class="p-2 search-lbl fw-semibold">Keyword:</label>
                                <form method="post" action="{{ route('postSearch') }}" class="d-flex">
                                    @csrf
                                    <div class="mx-1">
                                        <input class="search-input mb-2 form-control" name="search" type="text" id="search-keyword-sm" placeholder="Search..." />
                                    </div>
                                    <div class="mx-1">
                                        <button type="submit" class="btn btn-success mb-2 search-btn" id="search-click">Search</button>
                                    </div>
                                </form>
                                @if(auth()->user() && (auth()->user()->type == 0 || auth()->user()->type == 1))
                                <a class="btn btn-success header-btn" href="/post/create">{{ __('Create') }}</a>
                                <a class="btn btn-success header-btn" href="/post/upload">{{ __('Upload') }}</a>
                                @endif
                                @if($search)
                                <a class="btn btn-success header-btn" href="{{ url('/post/download/filtered/' . $search) }}">{{ __('Download') }}</a>
                                @else
                                <a class="btn btn-success header-btn" href="{{ url('/post/download') }}">{{ __('Download') }}</a>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="post-list">
                            <thead class="table-success">
                                <tr>
                                    <th class="header-cell" scope="col">Post Title</th>
                                    <th class="header-cell" scope="col">Post Description</th>
                                    <th class="header-cell" scope="col">Posted User</th>
                                    <th class="header-cell" scope="col">Posted Date</th>
                                    @if(auth()->user() && (auth()->user()->type == 0 || auth()->user()->type == 1))
                                    <th class="header-cell" scope="col">Operation</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($postList->isEmpty())
                                <tr>
                                    <td colspan="12" class="table-active text-center">No Post Available!</td>
                                </tr>
                                @else
                                @foreach ($postList as $post)
                                <tr>
                                    <td class="abbreviation">
                                        <a class="post-name text-decoration-none abbreviation" type="button" onclick="showPostDetail({{json_encode($post)}})" data-bs-toggle="modal" data-bs-target="#post-detail-popup">{{$post->title}}</a>
                                    </td>
                                    <td class="abbreviation">{{$post->description}}</td>
                                    <td class="abbreviation">{{$post->created_user}}</td>
                                    <td>{{date('Y/m/d', strtotime($post->created_at))}}</td>
                                    @if(auth()->user() && (auth()->user()->type == 0 || $post->created_user_id == auth()->user()->id))
                                    <td>
                                        <a type="button" class="btn btn-primary" href="/post/edit/{{$post->id}}"><i class="fa fa-edit"></i>Edit</a>
                                        <button onclick="showDeleteConfirm({{json_encode($post)}})" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#post-delete-popup"><i class="fa fa-trash"></i>Delete</button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">{{$postList->links()}}</div>
                    <div class="modal fade" id="post-detail-popup" tabindex="-1" aria-label="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header text-bg-success">
                                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Post Detail') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    </button>
                                </div>
                                <div class="modal-body" id="post-detail">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <label class="col-md-4 text-md-left">{{ __('Title') }}</label>
                                            <label class="col-md-8 text-md-left">
                                                <i class="post-text" id="post-title"></i>
                                            </label>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-4 text-md-left">{{ __('Description') }}</label>
                                            <label class="col-md-8 text-md-left">
                                                <i class="post-text" id="post-description"></i>
                                            </label>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-4 text-md-left">{{ __('Status') }}</label>
                                            <label class="col-md-8 text-md-left">
                                                <i class="post-text" id="post-status"></i>
                                            </label>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-4 text-md-left">{{ __('Created Date') }}</label>
                                            <label class="col-md-8 text-md-left">
                                                <i class="post-text" id="post-created-at"></i>
                                            </label>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-4 text-md-left">{{ __('Created User') }}</label>
                                            <label class="col-md-8 text-md-left">
                                                <i class="post-text" id="post-created-user"></i>
                                            </label>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-4 text-md-left">{{ __('Updated Date') }}</label>
                                            <label class="col-md-8 text-md-left">
                                                <i class="post-text" id="post-updated-at"></i>
                                            </label>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-4 text-md-left">{{ __('Updated User') }}</label>
                                            <label class="col-md-8 text-md-left">
                                                <i class="post-text" id="post-updated-user"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="post-delete-popup" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header text-bg-danger">
                                    <h5 class="modal-title">{{ __('Delete Confirm') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    </button>
                                </div>
                                <div class="modal-body" id="post-delete">
                                    <h4 class="delete-confirm-header">Are you sure to delete post?</h4>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <label class="col-md-4 text-md-left">{{ __('ID') }}</label>
                                            <label class="col-md-8 text-md-left">
                                                <i class="post-text" id="post-id"></i>
                                            </label>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-4 text-md-left">{{ __('Title') }}</label>
                                            <label class="col-md-8 text-md-left">
                                                <i class="post-text" id="post-title"></i>
                                            </label>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-4 text-md-left">{{ __('Description') }}</label>
                                            <label class="col-md-8 text-md-left">
                                                <i class="post-text" id="post-description"></i>
                                            </label>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-4 text-md-left">{{ __('Status') }}</label>
                                            <label class="col-md-8 text-md-left">
                                                <i class="post-text" id="post-status"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button onclick="deletePostById({{json_encode(csrf_token())}})" type="button" class="btn btn-danger">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection