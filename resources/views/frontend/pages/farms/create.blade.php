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
            <h1 class="text-2xl font-bold text-green-700 mb-6">My Farms</h1>
            <x-messages />

            <div x-data="farmWizard()" x-init="initValidation()">
            <div class="max-w-5xl mx-auto">

                <!-- Progress Bar -->
                <div class="mb-8">
                <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                    <span>Progress</span>
                    <span x-text="progress + '%'"></span>
                </div>
                <div class="bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div class="bg-green-600 h-2 transition-all" :style="`width: ${progress}%`"></div>
                </div>
                <div class="flex justify-between text-xs text-green-700 mt-2">
                    <span>Farm Details</span>
                    <span>Fields Info</span>
                    <span>Crop Info</span>
                </div>
                </div>

                <form id="farmFormValidate" @submit.prevent>
                @csrf
                <!-- Step 1: Farm Details -->
                <div x-show="step === 1" x-transition class="space-y-8">
                    <h2 class="text-2xl font-bold text-green-700">Add New Farm</h2>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                        <label class="block text-gray-700 font-semibold mb-2">Farm Name <span class="text-red-500">*</span></label>
                        <input type="text" x-model="farmName" name="farm_name" placeholder="e.g. Green Valley Farm"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-green-500 focus:ring-green-500 focus:outline-none" />
                        </div>
                        <div>
                        <label class="block text-gray-700 font-semibold mb-2">Farm Location <span class="text-red-500">*</span></label>
                        <input type="text" x-model="farmLocation" name="farm_location" placeholder="e.g. Punjab, India"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-green-500 focus:ring-green-500 focus:outline-none" />
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="validateStep(1)" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">Next Step &#8594;</button>
                    </div>
                </div>

                <!-- Step 2: Fields Information -->
                <div x-show="step === 2" x-transition class="space-y-8">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-green-700">Farm Fields</h2>
                        <button type="button" @click="addField()" 
                                class="bg-green-100 text-green-700 px-4 py-2 rounded-lg font-semibold hover:bg-green-200 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19" stroke-linecap="round"/>
                            <line x1="5" y1="12" x2="19" y2="12" stroke-linecap="round"/>
                        </svg>
                        Add Field
                        </button>
                    </div>

                    <!-- Field Tabs -->
                    <div class="flex gap-3 overflow-x-auto pb-4">
                        <template x-for="(field, idx) in fields" :key="field.id">
                        <button type="button" @click="activeField = idx"
                            :class="activeField === idx ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700'"
                            class="relative rounded-full px-6 py-2 font-semibold whitespace-nowrap flex items-center">
                            Field <span class="ml-1" x-text="idx + 1"></span>

                            <button type="button" @click.stop="removeField(idx)" 
                                    class="ml-3 text-red-400 hover:text-red-600 absolute top-1 right-1 rounded-full text-sm"
                                    title="Remove field">&times;</button>
                        </button>
                        </template>
                    </div>

                    <!-- Active Field Form -->
                    <div class="border border-green-300 p-6 space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                        <!-- Total Land Area + Unit -->
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Total Land Area</label>                                
                                <input type="number" step="0.1" min="0"  :name="`fields[${activeField}][landArea]`"  x-model.number="fields[activeField].landArea" placeholder="e.g. 5.5" 
                                    class="rounded border border-gray-300 px-3 py-2 w-full focus:ring-green-500 focus:border-green-500"/>                                
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Land Unit</label>
                                <select :name="`fields[${activeField}][landUnit]`" x-model="fields[activeField].landUnit" class="rounded border border-gray-300 px-3 py-2 focus:ring-green-500 focus:border-green-500 form-control ">
                                    <option value="acres">Acres</option>
                                    <option value="hectares">Hectares</option>
                                    <option value="kila">Kila</option>
                                    <option value="biga">Biga</option>
                                </select>
                            </div>                            
                        </div>

                        <!-- Land Type -->
                        <div class="grid md:grid-cols-1 gap-6">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-2">Land Type</label>
                                <input type="hidden" :name="`fields[${activeField}][landType]`" x-model="fields[activeField].landType">

                                <div class="flex gap-3">
                                    <!-- Nahri (Irrigated) -->
                                    <button type="button"
                                        @click="fields[activeField].landType = 'nahri'"
                                        :class="fields[activeField].landType === 'nahri' 
                                            ? 'bg-green-600 text-white border border-green-600' 
                                            : 'bg-white border border-green-600 text-green-700'"
                                        class="flex-1 rounded-lg px-4 py-2 font-semibold transition text-left"
                                    >
                                        Nahri (Irrigated)
                                        <p class="text-xs mt-1" 
                                        :class="fields[activeField].landType === 'nahri' ? 'text-green-100' : 'text-gray-400'">
                                        Canal / Tube well irrigated
                                        </p>
                                    </button>

                                    <!-- Birani (Rain-fed) -->
                                    <button type="button"
                                        @click="fields[activeField].landType = 'birani'"
                                        :class="fields[activeField].landType === 'birani' 
                                            ? 'bg-green-600 text-white border border-green-600' 
                                            : 'bg-white border border-green-600 text-green-700'"
                                        class="flex-1 rounded-lg px-4 py-2 font-semibold transition text-left"
                                    >
                                        Birani (Rain-fed)
                                        <p class="text-xs mt-1"
                                        :class="fields[activeField].landType === 'birani' ? 'text-green-100' : 'text-gray-400'">
                                        Rain-fed agriculture
                                        </p>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Soil Section -->
                        <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Soil Type</label>
                            <select :name="`fields[${activeField}][soilType]`" x-model="fields[activeField].soilType" 
                            class="rounded border border-gray-300 w-full py-2 px-3 focus:ring-green-500 focus:border-green-500">
                            <option value="">Select Soil Type</option>
                            <option value="alluvial">Alluvial Soil</option>
                            <option value="black">Black Soil</option>
                            <option value="red">Red Soil</option>
                            <option value="sandy">Sandy Soil</option>
                            <option value="laterite">Laterite Soil</option>
                            <option value="peaty">Peaty Soil</option>
                            <option value="saline">Saline Soil</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Soil pH Level</label>
                            <input type="number" step="0.1" min="0" max="14" :name="`fields[${activeField}][soilPH]`" x-model.number="fields[activeField].soilPH"
                            placeholder="e.g. 6.5"
                            :class="(fields[activeField].soilPH < 6 || fields[activeField].soilPH > 7.5) && fields[activeField].soilPH !== '' ? 'border-red-500' : 'border-gray-300'"
                            class="rounded border py-2 px-3 w-full focus:ring-green-500 focus:border-green-500"/>
                            <p class="text-xs text-gray-400 mt-1">Normal range: 6.0 - 7.5</p>
                        </div>
                        </div>

                        <!-- Water Section -->
                        <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Water Type</label>
                            <select :name="`fields[${activeField}][waterType]`" x-model="fields[activeField].waterType" 
                            class="rounded border border-gray-300 w-full py-2 px-3 focus:ring-green-500 focus:border-green-500">
                            <option value="">Select Water Type</option>
                            <option value="freshwater">Freshwater</option>
                            <option value="slightly-saline">Slightly Saline</option>
                            <option value="moderately-saline">Moderately Saline</option>
                            <option value="highly-saline">Highly Saline</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Water pH Level</label>
                            <input type="number" step="0.1" min="0" max="14" :name="`fields[${activeField}][waterPH]`" x-model.number="fields[activeField].waterPH" 
                            placeholder="e.g. 7.0"
                            :class="(fields[activeField].waterPH < 6.5 || fields[activeField].waterPH > 8.5) && fields[activeField].waterPH !== '' ? 'border-red-500' : 'border-gray-300'"
                            class="rounded border py-2 px-3 w-full focus:ring-green-500 focus:border-green-500"/>
                            <p class="text-xs text-gray-400 mt-1">Ideal range: 6.5 - 8.5</p>
                        </div>
                        </div>

                        <div class="flex justify-between mt-6">
                        <button type="button" @click="goBack()" class="border border-green-600 text-green-600 px-6 py-2 rounded hover:bg-green-100 transition">&#8592; Back</button>
                        <button type="button" @click="validateStep(2)" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">Next Step &#8594;</button>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Crop Information -->
                <div x-show="step === 3" x-transition class="space-y-8">
                    <h2 class="text-2xl font-bold text-green-700 mb-6 flex items-center gap-2">                    
                        Crop Information
                    </h2>

                    <!-- Field Tabs -->
                    <div class="flex gap-3 overflow-x-auto pb-4 mb-6">
                        <template x-for="(field, idx) in fields" :key="field.id">
                        <button type="button" @click="activeField = idx"
                            :class="activeField === idx ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700'"
                            class="rounded-full px-6 py-2 font-semibold whitespace-nowrap flex items-center">
                            Field <span class="ml-1" x-text="idx + 1"></span>
                        </button>
                        </template>
                    </div>

                    <div class="border border-green-300 p-6 space-y-6">
                        <!-- Crop Season -->
                        <div>
                        <label class="block font-semibold text-gray-700 mb-3">Crop Season</label>
                        <input type="hidden" :name="`fields[${activeField}][cropSeason]`" x-model="fields[activeField].cropSeason">
                        <div class="flex gap-6">
                            <button type="button"
                            :class="fields[activeField].cropSeason === 'kharif' ? 'bg-green-600 text-white' : 'bg-green-50 text-green-700 border border-green-600'"
                            @click="fields[activeField].cropSeason = 'kharif'"
                            class="flex-1 rounded-lg px-4 py-3 font-semibold transition">
                            Kharif Crops (Monsoon)
                            <span class="block text-xs text-gray-300 mt-1">June - October</span>
                            </button>
                            <button type="button"
                            :class="fields[activeField].cropSeason === 'rabi' ? 'bg-green-600 text-white' : 'bg-green-50 text-green-700 border border-green-600'"
                            @click="fields[activeField].cropSeason = 'rabi'"
                            class="flex-1 rounded-lg px-4 py-3 font-semibold transition">
                            Rabi Crops (Winter)
                            <span class="block text-xs text-gray-300 mt-1">November - April</span>
                            </button>
                        </div>
                        </div>

                        <!-- Crop Name (Dropdown) -->
                        <div>
                        <label class="block font-semibold text-gray-700 mb-1">Crop Name (Optional)</label>
                        <select :name="`fields[${activeField}][cropName]`" x-model="fields[activeField].cropName"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Select crop or leave blank for recommendations</option>
                            <option value="cotton">Cotton</option>
                            <option value="sugarcane">Sugarcane</option>
                            <option value="wheat">Wheat</option>                        
                        </select>
                        </div>

                        <!-- Sowing & Harvest Dates; shown only if crop is selected -->
                        <div x-show="fields[activeField].cropName !== ''" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Date of Sowing</label>
                            <input :name="`fields[${activeField}][sowingDate]`" type="text" x-model="fields[activeField].sowingDate" 
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 custom-datepicker" />
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Estimated Harvest Date</label>
                            <input :name="`fields[${activeField}][harvestDate]`" type="text" x-model="fields[activeField].harvestDate" 
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 custom-datepicker" />
                        </div>
                        </div>


                        <div class="flex justify-between mt-6">
                        <button type="button" @click="goBackToStep2()" 
                                class="border border-green-600 text-green-600 px-6 py-2 rounded hover:bg-green-100 transition">&#8592; Previous</button>
                        <button type="submit" 
                                class="bg-black text-white px-6 py-2 rounded hover:bg-gray-900 transition">Submit</button>
                        </div>
                    
                    </div>
                </div>
                </form>

            </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>

</script>


@endpush