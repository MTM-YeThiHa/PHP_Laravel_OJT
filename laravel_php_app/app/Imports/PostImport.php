<?php

namespace App\Imports;

use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithUpserts;

class PostImport implements ToModel, WithValidation, WithUpserts
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        return new Post([
            'title' => $row[1],
            'description' => $row[2],
            'status' => (int)$row[3],
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
