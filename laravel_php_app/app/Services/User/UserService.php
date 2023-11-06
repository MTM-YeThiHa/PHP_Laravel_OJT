<?php

namespace App\Services\User;

use App\Contracts\Dao\User\UserDaoInterface;
use App\Contracts\Services\User\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserService implements UserServiceInterface
{
  //user Dao
  private $userDao;

  //userDaoInterface
  public function __construct(UserDaoInterface $userDao)
  {
    $this->userDao = $userDao;
  }

  //getUserById($id)
  public function getUserById($id)
  {
    return $this->userDao->getUserById($id);
  }

  //getUserList
  public function getUserList()
  {
    return $this->userDao->getUserList();
  }

  //updateUser
  public function updateUser(Request $request)
  {
    $user = $this->userDao->updateUser($request);
    // if ($request['profile']) {
    //   Storage::move(
    //     config('path.public_tmp') . $request['profile'],
    //     config('path.profile') . Auth::user()->id . config('path.separator') . $request['profile']
    //   );
    // }
    return $user;
  }

  //changeUserPassword
  public function changeUserPassword($validated)
  {
    return $this->userDao->changeUserPassword($validated);
  }

  /**
   * To delete user by id
   * @param string $id user id
   * @param string $deletedUserId deleted user id
   * @return string $message message for success or not
   */
  public function deleteUserById(Request $request)
  {
    return $this->userDao->deleteUserById($request);
  }

  //storeProfile Under Temp
  public function storeProfileUnderTemp($validated)
  {
    $profileName = Auth::user()->profile;
    $profilePath = config('path.separator') .
      config('path.profile') . Auth::user()->id .
      config('path.separator') . Auth::user()->profile;

    //store and set new profile in request
    if(array_key_exists('profile', $validated)){
      $profileName = time() . '.' . $validated['profile']->extension();
      Storage::putFileAs(config('path.public_tmp'), $validated['profile'], $profileName);
      $profilePath = Storage::url(config('path.tmp_path') . $profileName);
    }

    $profile = array('name' => $profileName, 'path' => $profilePath);
    return $profile;
  }

  //saveUser
  public function saveUser($validated)
  {
   $user = $this->userDao->saveUser($validated);
   Storage::putFileAs(
    config('path.profile') . $user->id,
    $validated['profile'],
    $user->profile
   );
   return $user;
  }

  //user search
  public function userSearch(Request $request)
  {
    $user = $this->userDao->userSearch($request);
    return $user;
  }
  
}
