<?php

namespace App\Services\Post;

use App\Contracts\Dao\Post\PostDaoInterface;
use App\Contracts\Services\Post\PostServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/** 
 * Service of post.
 */

class PostService implements PostServiceInterface
{
    /**
     * post dao
     */
    private $postDao;
    /**
     * class constructor
     * @param PostDaoInterface
     * @return
     */
    public function __construct(PostDaoInterface $postDao)
    {
        $this->postDao = $postDao;
    }

    /**
     * To save post
     * @param Request $request request with inputs
     * @return Object $post saved post
     */

    public function savePost(Request $request)
    {
        return $this->postDao->savePost($request);
    }

    /**
     * To get post list
     * @return array $postList Post list
     */
    public function getPostList(Request $request)
    {
        return $this->postDao->getPostList($request);
    }

    //deletePostById
    public function deletePostById($id, $deletedUserId)
    {
        return $this->postDao->deletePostById($id, $deletedUserId);
    }

    //getPostById
    public function getPostById($id)
    {
        return $this->postDao->getPostById($id);
    }

     /**
   * To update post by id
   * @param Request $request request with inputs
   * @param string $id Post id
   * @return Object $post Post Object
   */
  public function updatedPostById(Request $request, $id)
  {
    return $this->postDao->updatedPostById($request, $id);
  }

   /**
   * To upload csv file for post
   * @param array $validated Validated values
   * @param string $uploadedUserId uploaded user id
   * @return array $content Message and Status of CSV Uploaded or not
   */
  public function uploadPostCSV($validated, $uploadedUserId)
  {
    $fileName = $validated['csv_file']->getClientOriginalName();
    Storage::putFileAs(config('path.csv') . $uploadedUserId .
      config('path.separator'), $validated['csv_file'], $fileName);
    return $this->postDao->uploadPostCSV($validated, $uploadedUserId);
  }

   /**
   * To download post csv file
   * @return File Download CSV file
   */
  public function downloadPostCSV()
  {}

    /**
   * To save post via API
   * @param array $validated Validated values from request
   * @return Object created post object
   */

    /**
   * To update post by id via api
   * @param array $validated Validated values from request
   * @param string $id Post id
   * @return Object $post Post Object
   */

  /**
   * To filter post by filter post
   * @param array $validated Validated values from request
   * @param string $postId Post id
   * @return Object $post Post Object
   */
  public function filterPost(Request $request)
  {
    return $this->postDao->filterPost($request);
  }
}
