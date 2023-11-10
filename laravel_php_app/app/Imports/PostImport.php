<?php

namespace App\Imports;

use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithUpserts;

class  PostImport implements ToModel, WithValidation, WithUpserts
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        $title = $row['title'];
        $description = $row['description'];
        $status = $row['status'];
        return new Post([
            'title' => $title,
            'description' => $description,
            'status' => (int)$status,
            'created_user_id' => Auth::user()->id ?? $row['created_user_id'],
            'updated_user_id' => Auth::user()->id ?? $row['updated_user_id'],
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required',
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
        ];
    }

    public function uniqueBy()
    {
        return 'id';
    }
}
