<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Str;

/**
 * Class MusicsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MusicsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Musics::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/musics');
        CRUD::setEntityNameStrings('musics', 'musics');
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
            'name' => 'music_id',
            'type' => 'closure',
            'function' => function($entry){
                return "<a target='_blank' href='". make_url_music_tiktok(title: $entry->title, music_id: $entry->music_id) ."'>$entry->music_id <i class='las la-external-link-alt'></i></a>";
            },
            'escaped' => false
        ]);
        CRUD::addColumn([
            'name' => 'play_url',
            'type' => 'closure',
            'function' => function($entry){
                return "<a target='_blank' href='". $entry->play_url ."'>". Str::of($entry->play_url)->limit(32) ."<i class='las la-play-circle'></i></a>";
            },
            'escaped' => false
        ]);
        CRUD::column('title');
        CRUD::addColumn([
            'name' => 'duration',
            'type' => 'closure',
            'function' => function($entry){
                return $entry->duration . 's';
            }
        ]);
    }
}
