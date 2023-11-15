<?php

namespace App\Dao\User;

use App\Contracts\Dao\User\UserDaoInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Data Access Object of User
 */
class UserDao implements UserDaoInterface
{
  /**
   * To get user by id
   * @param string $id user id
   * @return Object $user user object
   */
  public function getUserById($id)
  {
    $user = User::find($id);
    return $user;
  }

  //To get user List
  public function getUserList()
  {
    $userList = DB::table('users as user')
      ->join('users as created_user', 'user.created_user_id', '=', 'created_user.id')
      ->join('users as updated_user', 'user.updated_user_id', '=', 'updated_user.id')
      ->select('user.*', 'created_user.name as created_user', 'updated_user.name as updated_user')
      ->whereNull('user.deleted_at');
    return $userList;
  }

  //save User
  public function saveUser($validated)
  {
    $profileName = session('profileName') ?? '';
    $user = new User();
    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->password = Hash::make($validated['password']);
    $user->profile = $profileName ?? '';
    $user->type = $validated['type'] ?? '1';
    $user->type = $validated['phone'] ?? '';
    $user->dob = empty($validated['dob']) ? now() : $validated['dob'];
    $user->address = $validated['address'] ?? '';
    $user->created_user_id = Auth::user()->id ?? '1';
    $user->updated_user_id = Auth::user()->id ?? '1';
    $user->save();
    return $user;
  }

  //update user
  public function updateUser(Request $request)
  {
    // dd($request);
    $user = User::find(Auth::user()->id);
    $type = $request['type'] === 'User' ? 1 : 0;
    $user->name = $request['name'];
    $user->email = $request['email'];
    $user->profile = $request['profile'];
    $user->type = $type;
    $user->phone = $request['phone'];
    $user->dob = $request['dob'];
    $user->address = $request['address'];
    $user->updated_user_id = Auth::user()->id;
    $user->save();
    return $user;
  }

  //change user password
  public function changeUserPassword($validated)
  {
    $user = User::find(auth()->user()->id)
      ->update([
        'password' => Hash::make($validated['new_password']),
        'updated_user_id' => Auth::user()->id
      ]);
    return "password change";
  }

  /**
   * To delete user by id
   * @param string $id user id
   * @param string $deletedUserId deleted user id
   * @return string $message message for success or not
   */
  public function deleteUserById(Request $request)
  {
    $user = User::find($request['deleteId']);
    if ($user) {
      $user->deleted_user_id = Auth::user()->id;
      $user->save();
      $user->delete();
    }
  }

  public function userSearch(Request $request)
  {
    $name = $request->input('name');
    $email = $request->input('email');
    $fromDate = $request->input('fromDate');
    $toDate = $request->input('toDate');

    $searchKeywords = [];
    $userList = DB::table('users as user')
      ->join('users as created_user', 'user.created_user_id', '=', 'created_user.id')
      ->join('users as updated_user', 'user.updated_user_id', '=', 'updated_user.id')
      ->select('user.*', 'created_user.name as created_user', 'updated_user.name as updated_user')
      ->whereNull('user.deleted_at');

    if ($name && $email) {
      $userList->where(function ($query) use ($name, $email, &$searchKeywords) {
        $query->where('user.name', 'like', '%' . $name . '%');
        $query->where('user.email', 'like', '%' . $email . '%');
        $searchKeywords[] = "Name: " . $name;
        $searchKeywords[] = "Email: " . $email;
      });
    } elseif ($name) {
      $userList->where('user.name', 'like', '%' . $name . '%');
      $searchKeywords[] = "Name: " . $name;
    } elseif ($email) {
      $userList->where('user.email', 'like', '%' . $email . '%');
      $searchKeywords[] = "Email: " . $email;
    }
    if ($fromDate && $toDate) {
      $userList->whereBetween('user.created_at', [$fromDate, $toDate]);
      $searchKeywords[] = "Date Range: $fromDate - $toDate";
    }
    // $userList = $userList->paginate(5);
    // return view('search-results', ['userList' => $userList, 'searchKeywords' => $searchKeywords]);
    return $userList;
  }
}
