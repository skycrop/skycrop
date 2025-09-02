import JustValidate from 'just-validate';

export function promoFormValidation() {
    const form = document.querySelector('#validatePromoForm');
    if (!form) return;

    const validation = new JustValidate('#validatePromoForm');
    const isEditMode = document.querySelector('#is_edit_mode')?.value === '1';

    const codeRules = [
        !isEditMode
            ? {
                rule: 'required',
                errorMessage: 'Promo code is required',
            }
            : null,
        {
            rule: 'customRegexp',
            value: /^[A-Za-z0-9-_]+$/,
            errorMessage: 'Invalid format (letters & numbers only)',
        }
    ].filter(Boolean);

    validation
    .addField('[name="code"]', codeRules)
    .addField('[name="per_person_usage"]', [
        {
            rule: 'required',
            errorMessage: 'Per person use is required',
        },
        {
            rule: 'number',
            errorMessage: 'Must be a number',
        },
        {
            rule: 'minNumber',
            value: 1,
            errorMessage: 'Must be at least 1',
        },
    ])
    .addField('[name="discount_type"]', [
        {
            rule: 'required',
            errorMessage: 'Select discount type',
        }
    ])
    .addField('[name="is_active"]', [
        {
            rule: 'required',
            errorMessage: 'Select status',
        }
    ])
    .addField('[name="start_date"]', [
        {
            rule: 'required',
            errorMessage: 'Start date is required',
        }
    ])
    .addField('[name="end_date"]', [
        {
            rule: 'required',
            errorMessage: 'End date is required',
        },
        {
            validator: (value, fields) => {
                const startDate = fields['[name="start_date"]']?.elem?.value;
                if (!startDate) return true; // Skip if no start date yet
                return new Date(value) > new Date(startDate);
            },
            errorMessage: 'End date must be after start date',
        }
    ])
    .addField('[name="max_discount_amount"]', [
        {
            rule: 'required',
            errorMessage: 'Maximum discount is required',
        },
        {
            rule: 'number',
            errorMessage: 'Must be a number',
        },
        {
            rule: 'minNumber',
            value: 0,
            errorMessage: 'Must be at least 0',
        },
    ])
    .addField('[name="min_cart_amount"]', [
        {
            rule: 'required',
            errorMessage: 'Minimum cart amount is required',
        },
        {
            rule: 'number',
            errorMessage: 'Must be a number',
        },
        {
            rule: 'minNumber',
            value: 0,
            errorMessage: 'Must be at least 0',
        },
    ])
    .addField('[name="discount_amount"]', [
        {
            rule: 'required',
            errorMessage: 'Discount value is required',
        },
        {
            rule: 'number',
            errorMessage: 'Must be a number',
        }
    ])
    .addField('[name="description"]', [
        {
            rule: 'maxLength',
            value: 255,
            errorMessage: 'Description must be less than 255 characters',
        }
    ])
    .onSuccess((event) => {
        form.submit(); // ✅ Manually submit the form
    });
}
