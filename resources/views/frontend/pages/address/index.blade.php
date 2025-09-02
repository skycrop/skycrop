@extends('frontend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('main-content')
<div class="min-h-screen bg-green-50 py-10 px-4">
  <div class="max-w-7xl min-h-150 mx-auto bg-white shadow-xl rounded-2xl overflow-hidden flex">
    
    <!-- Sidebar -->
    @include('frontend.layouts.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 p-8">
    <h1 class="text-2xl font-bold text-green-700 mb-6">Shipping Addresses</h1>
    <x-messages />

    <div class="mb-6 flex justify-end">
        <a href="{{ route('user.addresses.create') }}"
        class="bg-green-500 text-white px-5 py-2 rounded-lg shadow hover:bg-green-600 transition font-medium flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <line x1="12" y1="5" x2="12" y2="19" stroke-width="2" stroke-linecap="round" />
                <line x1="5" y1="12" x2="19" y2="12" stroke-width="2" stroke-linecap="round" />
            </svg>
            Add New Address
        </a>
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
                            {{ __('Full Address') }}                            
                        </div>
                    </th>                                              
                    <th width="12%" class="p-2 bg-gray-50 dark:bg-gray-800 dark:text-white">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($addresses as $address)
                    <tr class="{{ $loop->index + 1 != count($addresses) ?  'border-b border-gray-100 dark:border-gray-800' : '' }}">
                        <td class="px-5 py-4 sm:px-6">
                            {{ $loop->index + 1 }}
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            {{ $address->full_name }}
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <p class="text-gray-700">{{ $address->address_line_1 }}, {{ $address->address_line_2 }}</p>
                            <p class="text-gray-500 text-sm">
                            {{ $address->city }}, {{ $address->state }} - {{ $address->zipcode }}
                            </p>
                            <p class="text-gray-500 text-sm">Phone: {{ $address->phone }}</p>
                        </td>                                
                        <td class="px-5 py-4 sm:px-6 flex justify-center">
                            <x-buttons.action-buttons :label="__('Actions')" :show-label="false" align="right">                                
                                <x-buttons.action-item
                                    :href="route('user.addresses.edit', $address->id)"
                                    icon="pencil"
                                    :label="__('Edit')"
                                />

                                <div x-data="{ deleteModalOpen: false }">
                                    <x-buttons.action-item
                                        type="modal-trigger"
                                        modal-target="deleteModalOpen"
                                        icon="trash"
                                        :label="__('Delete')"
                                        class="text-red-600 dark:text-red-400"
                                    />

                                    <x-modals.confirm-delete
                                        id="delete-modal-{{ $address->id }}"
                                        title="{{ __('Delete') }} {{ strtolower($address->full_name) }}"
                                        content="{{ __('Are you sure you want to delete this') }} {{ strtolower($address->full_name) }}?"
                                        formId="delete-form-{{ $address->id }}"
                                        formAction="{{ route('user.addresses.destroy', $address->id) }}"
                                        modalTrigger="deleteModalOpen"
                                        cancelButtonText="{{ __('No, cancel') }}"
                                        confirmButtonText="{{ __('Yes, Confirm') }}"
                                    />
                                </div>
                            </x-buttons.action-buttons>
                        </td>
                    
                    </tr>
                @empty
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td colspan="5" class="px-5 py-4 sm:px-6 text-center">
                            <span class="text-gray-500 dark:text-gray-300">{{ __('No address found') }}</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="my-4 px-4 sm:px-6">
            {{ $addresses->links() }}
        </div>
    </div>

    @if (count($addresses) == 0)
        <div class="text-center text-gray-500 mt-16">
        <img src="https://cdn-icons-png.flaticon.com/512/2991/2991108.png" alt="No Address" class="mx-auto w-28 h-28 mb-4 opacity-60">
        <p>No saved shipping addresses. Click "Add New Address" to save one!</p>
        </div>
    @endif
    </div>

  </div>
</div>



@endsection

@push('scripts')

@endpush