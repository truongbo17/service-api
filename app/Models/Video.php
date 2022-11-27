<?php

namespace App\Models;

use App\Libs\DiskPathTools\DiskPathInfo;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\FilesystemException;
use Storage;

/**
 * App\Models\Video
 *
 * @property int $id
 * @property string $video_id
 * @property string|null $music_id
 * @property string|null $author_id
 * @property string|null $region
 * @property string $title
 * @property string $hash_title
 * @property string|null $storage_file
 * @property int $duration
 * @property int $play_count
 * @property int $digg_count
 * @property int $comment_count
 * @property int $share_count
 * @property int $download_count
 * @property int $height
 * @property int $width
 * @property string $frame
 * @property string $create_time
 * @property int $is_trending
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Video newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Video newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Video query()
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereCommentCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereDiggCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereDownloadCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereHashTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereIsTrending($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereMusicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video wherePlayCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereShareCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereStorageFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereVideoId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereWidth($value)
 */
class Video extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'videos';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    protected $dates = [
        'create_time'
    ];

    /**
     * Download video
     *
     * @return string
     * @throws FilesystemException
     */
    function downloadVideo(): string
    {
        if (is_string($this->storage_file)) {
            if (!DiskPathInfo::parse($this->storage_file)) {
                if (Storage::disk(config('crawl.disk_video'))->has($this->storage_file)) {
                    return "<a target='_blank' class='btn btn-sm btn-link' href='" . route('video.download', [
                            'disk' => config('crawl.disk_video'),
                            'file_path' => $this->storage_file,
                        ]) . "'><i class='las la-file-download'></i>Download Video</a>";
                }
            } else {
                if (DiskPathInfo::parse($this->storage_file)->exists()) {
                    return "<a target='_blank' class='btn btn-sm btn-link' href='" . route('video.download', [
                            'disk' => DiskPathInfo::parse($this->storage_file)->bestDisk(),
                            'file_path' => DiskPathInfo::parse($this->storage_file)->path(),
                        ]) . "'><i class='las la-file-download'></i>Download Video</a>";
                }
            }
        }
        return "<a class='btn btn-sm text-danger' disabled><i class='las la-times-circle'></i>No Video</a>";
    }
}
