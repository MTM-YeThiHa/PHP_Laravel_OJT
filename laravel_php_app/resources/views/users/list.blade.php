@extends('layouts.app')

@section('content')
<!-- Styles -->
<!-- <link href="{{ asset('css/lib/jquery.dataTables.min.css') }}" rel="stylesheet"> -->
<link href="{{ asset('css/user-list.css') }}" rel="stylesheet">

<!-- Script -->
<script src="{{ asset('js/lib/moment.min.js') }}"></script>
<!-- <script src="{{ asset('js/lib/jquery.dataTables.min.js') }}"></script> -->
<script src="{{ asset('js/user-list.js') }}"></script>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header text-bg-success rainbow-text animated" data-splitting>
          {{__('User List')}}
        </div>
        <div class="card-body">
          <form method="post" action="{{ route('userSearch') }}" class="col-auto d-flex">
            @csrf

            <div class="d-flex mx-2 justify-content-center align-items-center">
              <label class="mx-1">Name:</label>
              <input class="search-input  form-control" type="text" id="search-name" name="name" />
            </div>
            <div class="d-flex mx-2 justify-content-center align-items-center">
              <label class="mx-1">Email:</label>
              <input class="search-input  form-control" type="text" id="search-email" name="email" />
            </div>
            <div class="d-flex mx-2 justify-content-center align-items-center">
              <label class="mx-1">From:</label>
              <input class="search-input  form-control" id="dateStart" type="date" name="fromDate" />
            </div>
            <div class="d-flex mx-2 justify-content-center align-items-center">
              <label class="mx-1">To:</label>
              <input class="search-input  form-control" id="dateEnd" type="date" name="toDate" />
            </div>
            <button type="submit" class="btn btn-primary  search-btn col-auto" id="search-click">Search</button>

          </form>
          <div class="table-responsive">
            <table class="table table-hover table-bordered">
              <thead class="table-success">
                <tr class="p-3 mb-2 text-white text-nowrap">
                  <th class="py-3">ID</th>
                  <th class="py-3">Name</th>
                  <th class="py-3">Email</th>
                  <th class="py-3">Created User</th>
                  <th class="py-3">Type</th>
                  <th class="py-3">Phone</th>
                  <th class="py-3">Date of Birth</th>
                  <th class="py-3">Address</th>
                  <th class="py-3">Created Date</th>
                  <th class="py-3">Updated Date</th>
                  <th class="py-3">Operation</th>
                </tr>
              </thead>
              <tbody>
                @if ($userList->isEmpty())
                <tr>
                  <td colspan="12" class="table-active text-center">No Data Available!</td>
                </tr>
                @else
                @foreach ($userList as $user)
                <tr>
                  <td>{{$user->id}}</td>
                  <td>
                    <a class="user-name text-decoration-none" onclick="showUserDetail({{json_encode($user)}})" data-toggle="modal" data-target="#user-detail-popup">{{$user->name}}</a>
                  </td>
                  <td>{{$user->email}}</td>
                  <td>{{$user->created_user}}</td>
                  <td>
                    {{$user->type == '0' ? 'Admin' : ($user->type == '1' ? 'User' : '')}}
                  </td>
                  <td>{{$user->phone}}</td>
                  <td>{{date('Y/m/d', strtotime($user->dob))}}</td>
                  <td>{{$user->address}}</td>
                  <td>{{date('Y/m/d', strtotime($user->created_at))}}</td>
                  <td>{{date('Y/m/d', strtotime($user->updated_at))}}</td>
                  <td>
                    @if($user->id != auth()->user()->id)
                    <button type="button" class="btn btn-danger" onclick="showDeleteConfirm({{json_encode($user)}})" data-toggle="modal" data-target="#delete-confirm">Delete</button>
                    @endif
                  </td>
                </tr>
                @endforeach
                @endif
              </tbody>
            </table>
            <div class="d-flex justify-content-end">{{$userList->links()}}</div>
          </div>
          <div class="modal fade" id="user-detail-popup" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header text-bg-success">
                  <h5 class="modal-title">{{ __('User Detail') }}</h5>
                  <button type="submit" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="user-detail">
                  <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-6 text-center">
                      <div class="ratio ratio-1x1 rounded-circle overflow-hidden">
                        <img class="card-img-top object-fit-cover" id="user-profile" src="" alt="Profile Image" class="preview-profile">
                      </div>
                    </div>
                    <div class="col-lg-8 col-md-12 col-sm-6">
                      <div class="row">
                        <label class="col-md-3 text-md-left">{{ __('Name') }}</label>
                        <label class="col-md-9 text-md-left">
                          <i class="profile-text" id="user-name"></i>
                        </label>
                      </div>
                      <div class="row">
                        <label class="col-md-3 text-md-left">{{ __('Type') }}</label>
                        @if($userList[0]->type == '0')
                        <label class="col-md-9 text-md-left">
                          <i class="profile-text" id="user-type">Admin</i>
                        </label>
                        @else
                        <label class="col-md-9 text-md-left">
                          <i class="profile-text" id="user-type">User</i>
                        </label>
                        @endif
                      </div>
                      <div class="row">
                        <label class="col-md-3 text-md-left">{{ __('Email') }}</label>
                        <label class="col-md-9 text-md-left">
                          <i class="profile-text" id="user-email"></i>
                        </label>
                      </div>
                      <div class="row">
                        <label class="col-md-3 text-md-left">{{ __('Phone') }}</label>
                        <label class="col-md-9 text-md-left">
                          <i class="profile-text" id="user-phone"></i>
                        </label>
                      </div>
                      <div class="row">
                        <label class="col-md-3 text-md-left">{{ __('Date of Birth') }}</label>
                        <label class="col-md-9 text-md-left">
                          <i class="profile-text" id="user-dob"></i>
                        </label>
                      </div>
                      <div class="row">
                        <label class="col-md-3 text-md-left">{{ __('Address') }}</label>
                        <label class="col-md-9 text-md-left">
                          <i class="profile-text" id="user-address"></i>
                        </label>
                      </div>
                      <div class="row">
                        <label class="col-md-3 text-md-left">{{ __('Created Date') }}</label>
                        <label class="col-md-9 text-md-left">
                          <i class="profile-text" id="user-created-at"></i>
                        </label>
                      </div>
                      <div class="row">
                        <label class="col-md-3 text-md-left">{{ __('Created User') }}</label>
                        <label class="col-md-9 text-md-left">
                          <i class="profile-text" id="user-created-user"></i>
                        </label>
                      </div>
                      <div class="row">
                        <label class="col-md-3 text-md-left">{{ __('Updated Date') }}</label>
                        <label class="col-md-9 text-md-left">
                          <i class="profile-text" id="user-updated-at"></i>
                        </label>
                      </div>
                      <div class="row">
                        <label class="col-md-3 text-md-left">{{ __('Updated User') }}</label>
                        <label class="col-md-9 text-md-left">
                          <i class="profile-text" id="user-updated-user"></i>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" id="delete-confirm" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header text-bg-danger">
                  <h5 class="modal-title">{{ __('Delete Confirm') }}</h5>
                  <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                  </button>
                </div>
                <div class="modal-body" id="user-delete">
                  <h4 class="delete-confirm-header">{{__('Are you sure to delete user?')}}</h4>
                  <div class="col-md-12">
                    <div class="row">
                      <label class="col-md-3 text-md-left">{{ __('ID') }}</label>
                      <label class="col-md-9 text-md-left">
                        <i class="profile-text" id="user-id"></i>
                      </label>
                    </div>
                    <div class="row">
                      <label class="col-md-3 text-md-left">{{ __('Name') }}</label>
                      <label class="col-md-9 text-md-left">
                        <i class="profile-text" id="user-name"></i>
                      </label>
                    </div>
                    <div class="row">
                      <label class="col-md-3 text-md-left">{{ __('Type') }}</label>
                      <label class="col-md-9 text-md-left">
                        <i class="profile-text" id="user-type"></i>
                      </label>
                    </div>
                    <div class="row">
                      <label class="col-md-3 text-md-left">{{ __('Email') }}</label>
                      <label class="col-md-9 text-md-left">
                        <i class="profile-text" id="user-email"></i>
                      </label>
                    </div>
                    <div class="row">
                      <label class="col-md-3 text-md-left">{{ __('Phone') }}</label>
                      <label class="col-md-9 text-md-left">
                        <i class="profile-text" id="user-phone"></i>
                      </label>
                    </div>
                    <div class="row">
                      <label class="col-md-3 text-md-left">{{__('Date of Birth')}}</label>
                      <label class="col-md-9 text-md-left">
                        <i class="profile-text" id="user-dob"></i>
                      </label>
                    </div>
                    <div class="row">
                      <label class="col-md-3 text-md-left">{{ __('Address') }}</label>
                      <label class="col-md-9 text-md-left">
                        <i class="profile-text" id="user-address"></i>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <form action="{{route('user.delete')}}" method="post">
                    <input type="hidden" name="deleteId" id="deleteId">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                  </form>
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