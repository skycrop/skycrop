import JustValidate from 'just-validate';

export function cropFormValidation() {
    const form = document.querySelector('#validateCropForm');
    if (!form) return;

    const validation = new JustValidate('#validateCropForm');

    validation
        .addField('[name="crop_name"]', [
            { rule: 'required', errorMessage: 'Crop Name is required' },
            { rule: 'minLength', value: 2, errorMessage: 'Minimum 2 characters' },
        ])
        .addField('[name^="suitable_season["]', [
            {
                validator: () => {                                                
                    return document.querySelectorAll('[name^="suitable_season["]').length > 1;
                },
                errorMessage: 'Please select a suitable season.',
            }
        ])
        .addField('[name^="category["]', [
            {
                validator: () => {                                                
                    return document.querySelectorAll('[name^="category["]').length > 1;
                },
                errorMessage: 'Please select a main category.',
            }
        ])
        .addField('[name^="suitable_land_types["]', [
            {
                validator: () => {                                                
                    return document.querySelectorAll('[name^="suitable_land_types["]').length > 1;
                },
                errorMessage: 'Please select suitable land type.',
            }
        ])
        .addField('[name^="suitable_soil_types["]', [
            {
                validator: () => {                                                
                    return document.querySelectorAll('[name^="suitable_soil_types["]').length > 1;
                },
                errorMessage: 'Please select suitable land type.',
            }
        ])
        .addField('[name^="suitable_water_types["]', [
            {
                validator: () => {                                                
                    return document.querySelectorAll('[name^="suitable_water_types["]').length > 1;
                },
                errorMessage: 'Please select suitable land type.',
            }
        ])
        .addField('[name="soil_ph_min"]', [
            { rule: 'required', errorMessage: 'Soil pH Min is required' },
            { rule: 'number', errorMessage: 'Enter a valid number' },
        ])

        .addField('[name="soil_ph_max"]', [
            { rule: 'required', errorMessage: 'Soil pH Max is required' },
            { rule: 'number', errorMessage: 'Enter a valid number' },
        ])

        .addField('[name="water_ph_min"]', [
            { rule: 'required', errorMessage: 'Water pH Min is required' },
            { rule: 'number', errorMessage: 'Enter a valid number' },
        ])

        .addField('[name="water_ph_max"]', [
            { rule: 'required', errorMessage: 'Water pH Max is required' },
            { rule: 'number', errorMessage: 'Enter a valid number' },
        ])
        .onSuccess((event) => {
            form.submit(); // ✅ Manually submit the form
        });
}
