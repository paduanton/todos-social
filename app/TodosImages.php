<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecipesImages extends Model
{
    use SoftDeletes;

    protected $table = 'todos_images';

    protected $fillable = [
        'thumbnail', 'picture_url', 'filename', 'path', 'mime', 'original_filename', 'original_extension'
    ];

    public function recipes()
    {
        return $this->belongsTo(Recipes::class);
    }
}
