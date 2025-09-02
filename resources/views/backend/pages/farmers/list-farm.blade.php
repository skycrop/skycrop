@extends('backend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('admin-content')
<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6" x-data="{ selectedBrand: [], selectAll: false, bulkDeleteModalOpen: false }">
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

    {!! ld_apply_filters('farmer_after_breadcrumbs', '') !!}

    <div class="space-y-6">
        <div class="rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 sm:px-6 sm:py-5 flex flex-col md:flex-row justify-between items-center gap-3">               
               <div class="flex items-center gap-3">
                 <div class="flex items-center gap-2">
                    <!-- Bulk Actions dropdown -->
                    <div class="flex items-center justify-center" x-show="selectedBrand.length > 0">
                        <button id="bulkActionsButton" data-dropdown-toggle="bulkActionsDropdown" class="btn-secondary flex items-center justify-center gap-2 text-sm" type="button">
                            <iconify-icon icon="lucide:more-vertical"></iconify-icon>
                            <span>{{ __('Bulk Actions') }} (<span x-text="selectedBrand.length"></span>)</span>
                            <iconify-icon icon="lucide:chevron-down"></iconify-icon>
                        </button>

                        <!-- Bulk Actions dropdown menu -->
                        <div id="bulkActionsDropdown" class="z-10 hidden w-48 p-2 bg-white rounded-md shadow dark:bg-gray-700">
                            <ul class="space-y-2">
                                <li class="cursor-pointer flex items-center gap-1 text-sm text-red-600 dark:text-red-500 hover:bg-red-50 dark:hover:bg-red-500 dark:hover:text-red-50 px-2 py-1.5 rounded transition-colors duration-300"
                                    @click="bulkDeleteModalOpen = true">
                                    <iconify-icon icon="lucide:trash" ></iconify-icon> {{ __('Delete Selected') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                @if (auth()->user()->can('farmer.create'))
                <a href="{{ route('admin.farmer.createFarm', $farmer->id) }}" class="btn-primary flex items-center gap-2  ml-auto">
                    <iconify-icon icon="feather:plus" height="16" ></iconify-icon>
                    {{ __('Add Farm') }}
                </a>
                @endif
               </div>
            </div>
            <div class="space-y-3 border-t border-gray-100 dark:border-gray-800 overflow-x-auto overflow-y-visible">
                <table id="dataTable" class="w-full dark:text-gray-300">
                    <thead class="bg-light text-capitalize">
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <th width="5%" class="p-2 bg-gray-50 dark:bg-gray-800 dark:text-white text-left px-5 sm:px-6">
                                <div class="flex items-center">
                                    #
                                </div>
                            </th>
                            <th width="15%" class="p-2 bg-gray-50 dark:bg-gray-800 dark:text-white text-left px-5">
                                <div class="flex items-center">
                                    {{ __('Name') }}
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => request()->sort === 'name' ? '-name' : 'name']) }}" class="ml-1">
                                        @if(request()->sort === 'name')
                                            <iconify-icon icon="lucide:sort-asc" class="text-primary"></iconify-icon>
                                        @elseif(request()->sort === '-name')
                                            <iconify-icon icon="lucide:sort-desc" class="text-primary"></iconify-icon>
                                        @else
                                            <iconify-icon icon="lucide:arrow-up-down" class="text-gray-400"></iconify-icon>
                                        @endif
                                    </a>
                                </div>
                            </th> 
                            <th width="15%" class="p-2 bg-gray-50 dark:bg-gray-800 dark:text-white text-left px-5">
                                <div class="flex items-center">
                                    {{ __('Location') }}                            
                                </div>
                            </th>                                              
                            <th width="12%" class="p-2 bg-gray-50 dark:bg-gray-800 dark:text-white">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($farmerFarms as $farm)
                            <tr class="{{ $loop->index + 1 != count($farmerFarms) ?  'border-b border-gray-100 dark:border-gray-800' : '' }}">
                                <td class="px-5 py-4 sm:px-6">
                                    {{ $loop->index + 1 }}
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                        {{ $farm->name }}
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                        {{ $farm->location }}
                                </td>                                
                                <td class="px-5 py-4 sm:px-6 flex justify-center">
                                    <x-buttons.action-buttons :label="__('Actions')" :show-label="false" align="right">                                
                                            <x-buttons.action-item
                                                
                                                icon="pencil"
                                                :label="__('Edit')"
                                            />
                                    </x-buttons.action-buttons>
                                </td>
                            
                            </tr>
                        @empty
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <td colspan="5" class="px-5 py-4 sm:px-6 text-center">
                                    <span class="text-gray-500 dark:text-gray-300">{{ __('No farmers found') }}</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="my-4 px-4 sm:px-6">
                    {{ $farmerFarms->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Delete Confirmation Modal -->
    <div
        x-cloak
        x-show="bulkDeleteModalOpen"
        x-transition.opacity.duration.200ms
        x-trap.inert.noscroll="bulkDeleteModalOpen"
        x-on:keydown.esc.window="bulkDeleteModalOpen = false"
        x-on:click.self="bulkDeleteModalOpen = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 p-4 backdrop-blur-md"
        role="dialog"
        aria-modal="true"
        aria-labelledby="bulk-delete-modal-title"
    >
        <div
            x-show="bulkDeleteModalOpen"
            x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
            x-transition:enter-start="opacity-0 scale-50"
            x-transition:enter-end="opacity-100 scale-100"
            class="flex max-w-md flex-col gap-4 overflow-hidden rounded-md border border-outline border-gray-100 dark:border-gray-800 bg-white text-on-surface dark:border-outline-dark dark:bg-gray-700 dark:text-gray-300"
        >
            <div class="flex items-center justify-between border-b border-gray-100 px-4 py-2 dark:border-gray-800">
                <div class="flex items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 p-1">
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                <h3 id="bulk-delete-modal-title" class="font-semibold tracking-wide text-gray-700 dark:text-white">
                    {{ __('Delete Selected Brands') }}
                </h3>
                <button
                    x-on:click="bulkDeleteModalOpen = false"
                    aria-label="close modal"
                    class="text-gray-400 hover:bg-gray-200 hover:text-gray-700 rounded-md p-1 dark:hover:bg-gray-600 dark:hover:text-white"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="1.4" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="px-4 text-center">
                <p class="text-gray-500 dark:text-gray-300">
                    {{ __('Are you sure you want to delete the selected brands?') }}
                    {{ __('This action cannot be undone.') }}
                </p>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 p-4 dark:border-gray-800">
                <form id="bulk-delete-form" action="{{ route('admin.roles.bulk-delete') }}" method="POST">
                    @method('DELETE')
                    @csrf

                    <template x-for="id in selectedBrand" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>

                    <button
                        type="button"
                        x-on:click="bulkDeleteModalOpen = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
                    >
                        {{ __('No, Cancel') }}
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-300 dark:focus:ring-red-800"
                    >
                        {{ __('Yes, Delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
