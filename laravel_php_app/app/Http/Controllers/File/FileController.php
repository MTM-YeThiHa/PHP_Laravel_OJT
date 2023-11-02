<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class FileController extends Controller
{
      /**
   * Create a new controller instance.
   *
   * @return <void></void>
   */
  public function __construct()
  {
    //
  }
  /**
   * To get user profile image
   *
   * @return Image user profile if exist.
   */
  // public function getUserProfile($userId, $profileName)
  // {
  //   $path = config('path.profile') . $userId . config('path.separator') . $profileName;
  //   if(!Storage::disk('local')->exists($path)) {
  //     abort(404, "something went wrong");
  //   }
  //   return response()->file(storage_path(config('path.profile_app_path') .$path));
  // }
  public function getUserProfile($userId, $profileName)
  {
    $path = 'images/' . $userId . '/' . $profileName;

    if(!Storage::disk('public')->exists($path)) {
      abort(404, "image file not found");
    }
    $filepath = Storage::disk('public')->get($path);
    return  response()->file($filepath, ['Content-Type' => 'image' ]);
  }
  
}
