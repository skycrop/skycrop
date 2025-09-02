<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Enums\ActionType;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function __construct(        
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['chemical.view']);

        
        $filters = [
            'search' => request('search'),            
            'sort_field' => null,
            'sort_direction' => null,
        ];
        $query = Tag::applyFilters($filters);

        $tags = $query->paginateData([
            'per_page' => $filters['per_page'] ?? config('settings.default_pagination') ?? 10,
        ]);
        
        return view('backend.pages.tag.index', [
            'tags' => $tags,            
            'breadcrumbs' => [
                'title' => __('Chemicals'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['chemical.create']);       

        ld_do_action('tag_create_page_before');

        return view('backend.pages.tag.create', [                        
            'breadcrumbs' => [
                'title' => __('New Chemical'),
                'items' => [
                    [
                        'label' => __('Chemicals'),
                        'url' => route('admin.tag.index'),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);
        
        $tag = new Tag();
        $tag->name = $request->name;
        
        $tag = ld_apply_filters('tag_store_before_save', $tag, $request);
        $tag->save();
        /** @var Tag $tag */
        $tag = ld_apply_filters('tag_store_after_save', $tag, $request);

        $this->storeActionLog(ActionType::CREATED, ['tag' => $tag]);

        session()->flash('success', __('Chemical has been created.'));

        ld_do_action('tag_store_after', $tag);

        return redirect()->route('admin.tag.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['chemical.edit']);

        $tag = Tag::findOrFail($id);

        ld_do_action('tag_edit_page_before');

        $tag = ld_apply_filters('tag_edit_page_before', $tag);

        return view('backend.pages.tag.edit', [            
            'tag' => $tag,
            'breadcrumbs' => [
                'title' => __('Edit Chemical'),
                'items' => [
                    [
                        'label' => __('Chemicals'),
                        'url' => route('admin.tag.index'),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $id,
        ]);
        
        $tag = Tag::findOrFail($id);

        $tag->name = $request->name;        
        
        $tag = ld_apply_filters('tag_update_before_save', $tag, $request);
        $tag->save();

        /** @var Tag $tag */
        $tag = ld_apply_filters('tag_update_after_save', $tag, $request);
        ld_do_action('tag_update_after', $tag);

        $this->storeActionLog(ActionType::UPDATED, ['tag' => $tag]);

        session()->flash('success', __('Chemical has been updated.'));
        
        return redirect()->route('admin.tag.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}