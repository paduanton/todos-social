<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Todos extends Model
{
    use SoftDeletes, Filterable;

    protected $table = 'todos';

    protected $fillable = [
        'users_id', 'title', 'description', 'completed'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    private static $whiteListFilter = [
        'title', 'completed'
    ];

    public function users()
    {
        return $this->belongsTo(Users::class);
    }

    public function images()
    {
        return $this->hasMany(TodosImages::class);
    }

    public function users_comments()
    {
        return $this->belongsToMany(Users::class, 'comments', 'todos_id','users_id');
    }
}
