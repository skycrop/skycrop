import JustValidate from 'just-validate';

export function categoryFormValidation() {
  const form = document.querySelector('#validateCategoryForm');
  if (!form) return;

  const validation = new JustValidate('#validateCategoryForm');

  validation
    .addField('#name', [
      {
        rule: 'required',
        errorMessage: 'Category name is required',
      },
      {
        rule: 'minLength',
        value: 2,
        errorMessage: 'At least 2 characters required',
      },
    ])
    .addField('[name="level"]', [
      {
        rule: 'required',
        errorMessage: 'Please select a category level',
      },
    ])
    .addField('[name="photo"]', [ imageValidator('[name="photo"]') ])
    .addField('[name="parent_id"]', [
      {
        validator: (value, fields) => {
          const levelField = fields['[name="level"]'];
          const levelValue = levelField?.elem?.value;                    

          if (levelValue == '1') {            
            return value.trim() !== '';
          } else {            
            return value.trim() === '';
          }
        },
        errorMessage: (value, fields) => {
          const levelValue = fields['[name="level"]']?.elem?.value.trim();
          if (levelValue == '1') {
            return 'Please select a parent category';
          } else {
            return 'Please unselect parent category';
          }
        },
      },
    ])
    .onSuccess(() => {
      form.submit(); // ✅ Important for actual submission
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