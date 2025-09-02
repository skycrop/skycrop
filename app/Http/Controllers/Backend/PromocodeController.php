<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ActionType;
use App\Http\Controllers\Controller;
use App\Models\Promocode;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;

class PromocodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(Auth::user(), ['promocode.view']);

        $filters = [
            'search' => request('search'),
            'role' => request('role'),
            'sort_field' => null,
            'sort_direction' => null,
        ];

        $query = Promocode::applyFilters($filters);
        $promocodes = $query->paginateData([
            'per_page' => $filters['per_page'] ?? config('settings.default_pagination') ?? 10,
        ]);

        return view('backend.pages.promocode.index', [
            'promocodes' => $promocodes,            
            'breadcrumbs' => [
                'title' => __('Promo Codes'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(Auth::user(), ['promocode.create']);       

        ld_do_action('brand_create_page_before');

        return view('backend.pages.promocode.create', [                        
            'breadcrumbs' => [
                'title' => __('New Promocode'),
                'items' => [
                    [
                        'label' => __('Promo Codes'),
                        'url' => route('admin.promocode.index'),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'code' => 'required|string|unique:promocodes,code|max:50',
            'per_person_usage' => 'required|integer|min:1',
            'discount_type' => 'required|in:fixed,percent',
            'is_active' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_cart_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        // Create new promocode
        $promocode = new Promocode();
        $promocode->code = $validatedData['code'];
        $promocode->per_person_usage = $validatedData['per_person_usage'];
        $promocode->discount_type = $validatedData['discount_type'];
        $promocode->is_active = $validatedData['is_active'];
        $promocode->start_date = $validatedData['start_date'];
        $promocode->end_date = $validatedData['end_date'];
        $promocode->max_discount_amount = $validatedData['max_discount_amount'] ?? null;
        $promocode->min_cart_amount = $validatedData['min_cart_amount'] ?? 0;
        $promocode->discount_amount = $validatedData['discount_amount'];
        $promocode->description = $validatedData['description'] ?? null;

        $promocode->save();

        $this->storeActionLog(ActionType::CREATED, ['promocode' => $promocode]);

        return redirect()->route('admin.promocode.index')->with('success', 'Promocode created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Promocode $promocode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['promocode.edit']);

        $promocode = Promocode::findOrFail($id);

        ld_do_action('promocode_edit_page_before');

        $promocode = ld_apply_filters('promocode_edit_page_before', $promocode);

        return view('backend.pages.promocode.edit', [            
            'promocode' => $promocode,
            'breadcrumbs' => [
                'title' => __('Edit Promocode'),
                'items' => [
                    [
                        'label' => __('Promo Codes'),
                        'url' => route('admin.promocode.index'),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {        
        $promocode = Promocode::findOrFail($id);

        $validated = $request->validate([
            'per_person_usage'   => ['required', 'integer', 'min:1'],
            'discount_type'      => ['required', 'in:fixed,percent'],
            'is_active'          => ['required', 'boolean'],
            'start_date'         => ['required', 'date', 'before_or_equal:end_date'],
            'end_date'           => ['required', 'date', 'after_or_equal:start_date'],
            'max_discount_amount'=> ['nullable', 'numeric', 'min:0'],
            'min_cart_amount'    => ['nullable', 'numeric', 'min:0'],
            'discount_amount'    => ['required', 'numeric', 'min:0'],
            'description'        => ['nullable', 'string', 'max:500'],
        ]);

        // 2. Update model
        $promocode->update($validated);

        $this->storeActionLog(ActionType::UPDATED, ['promocode' => $promocode]);

        
        return redirect()
            ->route('admin.promocode.index')
            ->with('success', 'Promocode updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promocode $promocode)
    {
        //
    }
}
