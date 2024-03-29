<?php

namespace App\Imports;

use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class  PostImport implements ToModel, WithUpserts, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $collection
     */

     public function model(array $row)
     {
        
         return new Post([
             'title'=>$row['title'],
             'description'=>$row['description'],
             'status'=>$row['status'],
             'created_user_id'=>Auth::user()->id?? $row['created_user_id'],
             'updated_user_id'=>Auth::user()->id?? $row['updated_user_id'],
         ]);
     }

     public function rules(): array 
     {
        return [
            'title' => ['required', Rule::unique('posts', 'title')],
            'description' => 'required',
            'status' => 'required',
        ];
     }

    public function ValidationMessages()
    {
        return [
            'title.required' => 'The title field is required',
            'description.required' => 'The description field is required',
            'status.required' => 'The status field is required',
            'status.in' => 'The status must be either "Active" or "Inactive',
            'title.unique' => 'The title must not be the same',
        ];
    }

    public function uniqueBy()
    {
        return 'id';
    }
}
