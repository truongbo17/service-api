<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TypeVideoEnum;
use App\Events\MakeVideoEvent;
use App\Exceptions\InValidDurationVideoException;
use App\Exceptions\InValidTypeVideoException;
use App\Exceptions\MakeVideoException;
use App\Models\Trending;
use App\Video\MakeVideo;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

/**
 * Class TrendingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TrendingCrudController extends CrudController
{
    use ListOperation;
    use ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Trending::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/trending');
        CRUD::setEntityNameStrings('trending', 'trendings');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn('id');
        CRUD::addColumn([
            'name'     => 'video_id',
            'type'     => 'closure',
            'function' => function ($entry) {
                return "<a target='_blank' href='" . backpack_url('video?video_id=' . $entry->video_id) . "'>" . Str::of($entry->video_id)->limit(32) . "<i class='las la-video'></i></a>";
            },
            'escaped'  => false
        ]);
        CRUD::addColumn([
            'name'     => 'duration',
            'type'     => 'closure',
            'function' => function ($entry) {
                return $entry->duration . 's';
            }
        ]);
        CRUD::addColumn([
            'name'    => 'type_video',
            'type'    => 'select_from_array',
            'options' => array_flip(TypeVideoEnum::asArray()),
        ]);
        CRUD::column('uploaded_at');

        $this->crud->addButtonFromModelFunction('line', 'download_video', 'downloadVideo', 'beginning');
        $this->crud->addButtonFromView('top', 'moderate', 'create_video_trending', 'beginning');
    }


    /**
     * Make video trending by event,listener
     *
     * @param Request $request
     * @return string
     */
    protected function makeVideoTrending(Request $request): string
    {
        try {
            MakeVideoEvent::dispatch(TypeVideoEnum::TRENDING, $request->input('duration', 0));

            return json_encode([
                'error'   => false,
                'message' => "Your video is being created, please wait for a few minutes.",
            ]);
        } catch (Exception $exception) {
            return json_encode([
                'error'   => true,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
