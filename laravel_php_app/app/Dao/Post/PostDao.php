<?php

namespace App\Dao\Post;

use App\Models\Post;
use App\Contracts\Dao\Post\PostDaoInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostDao implements PostDaoInterface
{
    public function savePost(Request $request)
    {
        $post = new Post();
        $post->title = $request['title'];
        $post->description = $request['description'];
        $post->created_user_id = Auth::user()->id ?? 1;
        $post->updated_user_id = Auth::user()->id ?? 1;
        $post->save();
        return $post;
    }

    public function getPostList()
    {
        if (Auth::check()) {
            $loggedInUserId = Auth::user()->id;
            $postList = DB::table('posts as post')
                ->join('users as created_user', 'post.created_user_id', '=', 'created_user.id')
                ->join('users as updated_user', 'post.updated_user_id', '=', 'updated_user.id')
                ->select('post.*', 'created_user.name as created_user', 'updated_user.name as updated_user')
                ->where(function ($query) use ($loggedInUserId) {
                    $query->where('post.status', '!=', '0') // show inactive posts
                        ->orWhere('post.created_user_id', $loggedInUserId); // show posts created by the logged-in user
                })
                ->whereNull('post.deleted_at') // exclude soft-deleted posts
                ->paginate(5);
            return $postList;
        } else {
            // User is not authenticated, show all active posts
            $postList = DB::table('posts as post')
                ->join('users as created_user', 'post.created_user_id', '=', 'created_user.id')
                ->join('users as updated_user', 'post.updated_user_id', '=', 'updated_user.id')
                ->select('post.*', 'created_user.name as created_user', 'updated_user.name as updated_user')
                ->where('post.status', '!=', '0') // show all active posts
                ->whereNull('post.deleted_at') // exclude soft-deleted posts
                ->paginate(5);

            return $postList;
        }
    }

    /**
     * To delete post by id
     * @param string $id post id
     * @param string $deletedUserId deleted user id
     * @return string $message message success or not
     */

    public function deletePostById($id, $deletedUserId)
    {
        $post = Post::find($id);
        if ($post) {
            $post->deleted_user_id = $deletedUserId;
            $post->save();
            $post->delete();
            return 'Deleted successfully';
        }
        return 'Post Not Found';
    }

    /**
     * To get post by id
     * @param string $id post id
     * @return Object $post post object
     */

    public function getPostById($id)
    {
        $post = Post::find($id);
        return $post;
    }

    /**
     * To update post by id
     * @param Request $request request with inputs
     * @param string $id Post id
     * @return Object $post Post Object
     */
    public function updatedPostById(Request $request, $id)
    {
        $post = Post::find($id);
        $post->title = $request['title'];
        $post->description = $request['description'];
        if ($request['status']) {
            $post->status = '1';
        } else {
            $post->status = '0';
        }
        $post->updated_user_id = Auth::user()->id;
        $post->save();
        return $post;
    }

    public function uploadPostCSV(array $validated, $uploadedUserId)
    {
        // Validate the request
        $validator = Validator::make($validated, [
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048']
        ]);

        if ($validator->fails()) {
            return redirect('/post/upload')->withErrors($validator)->with('error', 'Validation error for CSV file.');
        }

        try {
            $path = $validated['csv_file']->getRealPath();

            $csv_data = array_map('str_getcsv', file($path));

            // Get the header row to map column names
            $header = array_shift($csv_data);

            foreach ($csv_data as $index => $row) {
                // Map column names to index
                $rowData = array_combine($header, $row);

                // Validate and save post
                $validator = Validator::make($rowData, [
                    'title' => 'required',
                    'description' => 'required',
                    'status' => ['required', 'in:Active,Inactive'],
                ]);

                if ($validator->fails()) {
                    return redirect('/post/upload')->withErrors($validator)->with('error', 'Row number (' . ($index + 1) . ') has validation errors.');
                }

                try {
                    $post = new Post();
                    $post->title = $rowData['title'];
                    $post->description = $rowData['description'];
                    $post->status = strtolower($rowData['status']) === 'active' ? 1 : 0;
                    $post->created_user_id = $uploadedUserId;
                    $post->updated_user_id = $uploadedUserId;
                    $post->save();
                } catch (\Illuminate\Database\QueryException $e) {
                    $errorCode = $e->errorInfo[1];

                    // Error handling for duplicated post
                    if ($errorCode == '1062') {
                        return redirect('/post/upload')->with('error', 'Row number (' . ($index + 1) . ') has a duplicated title.');
                    }
                }
            }

            return redirect()->route('postlist')->with('success', 'CSV file uploaded successfully.');
        } catch (\Exception $e) {
            return redirect('/post/upload')->with('error', 'An error occurred during the CSV file import.');
        }
    }

    public function filterPost(Request $request)
    {
        $search = $request['search'];
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
        return $postList;
    }
}
