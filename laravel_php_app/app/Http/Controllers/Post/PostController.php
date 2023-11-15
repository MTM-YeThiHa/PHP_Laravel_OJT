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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
    return redirect()->route('postlist')->with('message', 'Post created successfully');
    // return dd($post);
  }


  /**
   * To check post create form and redirect to confirm page.
   * @param PostCreateRequest $request Request form post create
   * @return View post create confirm
   */

  public function showPostList(Request $request)
  {
    $search = '';
    $pageSize = $request->input('perPage', 6);
    $postList = Post::paginate($pageSize);
    return view('posts.list', compact('postList', 'search'));
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
    return redirect()->route('postlist')->with('message', 'Post updated successfully');
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
    //validate the request 
    $validator = Validator::make($request->all(), $request->rules());
    try {
      Excel::import(new PostImport, $request->file('csv_file'));
      return redirect()->route('postlist')->with('success', 'CSV file uploaded successfully.');
    } catch (\Exception $e) {
      return redirect('/post/upload')->with('error', 'An error occurred during the CSV file import.');
    }
  }

  /**
   * To download post csv file
   * @return File Download CSV file
   */
  public function downloadPostCSV()
  {
    $post = Post::all();
    return Excel::download(new PostExport($post), 'posts.csv');
  }

  public function downloadFilteredPostCSV(Request $request, $search)
  {
    $query = DB::table('posts as post')
        ->join('users as created_user', 'post.created_user_id', '=', 'created_user.id')
        ->join('users as updated_user', 'post.updated_user_id', '=', 'updated_user.id')
        ->select('post.*', 'created_user.name as created_user', 'updated_user.name as updated_user')
        ->whereNull('post.deleted_at');
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('post.title', 'like', "%$search%")
                ->orWhere('post.description', 'like', "%$search%");
        });
    }
    $postList = $query->paginate(5);
    return Excel::download(new PostExport($postList), 'posts.csv');
  }

  public function filterPost(Request $request)
  {
    $search = $request->search;
    $pageSize = $request->input('perPage', 6);
    $postList = Post::where(function ($q) use ($search) {
      $q->where('title', 'like', "%$search%")
        ->orWhere('description', 'like', "%$search%");
    })
      ->paginate($pageSize);
    $postList->appends(['search' => $search]);
    return view('posts.list', compact('postList', 'search'));
  }
}
