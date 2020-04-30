<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TodosImages extends Model
{
    use SoftDeletes;

    protected $table = 'todos_images';

    protected $fillable = [
        'thumbnail', 'picture_url', 'filename', 'path', 'mime', 'original_filename', 'original_extension'
    ];

    protected $hidden = [
        'deleted_at'
    ];
    
    public function todos()
    {
        return $this->belongsTo(Todos::class);
    }
}
