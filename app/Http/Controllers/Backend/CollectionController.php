<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Enums\ActionType;
use App\Http\Controllers\Controller;

use App\Models\Collection;
use App\Services\CollectionService;
use Illuminate\Contracts\Support\Renderable;
use App\Http\Requests\Collection\StoreCollectionRequest;
use App\Http\Requests\Collection\UpdateCollectionRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;


class CollectionController extends Controller
{

    public function __construct(
        private readonly CollectionService $collectionService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(Auth::user(), ['collection.view']);

        $filters = [
            'search' => request('search'),                        
            'sort_field' => null,
            'sort_direction' => null,
        ];

        

        return view('backend.pages.collection.index', [
            'collection' => $this->collectionService->getCollections($filters),
            'breadcrumbs' => [
                'title' => __('Collections'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(Auth::user(), ['collection.create']);        

        ld_do_action('collection_create_page_before');

        return view('backend.pages.collection.create', [
            'level' => ['Main Collection', 'Sub Collection', 'Collection Type'],
            'collections' =>  $this->collectionService->getParentCollections(),
            'collectionsType' =>  $this->collectionService->getCollectionType(),
            'breadcrumbs' => [
                'title' => __('New Collection'),
                'items' => [
                    [
                        'label' => __('Collections'),
                        'url' => route('admin.collection.index'),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCollectionRequest $request): RedirectResponse
    {
        $collection = new Collection();
        $collection->name = $request->name;
        $collection->level = $request->level;
        $collection->parent_id = $request->parent_id;     
        $collection->type_id = $request->type_id;           

        $collection = ld_apply_filters('collection_store_before_save', $collection, $request);
        $collection->save();
        /** @var Collection $collection */
        $collection = ld_apply_filters('collection_store_after_save', $collection, $request);

        $this->storeActionLog(ActionType::CREATED, ['collection' => $collection]);

        session()->flash('success', __('collection has been created.'));

        ld_do_action('collection_store_after', $collection);

        return redirect()->route('admin.collection.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['collection.edit']);

        $collection = Collection::findOrFail($id);

        ld_do_action('collection_edit_page_before');

        $collection = ld_apply_filters('collection_edit_page_before', $collection);

        return view('backend.pages.collection.edit', [
            'level' => ['Main Collection', 'Sub Collection', 'Collection Type'],
            'collections' =>  $this->collectionService->getParentCollections(),
            'collectionsType' =>  $this->collectionService->getCollectionType(),
            'collection' => $collection,
            'breadcrumbs' => [
                'title' => __('Edit Collection'),
                'items' => [
                    [
                        'label' => __('Collections'),
                        'url' => route('admin.collection.index'),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCollectionRequest $request, int $id): RedirectResponse
    {
        $collection = Collection::findOrFail($id);

        $collection->name = $request->name;
        $collection->parent_id = $request->parent_id;        
        $collection->level = $request->level;
        
        $collection = ld_apply_filters('collection_update_before_save', $collection, $request);
        $collection->save();

        /** @var Collection $collection */
        $collection = ld_apply_filters('collection_update_after_save', $collection, $request);
        ld_do_action('collection_update_after', $collection);

        $this->storeActionLog(ActionType::UPDATED, ['collection' => $collection]);

        session()->flash('success', __('Collection has been updated.'));
        
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        //
    }
}
