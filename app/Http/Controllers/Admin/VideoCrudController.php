<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DownloadVideoRequest;
use App\Http\Requests\VideoRequest;
use App\Models\Author;
use App\Models\Musics;
use App\Models\Video;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Str;
use Storage;

/**
 * Class VideoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class VideoCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation;
    use DeleteOperation;
    use ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Video::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/video');
        CRUD::setEntityNameStrings('video', 'videos');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addFilter([
            'name'  => 'video_id',
            'type'  => 'select2_multiple',
            'label' => 'Video by Video ID'
        ], function () {
            return $this->crud->model->pluck('video_id', 'id')->toArray();
        }, function ($values) {
            $this->crud->addClause('whereIn', 'id', json_decode($values));
        });

        CRUD::addColumn('id');
        CRUD::addColumn([
            'name'     => 'video_id',
            'type'     => 'closure',
            'function' => function ($entry) {
                $unique_user = Author::find($entry->author_id)->unique_id;
                return "<a target='_blank' href='" . make_url_tiktok_by_unique_user_and_video_id(unique_user: $unique_user, video_id: $entry->video_id) . "'>$entry->video_id <i class='las la-external-link-alt'></i></a>";
            },
            'escaped'  => false
        ]);
        CRUD::addColumn([
            'name'     => 'music_id',
            'type'     => 'closure',
            'function' => function ($entry) {
                $music_name = Musics::find($entry->music_id)->title;
                return "<a target='_blank' href='" . backpack_url('author/' . $entry->author_id . '/show') . "'>" . Str::of($music_name)->limit(32) . "</a>";
            },
            'escaped'  => false
        ]);
        CRUD::addColumn([
            'name'     => 'author_id',
            'type'     => 'closure',
            'function' => function ($entry) {
                $nickname = Author::find($entry->author_id)->nickname;
                return "<a target='_blank' href='" . backpack_url('author/' . $entry->author_id . '/show') . "'>" . Str::of($nickname)->limit(32) . "</a>";
            },
            'escaped'  => false
        ]);
        CRUD::column('title');
        CRUD::addColumn([
            'name'     => 'duration',
            'type'     => 'closure',
            'function' => function ($entry) {
                return $entry->duration . 's';
            }
        ]);
        CRUD::column('create_time');

        $this->crud->addButtonFromModelFunction('line', 'download_video', 'downloadVideo', 'beginning');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(VideoRequest::class);

        CRUD::field('video_id');
        CRUD::field('music_id');
        CRUD::field('author_id');
        CRUD::field('region');
        CRUD::field('title');
        CRUD::field('hash_title');
        CRUD::field('storage_file');
        CRUD::field('duration');
        CRUD::field('play_count');
        CRUD::field('digg_count');
        CRUD::field('comment_count');
        CRUD::field('share_count');
        CRUD::field('download_count');
        CRUD::field('create_time');
        CRUD::field('is_trending');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function downloadVideo(DownloadVideoRequest $request)
    {
        if (Storage::disk($request->input('disk'))->has($request->input('file_path'))) {
            return Storage::disk($request->input('disk'))->download($request->input('file_path'));
        }
        return false;
    }
}
