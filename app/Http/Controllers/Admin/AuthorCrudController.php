<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AuthorRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AuthorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AuthorCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Author::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/author');
        CRUD::setEntityNameStrings('author', 'authors');
    }

    protected function setupListOperation()
    {
        CRUD::addColumn('id');
        CRUD::column('author_id');
        CRUD::addColumn([
           'name' => 'unique_id',
           'type' => 'closure',
           'function' => function($entry){
                return "<a target='_blank' href='". make_url_user_tiktok($entry->unique_id) ."'>". $entry->unique_id ."<i class='las la-external-link-alt'></i></a>";
           },
           'escaped' => false
        ]);
        CRUD::column('nickname');
    }
}
