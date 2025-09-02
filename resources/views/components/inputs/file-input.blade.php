@props([
    'label' => 'File',
    'name' => 'file',
    'id' => null,
    'multiple' => false,
    'existingAttachment' => null,
    'existingAltText' => '',
    'existingAttachments' => [],
    'removeCheckboxName' => 'remove_featured_image',
    'removeCheckboxLabel' => null,
])

@php
    $id = $id ?? $name;
@endphp

<div {{ $attributes->merge(['class' => 'mb-4 space-y-1']) }}>
    <label for="{{ $id }}"
        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }} <span class="text-red-500">*</span></label>
    <input type="file" name="{{ $name }}" id="{{ $id }}" {{ $multiple ? 'multiple' : '' }}
        class="form-control-file">
    @if ($existingAttachment)
        <div class="mb-4">
            <img src="{{ $existingAttachment }}" alt="{{ $existingAltText }}" class="max-h-30 rounded-md">

            @if($removeCheckboxLabel)
                <div class="mt-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="{{ $removeCheckboxName }}" id="{{ $removeCheckboxName }}"
                            class="form-checkbox mr-2">
                        <span
                            class="text-sm text-gray-700 dark:text-gray-300">{{ $removeCheckboxLabel }}</span>
                    </label>
                </div>
            @endif
        </div>
    @endif

    @if (!empty($existingAttachments))
        <div class="mb-4 flex flex-wrap gap-4">
            @foreach ($existingAttachments as $index => $image)
                <div class="relative">
                    <img src="{{ asset('storage/'.$image) }}" alt="{{ $existingAltText }}" class="max-h-25 rounded-md border">

                    @if ($removeCheckboxLabel)
                        <div class="mt-2">
                            <label class="flex items-center">
                                <input type="checkbox"
                                    name="{{ $removeCheckboxName }}[]"
                                    value="{{ $index }}"
                                    class="form-checkbox mr-2">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $removeCheckboxLabel }}</span>
                            </label>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif    
    {{ $slot }}
</div>
