<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Author extends Model
{
    use SoftDeletes;

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected $fillable = ['paper_id', 'uuid', 'seq', 'name', 'affiliation_no', 'is_presenter'];

    public function affiliations()
    {
        return $this->belongsToMany(Affiliation::class);
    }
}
