<?php

namespace App\Contracts\Dao\Post;

use Illuminate\Http\Request;

/**
 * Interface of Data Access Object for Post
 */
interface PostDaoInterface
{
    /**
   * To Save Post 
   * @param Request $request request including inputs
   * @return Object created post object
   */
  public function savePost(Request $request);

  /**
   * To get post list
   * @return $postList
   */
  public function getPostList();

  /**
   * To delete post by id
   * @param string $id post id
   * @param string $deletedUserId deleted user id
   * @return string $message message success or not
   */
  public function deletePostById($id, $deletedUserId);

   /**
   * To get post by id
   * @param string $id post id
   * @return Object $post post object
   */
  public function getPostById($id);

   /**
   * To update post by id
   * @param Request $request request with inputs
   * @param string $id Post id
   * @return Object $post Post Object
   */
  public function updatedPostById(Request $request, $id);

    /**
   * To upload csv file for post
   * @param array $validated Validated values
   * @param string $uploadedUserId uploaded user id
   * @return array $content Message and Status of CSV Uploaded or not
   */
  public function uploadPostCSV($validated, $uploadedUserId);

   /**
   * To filter post by filter post
   * @param array $validated Validated values from request
   * @param string $postId Post id
   * @return Object $post Post Object
   */
  public function filterPost(Request $request);
}