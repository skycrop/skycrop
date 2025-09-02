@props([
    'postMeta' => []
])

<div class="rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
     x-data="{ open: true }">
    <button type="button"
            @click="open = !open"
            class="flex w-full items-center justify-between p-5 text-left">
        <h3 class="text-lg font-medium text-gray-700 dark:text-white">{{ __('Variants') }} <span class="text-red-500">*</span></h3>
        <svg class="h-5 w-5 transform transition-transform duration-200 dark:text-gray-300"
             :class="{ 'rotate-180': open }"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-y-95"
         x-transition:enter-end="opacity-100 transform scale-y-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-y-100"
         x-transition:leave-end="opacity-0 transform scale-y-95"
         class="border-t border-gray-100 dark:border-gray-800">
        <div class="p-5">
            @php
                $metaJson = json_encode($postMeta, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);                                
                
            @endphp

            <div x-data="variantFields({{ $metaJson }})"
                 x-init="init()"
                 class="space-y-6">
                <!-- Fields container -->
                <div x-show="initialized && fields.length > 0" class="space-y-3">
                    <template x-for="(field, index) in fields" :key="`field-${index}`">
                        <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-md">
                            <div class="flex-1 space-y-3">
                                <!-- Row 1: Meta Key and Type -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <!-- Meta Key -->
                                    <div>
                                        <select x-model="field.variant_name"
                                               :name="`variant_name[${index}]`"
                                               class="w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">                                            
                                                <option value="Single Pack">Single Pack</option>
                                                <option value="Multi Pack">Multi Pack</option>                                            
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <input type="text"
                                               x-model="field.variant_pack_description"
                                               :name="`variant_pack_description[${index}]`"
                                               placeholder="{{ __('Variant Name') }}"
                                               required
                                               class="w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    </div>   

                                    <div>
                                        <input type="text"
                                               x-model="field.variant_sku"
                                               :name="`variant_sku[${index}]`"
                                               placeholder="{{ __('SKU') }}"
                                               required
                                               class="w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    </div>                                    
                                    
                                    <div>
                                        <input type="text"
                                               x-model="field.variant_item_price"
                                               :name="`variant_item_price[${index}]`"
                                               placeholder="{{ __('Item Price') }}"
                                               required
                                               class="w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    </div>                                    

                                    <div>
                                        <input type="text"
                                               x-model="field.variant_selling_price"
                                               :name="`variant_selling_price[${index}]`"
                                               placeholder="{{ __('Selling Price') }}"
                                               required
                                               class="w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    </div>                                    

                                    <div>
                                        <input type="text"
                                               x-model="field.variant_stock_quantity"
                                               :name="`variant_stock_quantity[${index}]`"
                                               placeholder="{{ __('Stock Quantity') }}"
                                               required
                                               class="w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    </div>       
                                    
                                    <div class="flex items-center gap-4">
                                        <input type="checkbox"
                                            :checked="field.variant_is_default"
                                            @change="
                                                    fields.forEach((f, i) => f.variant_is_default = (i === index))
                                            "
                                            :value="field.variant_is_default ? 1 : 0"
                                            :name="`variant_is_default[${index}]`"
                                            class="form-checkbox h-4 w-4 text-brand-500 border-gray-300 rounded focus:ring-brand-400 dark:border-gray-700 dark:bg-gray-900 dark:focus:ring-brand-500">
                                        <label class="text-sm text-gray-700 dark:text-gray-300">{{ __('Is Default') }}</label>


                                        <input type="checkbox" checked
                                                x-model="field.variant_is_available"
                                                :name="`variant_is_available[${index}]`"
                                                :value="field.variant_is_available ? 1 : 0"
                                                class="form-checkbox h-4 w-4 text-brand-500 border-gray-300 rounded focus:ring-brand-400 dark:border-gray-700 dark:bg-gray-900 dark:focus:ring-brand-500">
                                        <label class="text-sm text-gray-700 dark:text-gray-300">{{ __('Is Available') }}</label>
                                    </div>
                                    
                                </div>                                
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 pt-2">
                                <!-- Add Button -->
                                <button type="button"
                                        @click="addField()"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-brand-500 text-white hover:bg-brand-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>

                                <!-- Remove Button -->
                                <button type="button"
                                        @click="removeField(index)"
                                        x-show="fields.length > 1"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-500 text-white hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Add First Field Button (when no fields exist) -->
                <div x-show="initialized && fields.length === 0" class="text-center py-6">
                    <button type="button"
                            @click="addField()"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-brand-500 text-white rounded-md hover:bg-brand-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        {{ __('Add Variant Field') }}
                    </button>
                </div>

                <!-- Loading state -->
                <div x-show="!initialized" class="text-center py-6">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-500 mx-auto"></div>
                    <p class="mt-2 text-sm text-gray-500">{{ __('Loading...') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function variantFields(initialMeta = {}) {
    return {
        fields: [],
        initialized: false,

        init() {
            // Convert initial meta object to array format
            if (Array.isArray(initialMeta) && initialMeta.length > 0) {
                this.fields = initialMeta.map((item) => ({
                    variant_name: item.variant_name || '',
                    variant_pack_description: item.variant_pack_description || '',
                    variant_sku: item.variant_sku || '',
                    variant_item_price: item.variant_item_price || '',
                    variant_selling_price: item.variant_selling_price || '',
                    variant_stock_quantity: item.variant_stock_quantity || '',
                    variant_is_default: item.variant_is_default === 1 || item.variant_is_default === true,
                    variant_is_available: item.variant_is_available === 1 || item.variant_is_available === true
                }));
            } else {
                this.addField();
            }

            this.initialized = true;
        },

        addField() {
            this.fields.push({
                variant_name: '',
                variant_pack_description: '',
                variant_sku: '',
                variant_item_price: '',
                variant_selling_price: '',
                variant_stock_quantity: '',
                variant_is_default: false,
                variant_is_available: true
            });
        },

        removeField(index) {
            if (this.fields.length > 1) {
                this.fields.splice(index, 1);
            }
        }
    };
}
</script>
@endpush
