<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Musics
 *
 * @property int $id
 * @property string $music_id
 * @property string $play_url
 * @property string $title
 * @property int $duration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Musics newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Musics newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Musics query()
 * @method static \Illuminate\Database\Eloquent\Builder|Musics whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Musics whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Musics whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Musics whereMusicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Musics wherePlayUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Musics whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Musics whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Musics extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'musics';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
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
