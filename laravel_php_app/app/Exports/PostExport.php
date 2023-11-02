<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Post;

class PostExport implements FromCollection, FromQuery, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;

    public function collection()
    {
        return Post::select(
            'id',
            'title',
            'description',
            'status',
            'created_user_id',
            'updated_user_id',
            'deleted_user_id',
            'deleted_at',
            'created_at',
            'updated_at'
        )->get();
    }

    public function query()
    {
        return Post::select('id', 'title', 'description', 'status', 'created_user_id', 'updated_user_id', 'deleted_user_id', 'deleted_at', 'created_at', 'updated_at')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Title', 'Description', 'Status', 'Created User ID', 'Updated User ID', 'Deleted User ID', 'Deleted At', 'Created At', 'Updated At'];
    }
}
