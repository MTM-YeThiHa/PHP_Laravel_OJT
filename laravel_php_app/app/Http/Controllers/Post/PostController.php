<?php

namespace App\Http\Controllers\Post;

use App\Contracts\Services\Post\PostServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostEditRequest;
use App\Http\Requests\PostUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PostExport;
use App\Imports\PostImport;

class PostController extends Controller
{
  /**
   * post interface
   */
  private $postInterface;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(PostServiceInterface $postServiceInterface)
  {
    $this->postInterface = $postServiceInterface;
  }

  /**
   * To show create post view
   * 
   * @return View create post
   */
  public function showPostCreateView()
  {
    return view('posts.create');
    // return "This is post created page";
  }

  /**
   * To check post create form and redirect to confirm page.
   * @param PostCreateRequest $request Request form post create
   * @return View post create confirm
   */
  public function submitPostCreateView(PostCreateRequest $request)
  {
    // validation for request values
    $validated = $request->validated();
    return redirect()
      ->route('post.create.confirm')
      ->withInput();
    return redirect()->route('postlist');
  }

  /**
   * To show post create confirm view
   *
   * @return View post create confirm view
   */
  public function showPostCreateConfirmView()
  {
    if (old()) {
      return view('posts.create-confirm');
    }
    return redirect()->route('postlist');
  }

  /**
   * To submit post create confirm view
   * @param Request $request
   * @return View post list
   */
  public function submitPostCreateConfirmView(Request $request)
  {
    $post = $this->postInterface->savePost($request);
    return redirect()->route('postlist');
    // return dd($post);
  }


  /**
   * To check post create form and redirect to confirm page.
   * @param PostCreateRequest $request Request form post create
   * @return View post create confirm
   */

  public function showPostList()
  {
    $posts = Post::paginate(5);
    $postList = $this->postInterface->getPostList();
    return view('posts.list', compact('postList'));
  }

  //delete post by Id
  public function deletePostById($postId)
  {
    $deletedUserId = Auth::user()->id;
    $msg = $this->postInterface->deletePostById($postId, $deletedUserId);
    return response($msg, 204);
  }

  //update post by Id
  public function showPostEdit($postId)
  {
    $post = $this->postInterface->getPostById($postId);
    return view('posts.edit', compact('post'));
  }

  /**
   * Submit post edit
   * @param Request $request
   * @param $postId
   * @return View post edit confirm view
   */
  public function submitPostEditView(PostEditRequest $request, $postId)
  {
    //validation for the request values
    $validated = $request->validated();
    return redirect()
      ->route('posts.edit.confirm', [$postId])
      ->withInput();
  }

  /**
   * To show post edit confirm view
   * @param $postId
   * @return View post edit confirm view
   */

  public function showPostEditConfirmView($postId)
  {
    if (old()) {
      return view('posts.edit-confirm');
    }
    return redirect()->route('postlist');
  }

  /**
   * To submit profile edit confirmation view
   * @param Request $request Request from post edit confirm
   * @param string $postId Post id
   * @return View post list
   */
  public function submitPostEditConfirmView(Request $request, $postId)
  {
    $user =  $this->postInterface->updatedPostById($request, $postId);
    return redirect()->route('postlist');
  }

  /**
   * To show create post view
   * 
   * @return View create post
   */
  public function showPostUploadView()
  {
    return view('posts.upload');
  }

  /**
   * To submit CSV upload view
   * 
   * @param Request $request Request from post upload
   * @return view post list
   */
  public function submitPostUploadView(PostUploadRequest $request)
  {
    // validation for request values
    $validated = $request->validated();
    $uploadedUserId = Auth::user()->id;
    $content = $this->postInterface->uploadPostCSV($validated, $uploadedUserId);
    if (!$content['isUploaded']) {
      return redirect('/post/upload')->with('error', $content['message']);
    } else {
      return redirect()->route('postlist');
    }
    // $request->validate([
    //   'csv_file' => 'required | file | mimes:csv',
    // ]);
    // $file = $request->file('csv_file');
    // Excel::import(new PostImport, $file);
    // return redirect()->route('postlist')->with('success', 'CSV file uploaded successfully.');
  }

  /**
   * To download post csv file
   * @return File Download CSV file
   */
  public function downloadPostCSV()
  {
    return Excel::download(new PostExport(), 'posts.csv');
  }

  public function filterPost(Request $request)
  {
    $postList = $this->postInterface->filterPost($request);
    return view('posts.list', compact('postList'));
  }
}
