<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Enums\ActionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService,        
    ) {
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['category.view']);

        $filters = [
            'search' => request('search'),                        
            'sort_field' => null,
            'sort_direction' => null,
        ];

        

        return view('backend.pages.category.index', [
            'category' => $this->categoryService->getCategories($filters),            
            'breadcrumbs' => [
                'title' => __('Category'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['category.create']);
        $filters = [
            'category_level' => 0
        ];

        ld_do_action('category_create_page_before');

        return view('backend.pages.category.create', [
            'level' => ['Main Category', 'Sub Category'],
            'categories' =>  $this->categoryService->getParentCategories(),
            'categoriesType' =>  $this->categoryService->getCategoryType(),
            'breadcrumbs' => [
                'title' => __('New Category'),
                'items' => [
                    [
                        'label' => __('Catrgory'),
                        'url' => route('admin.category.index'),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $imagePath = null;
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('category', 'public');
        }
        
        $category = new Category();
        $category->name = $request->name;
        $category->level = $request->level;
        $category->parent_id = $request->parent_id;
        $category->photo = $imagePath;


        $category = ld_apply_filters('category_store_before_save', $category, $request);
        $category->save();
        /** @var Category $category */
        $category = ld_apply_filters('category_store_after_save', $category, $request);

        $this->storeActionLog(ActionType::CREATED, ['category' => $category]);

        session()->flash('success', __('Category has been created.'));

        ld_do_action('category_store_after', $category);

        return redirect()->route('admin.category.index');
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
        $this->checkAuthorization(Auth::user(), ['category.edit']);

        $category = Category::findOrFail($id);

        ld_do_action('category_edit_page_before');

        $category = ld_apply_filters('category_edit_page_before', $category);

        return view('backend.pages.category.edit', [
            'level' => ['Main Category', 'Sub Category'],
            'categories' =>  $this->categoryService->getParentCategories(),
            'categoriesType' =>  $this->categoryService->getCategoryType(),
            'category' => $category,
            'breadcrumbs' => [
                'title' => __('Edit Category'),
                'items' => [
                    [
                        'label' => __('Category'),
                        'url' => route('admin.category.index'),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, int $id): RedirectResponse
    {
        $category = Category::findOrFail($id);

        $category->name = $request->name;
        $category->parent_id = $request->parent_id;        
        $category->level = $request->level;

        if ($request->hasFile('photo')) {
            $category->photo = $request->file('photo')->store('category', 'public');
        }
        
        $category = ld_apply_filters('category_update_before_save', $category, $request);
        $category->save();

        /** @var Category $category */
        $category = ld_apply_filters('category_update_after_save', $category, $request);
        ld_do_action('category_update_after', $category);

        $this->storeActionLog(ActionType::UPDATED, ['category' => $category]);

        session()->flash('success', __('Category has been updated.'));
        
        return redirect()->route('admin.category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getSubcategories(string $id)
    {
        return response()->json($this->categoryService->getSubCategories($id));
    }

    
}
