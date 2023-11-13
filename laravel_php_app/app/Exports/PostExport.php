<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

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
      DB::raw('(CASE WHEN status = 1 THEN "Active" ELSE "Inactive" END) as status_text'),
      DB::raw('(SELECT name FROM users WHERE id = posts.created_user_id) as created_user_name'),
      DB::raw('(SELECT name FROM users WHERE id = posts.updated_user_id) as updated_user_name'),
      DB::raw('(SELECT name FROM users WHERE id = posts.deleted_user_id) as deleted_user_name'),
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
    return $query->get();
  }

  public function headings(): array
  {
    return ['ID', 'Title', 'Description', 'Status', 'Created User ID', 'Updated User ID', 'Deleted User ID', 'Deleted At', 'Created At', 'Updated At'];
  }
}
