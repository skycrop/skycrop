import JustValidate from 'just-validate';

export function productFormValidation() {
    const form = document.querySelector('#validateProductForm');
    if (!form) return;

    const validation = new JustValidate('#validateProductForm');

    validation
        .addField('[name="name"]', [
            {
                rule: 'required',
                errorMessage: 'Product name is required',
            },
        ])
        .addField('[name="sku"]', [
            {
                rule: 'required',
                errorMessage: 'Product SKU is required',
            },
        ]).
        addField('[name="brand_id"]', [
            {
                validator: (value) => value.trim() !== '',
                errorMessage: 'Please select a brand',
            },
        ])
        .addField('[name^="collection_ids["]', [
            {
                validator: () => {                                                
                    return document.querySelectorAll('[name^="collection_ids["]').length > 1;
                },
                errorMessage: 'Please select at least one collection.',
            }
        ])  
        .addField('[name^="tag_ids["]', [
            {
                validator: () => {                                                
                    return document.querySelectorAll('[name^="tag_ids["]').length > 1;
                },
                errorMessage: 'Please select chemical',
            },
        ])
        .addField('[name="is_active"]', [
            {
                validator: (value) => value.trim() !== '',
                errorMessage: 'Please select a status',
            },
        ])
        .addField('#content', [
            {
                validator: () => {
                    // Get plain text (no HTML tags)
                    const text = quill.getText().trim();
                    return text.length > 0;
                },
                errorMessage: 'Description is required',
            },
        ])
        .addField('[name="category_id"]', [
            {
            rule: 'required',
            errorMessage: 'Category is required',
            },
        ])
        .addField('[name="subcategory_id"]', [
            {
            rule: 'required',
            errorMessage: 'Sub Category is required',
            },
        ])
        .addField('[name="cover_image"]', [ imageValidator('[name="cover_image"]') ])
        .addField('[name="images[]"]', [ imageValidator('[name="images[]"]') ])
        .addField('[name="seed_type"]', [
            {
            rule: 'required',
            errorMessage: 'Seed Type is required',
            },
        ])
        .addField('[name="crop_id"]', [
            {
            rule: 'required',
            errorMessage: 'Crop Name is required',
            },
        ])
        .addField('[name="video_urls[benefit]"]', [
            {
                validator: (value) => {
                    if (!value.trim()) return true; // allow empty
                    const urlPattern = /^(https?:\/\/)?([\w\-]+(\.[\w\-]+)+)([\w.,@?^=%&:/~+#-]*[\w@?^=%&/~+#-])?$/;
                    return urlPattern.test(value);
                },
                errorMessage: 'Please enter a valid Benefit Video URL',
            },
        ])
        .addField('[name="video_urls[testimonial]"]', [
            {
                validator: (value) => {
                    if (!value.trim()) return true; // allow empty
                    const urlPattern = /^(https?:\/\/)?([\w\-]+(\.[\w\-]+)+)([\w.,@?^=%&:/~+#-]*[\w@?^=%&/~+#-])?$/;
                    return urlPattern.test(value);
                },
                errorMessage: 'Please enter a valid Testimonial Video URL',
            },
        ])
        .addField('[name="video_urls[result]"]', [
            {
                validator: (value) => {
                    if (!value.trim()) return true; // allow empty
                    const urlPattern = /^(https?:\/\/)?([\w\-]+(\.[\w\-]+)+)([\w.,@?^=%&:/~+#-]*[\w@?^=%&/~+#-])?$/;
                    return urlPattern.test(value);
                },
                errorMessage: 'Please enter a valid Result Video URL',
            },
        ])
        .onSuccess((event) => {
            form.submit(); // ✅ Manually submit the form
        });
}

function imageValidator(fieldName) {
    return {
        validator: (value, fields) => {
            const isEditMode = document.querySelector('#is_edit_mode')?.value === '1';
            const fileInput = fields[fieldName]?.elem;
            if (!fileInput) return false;

            // In edit mode → allow empty if no file is selected
            if (isEditMode && fileInput.files.length === 0) {
                return true;
            }

            // In add mode or file uploaded in edit → validate type
            if (fileInput.files.length === 0) return false;
            const file = fileInput.files[0];
            const allowedTypes = ['image/jpeg', 'image/png'];

            return allowedTypes.includes(file.type);
        },
        errorMessage: 'Please upload a valid image file (JPG, PNG)',
    };
}
