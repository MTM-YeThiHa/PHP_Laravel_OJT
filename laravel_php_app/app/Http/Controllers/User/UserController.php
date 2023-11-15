<?php

namespace App\Http\Controllers\User;

use App\Contracts\Services\User\UserServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserPasswordChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
  /**
   * user interface
   */
  private $userInterface;
  /**
   * Create a new controller instance.
   * @param UserServiceInterface $userServiceInterface
   * @return void
   */
  public function __construct(UserServiceInterface $userServiceInterface)
  {
    $this->middleware('auth');
    $this->userInterface = $userServiceInterface;
  }

  /**
   * To show user list
   *
   * @return View User list
   */
  public function showUserList(Request $request)
  {
    $pagesize = 6;
        if($request['perPage']){
            $pagesize = $request['perPage'];
        }
        $userList = DB::table('users as user')
            ->join('users as created_user', 'user.created_user_id', '=', 'created_user.id')
            ->join('users as updated_user', 'user.updated_user_id', '=', 'updated_user.id')
            ->select('user.*', 'created_user.name as created_user', 'updated_user.name as updated_user')
            ->whereNull('user.deleted_at')
            ->orderBy('user.created_at','desc')
            ->Paginate($pagesize);
        return view('users.list', compact('userList')); //crd YHA
    // $pageSize = $request->input('perPage', 6);
    // $userList = User::paginate($pageSize);
    // return view('users.list', compact('userList'));
  }

  /**
   * To Show the application dashboard.
   *
   * @return View change password view
   */
  public function showChangePasswordView()
  {
    return view('users.change-password');
  }

  /**
   * To Show the application dashboard.
   * @param UserPasswordChangeRequest $request request for password change
   * @return View user profile
   */
  public function savePassword(UserPasswordChangeRequest $request)
  {
    // validation for request values
    $validated = $request->validated();
    $user = $this->userInterface->changeUserPassword($validated);
    return redirect()->route('profile');
  }

  /**
   * To show user profile
   *
   * @return View UserProfile
   */

  public function showUserProfile()
  {
    $userId = Auth::user()->id;
    $user = $this->userInterface->getUserById($userId);
    return view('users.profile', compact('user'));
  }


  public function showUserProfileEdit()
  {
    $userId = Auth::user()->id;
    $user = $this->userInterface->getUserById($userId);
    return view('users.profile-edit', compact('user'));
  }

  /**
   * To check profile edit form is valid or not.
   * If valid will return to profile edit confim page.
   * If not, redirect to profile edit page.
   * 
   * @param UserEditRequest $request request from profile edit
   * @return View profile edit confirm
   */

  public function submitEditProfileView(UserEditRequest $request)
  {
    $validated = $request->validated();
    if ($request->hasFile('profile')) {
      $fileName = time() . Auth::user()->id . '.' . $request->file('profile')->getClientOriginalExtension();
      $request->file('profile')->storeAs('public/profiles/', $fileName);
      session(['profileName' => $fileName]);
    } else {
      $profileName = $request->user()->profile;
      $fileName = $profileName;
    }
    return redirect()
      ->route('profile.edit.confirm')
      ->withInput()
      ->with('profile', $fileName);
  }

  //showEditProfileConfirmView
  public function showEditProfileConfirmView()
  {
    if (old()) {
      return view('users.profile-edit-confirm');
    }
    return redirect()->route('userlist')->with('message', 'user created successfully');
  }

  /**
   * To submit profile edit confirmation view
   * @param Request $request request from profile edit confirm
   * @return View home
   */

  public function submitProfileEditConfirmView(Request $request)
  {
    $user = $this->userInterface->updateUser($request);
    return redirect()->route('profile')->with('message', 'User profile edited successfully');
  }

  public function userSearch(Request $request)
  {
    $pageSize = $request->input('perPage', 6);
    $userList = User::paginate($pageSize);
    $userList = $this->userInterface->userSearch($request);
    return view('users.list', compact('userList'));
  }

  /**
   * To delete user by id
   * @param string $userid user id
   * @return View user list
   */
  public function deleteUserById(Request $request)

  {
    $msg = $this->userInterface->deleteUserById($request);
    return redirect()->route('userlist')->with('message', 'User deleted successfully');
  }
}
