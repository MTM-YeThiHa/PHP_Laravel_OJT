<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Post;

class PostExport implements FromCollection, WithHeadings
{
    use Exportable;

    private $search;

    public function __construct($search)
    {
        $this->search = $search;
    }

    public function collection()
    {
        $query = Post::select(
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
        );

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%$this->search%")
                    ->orWhere('description', 'like', "%$this->search%");
            });
        }
        echo $this->search;
        return $query->get();
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
