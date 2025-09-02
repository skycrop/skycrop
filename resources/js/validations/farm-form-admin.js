import JustValidate from 'just-validate';

window.farmAdminWizard = function(farmerIdFromBlade) {
    return {
        step: 1,
        progress: 0,
        farmName: '',
        farmerId: farmerIdFromBlade,
        farmLocation: '',
        activeField: 0,

        validator: null,

        initValidation() {
            try {
                this.validator = new JustValidate('#farmFormAdminValidate');

                // Step 1 fields
                this.validator
                .addField('[name="farm_name"]', [
                    { rule: 'required', errorMessage: 'Farm name is required' }
                ])
                .addField('[name="farm_location"]', [
                    { rule: 'required', errorMessage: 'Location is required' }
                ]);

                // Step 2+3 fields (initial ones)
                this.fields.forEach((field, index) => {
                    setTimeout(() => {
                        this.registerDynamicFieldRules(index);
                    }, 3000)
                });

                this.validator.onSuccess((event) => {
                    this.submitFarm();
                });

            } catch (err) {
                console.error('Unexpected error during validation init:', err);
            }
        },

        /**
         * Registers validation rules for a dynamic field set
         */
        registerDynamicFieldRules(index) {
            const safeAddField = (selector, rules) => {
                 const el = document.querySelector(selector);
                if (el) {
                    try {
                        this.validator.addField(selector, rules);
                    } catch (e) {
                        console.warn(`Could not add field for ${selector}`, e);
                    }
                } else {
                    console.log(`⏳ Skipped missing field: ${selector}`);
                }                
            };

            safeAddField(`[name="fields[${index}][landArea]"]`, [
                { rule: 'required', errorMessage: 'Land area is required' },
                { rule: 'number', errorMessage: 'Land area must be a number' },
                { rule: 'minNumber', value: 0.1, errorMessage: 'Land area must be greater than 0' }
            ]);

            safeAddField(`[name="fields[${index}][landUnit]"]`, [
                { rule: 'required', errorMessage: 'Land unit is required' }
            ]);

            safeAddField(`[name="fields[${index}][landType]"]`, [
                { rule: 'required', errorMessage: 'Land type is required' }
            ]);

            safeAddField(`[name="fields[${index}][soilType]"]`, [
                { rule: 'required', errorMessage: 'Soil type is required' }
            ]);

            safeAddField(`[name="fields[${index}][soilPH]"]`, [
                { rule: 'required', errorMessage: 'Soil pH level is required' },
                { rule: 'number', errorMessage: 'Soil pH must be a number' },
                { rule: 'minNumber', value: 0, errorMessage: 'Soil pH must be at least 0' },
                { rule: 'maxNumber', value: 14, errorMessage: 'Soil pH must be at most 14' }
            ]);

            safeAddField(`[name="fields[${index}][waterType]"]`, [
                { rule: 'required', errorMessage: 'Water type is required' }
            ]);

            safeAddField(`[name="fields[${index}][waterPH]"]`, [
                { rule: 'required', errorMessage: 'Water pH level is required' },
                { rule: 'number', errorMessage: 'Water pH must be a number' },
                { rule: 'minNumber', value: 0, errorMessage: 'Water pH must be at least 0' },
                { rule: 'maxNumber', value: 14, errorMessage: 'Water pH must be at most 14' }
            ]);

            safeAddField(`[name="fields[${index}][cropSeason]"]`, [
                { rule: 'required', errorMessage: 'Crop season is required' }
            ]);
        },

        fields: [
            {
                id: Date.now(),
                landArea: '',
                landUnit: 'acres',
                landType: 'nahri',
                soilType: '',
                soilPH: '',
                waterType: '',
                waterPH: '',
                cropSeason: 'kharif',
                cropName: '',
                sowingDate: '',
                harvestDate: ''
            }
        ],

        updateProgress() {
            if (this.step === 1) this.progress = 0;
            else if (this.step === 2) this.progress = 50;
            else if (this.step === 3) this.progress = 100;
        },

        goBack() {
            if (this.step === 2) this.step = 1;
            else if (this.step === 3) this.step = 2;
            this.updateProgress();
        },

        goBackToStep2() {
            this.step = 2;
            this.updateProgress();
        },

        addField() {
            const newIndex = this.fields.length;

            this.fields.push({
                id: Date.now() + Math.random(),
                landArea: '',
                landUnit: 'acres',
                landType: 'nahri',
                soilType: '',
                soilPH: '',
                waterType: '',
                waterPH: '',
                cropSeason: 'kharif',
                cropName: '',
                sowingDate: '',
                harvestDate: ''
            });

            this.activeField = newIndex;

            // Register validation rules for new field set
            this.$nextTick(() => {
                this.registerDynamicFieldRules(newIndex);
            });
        },

        removeField(index) {
            if (this.fields.length > 1) {
                this.fields.splice(index, 1);
                if (this.activeField >= this.fields.length) {
                    this.activeField = this.fields.length - 1;
                }
            }
        },

        async validateStep(stepNumber) {
            let fields = [];

            if (stepNumber === 1) {
                fields = ['[name="farm_name"]', '[name="farm_location"]'];
            } else if (stepNumber === 2) {
                document.querySelectorAll('[name^="fields"]').forEach(input => {
                    if (
                        input.name.endsWith('[landArea]') || 
                        input.name.endsWith('[landUnit]') || 
                        input.name.endsWith('[landType]') || 
                        input.name.endsWith('[soilType]') || 
                        input.name.endsWith('[soilPH]') || 
                        input.name.endsWith('[waterType]') || 
                        input.name.endsWith('[waterPH]')
                    ) {
                        fields.push(`[name="${input.name}"]`);
                    }
                });
            } else if (stepNumber === 3) {
                document.querySelectorAll('[name^="fields"]').forEach(input => {
                    if (
                        input.name.endsWith('[cropSeason]') || 
                        input.name.endsWith('[cropName]') || 
                        input.name.endsWith('[sowingDate]') || 
                        input.name.endsWith('[harvestDate]')
                    ) {
                        fields.push(`[name="${input.name}"]`);
                    }
                });
            }

            const results = await Promise.all(
                fields.map(f => this.validator.revalidateField(f))
            );

            if (results.every(Boolean)) {
                this.step++;
                this.updateProgress();
            }
        },

        submitFarm() {       
            
            const farmData = {
                name: this.farmName,
                farmer_id: this.farmerId,
                location: this.farmLocation,
                fields: this.fields,
            };

            console.log('Submitting farm data:', farmData);
            // notyf.success('Farm data ready to submit. See console for details.');

            fetch('/admin/farmer/add-farm', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(farmData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // If your backend returns JSON
            })
            .then(data => {
                // Success code here: maybe redirect or show a message                
                setTimeout(() => {
                    notyf.success('Farm added successfully!');
                    window.location.href = '/admin/farmer';
                }, 1000);
            })
            .catch(error => {
                // Handle any error
                console.error('Error:', error);
            })
        }
    }
}

export function adminFarmFormValidation() {}
