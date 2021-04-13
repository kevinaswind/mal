<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Paper extends Model
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

    protected $fillable = ['title', 'topic', 'body', 'complete'];

    public function firstAuthor()
    {
        return $this->hasOne(FirstAuthor::class);
    }

    public function authors()
    {
        return $this->hasMany(Author::class);
    }

    public function status()
    {
        if($this->complete){
            return 'Complete';
        }

        return 'Incomplete';
    }
}
