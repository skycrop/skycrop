import JustValidate from 'just-validate';

export function collectionFormValidation() {
    const form = document.querySelector('#validateCollectionForm');
    if (!form) return;

    const validation = new JustValidate('#validateCollectionForm');

    validation
        .addField('[name="name"]', [
            { rule: 'required', errorMessage: 'Collection Name is required' },
        ])

        .addField('[name="level"]', [
            { 
                validator: (value) => value.trim() !== '', 
                errorMessage: 'Please select a collection level',
            },
        ])
        .addField('[name="parent_id"]', [
            { 
                validator: (value, fields) => {
                    const level = fields['[name="level"]']?.elem?.value || '';
                    console.log('level', level);
                    

                    if (['2', '3'].includes(level)) {
                        return value.trim() !== '' && !isNaN(parseInt(value));
                    }

                    return true;
                },
                errorMessage: 'Please select a valid parent collection',
            },
        ])
        .addField('[name="type_id"]', [
            { 
                validator: (value, fields) => {
                    const level = fields['[name="level"]']?.elem?.value || '';
                    if (level === '2') {
                        return value.trim() !== '' && !isNaN(parseInt(value));
                    }                    
                    return true;
                },
                errorMessage: 'Please select a valid collection type',
            },
        ])

        .onSuccess((event) => {
            form.submit(); // ✅ Manually submit the form
        });
}
