<?php

namespace App\Exports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class PostExport implements FromCollection, WithHeadings, WithMapping
{
  /**
   * @return \Illuminate\Support\Collection
   */
  private $data;

  public function __construct($data)
  {
    $this->data = $data;
  }

  public function collection()
  {
    return $this->data;
  }
  // public function columnWidths(): array
  // {
  //     return [
  //         'A' => 20, // Set the width of column A to 15 units
  //         'B' => 20, // Set the width of column B to 20 units
  //         'C' => 20,
  //         'D' => 20, // Set the width of column
  //         'E' => 20, // Set the width of column
  //         'F' => 20,
  //         'G' => 20,
  //         'H' => 25,
  //         'I' => 25,
  //         'J' => 25,
  //     ];
  // }

  public function headings(): array
  {
    return [
      'id',
      'title',
      'description',
      'status',
      'created_user_id',
      'updated_user_id',
      'deleted_user_id',
      'created_at',
      'updated_at',
      'deleted_at',
    ];
  }


  public function map($post): array
  {
    $deletedAt = $post->deleted_at ? date('Y/m/d', strtotime($post->deleted_at)) : '';
    $status = $post->status == '0' ? 'Inactive' : 'Active';
    return [
      $post->id,
      $post->title,
      $post->description,
      $status,
      $post->created_user_id,
      $post->updated_user_id,
      $post->deleted_user_id,
      date('Y/m/d', strtotime($post->created_at)),
      date('Y/m/d', strtotime($post->updated_at)),
      $deletedAt,
    ];
  }
}
