<?php

namespace MD0\BackpackReGenerator\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'reports';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['name', 'title', 'report_type', 'tag', 'db_name', 'sql_query', 'formatting', 'aggregates', 'pdf', 'csv', 'chart', 'parameters'];
    protected $fakeColumns = ['formatting', 'aggregates', 'pdf', 'csv', 'chart', 'parameters'];
    protected $casts = [
        'formatting' => 'array',
        'aggregates' => 'array',
        'pdf' => 'array',
        'csv' => 'array',
        'chart' => 'array',
        'parameters' => 'array'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeTag($query, $tag)
    {
        return $query->where('tag', 'like', $tag . '%');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
