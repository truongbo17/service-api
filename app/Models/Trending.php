<?php

namespace App\Models;

use App\Libs\DiskPathTools\DiskPathInfo;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use League\Flysystem\FilesystemException;
use Storage;

/**
 * App\Models\Trending
 *
 * @property int $id
 * @property string $video_id
 * @property int $duration
 * @property string|null $storage_video
 * @property string|null $storage_thumbnail
 * @property string $type_video
 * @property string|null $uploaded_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Trending newModelQuery()
 * @method static Builder|Trending newQuery()
 * @method static Builder|Trending query()
 * @method static Builder|Trending whereCreatedAt($value)
 * @method static Builder|Trending whereDuration($value)
 * @method static Builder|Trending whereId($value)
 * @method static Builder|Trending whereStorageThumbnail($value)
 * @method static Builder|Trending whereStorageVideo($value)
 * @method static Builder|Trending whereTypeVideo($value)
 * @method static Builder|Trending whereUpdatedAt($value)
 * @method static Builder|Trending whereUploadedAt($value)
 * @method static Builder|Trending whereVideoId($value)
 * @mixin Eloquent
 */
class Trending extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'trendings';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /**
     * Download video
     *
     * @return string
     * @throws FilesystemException
     */
    function downloadVideo(): string
    {
        if (is_string($this->storage_video)) {
            if (!DiskPathInfo::parse($this->storage_video)) {
                if (Storage::disk(config('crawl.disk_save_video_trending'))->has($this->storage_video)) {
                    return "<a target='_blank' class='btn btn-sm btn-link' href='" . route('video.download', [
                            'disk' => config('crawl.disk_save_video_trending'),
                            'file_path' => $this->storage_video,
                        ]) . "'><i class='las la-file-download'></i>Download Video</a>";
                }
            } else {
                if (DiskPathInfo::parse($this->storage_video)->exists()) {
                    return "<a target='_blank' class='btn btn-sm btn-link' href='" . route('video.download', [
                            'disk' => DiskPathInfo::parse($this->storage_video)->bestDisk(),
                            'file_path' => DiskPathInfo::parse($this->storage_video)->path(),
                        ]) . "'><i class='las la-file-download'></i>Download Video</a>";
                }
            }
        }
        return "<a class='btn btn-sm text-danger' disabled><i class='las la-times-circle'></i>No Video</a>";
    }
}
