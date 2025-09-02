import JustValidate from 'just-validate';

export function brandFormValidation() {
    const form = document.querySelector('#validateBrandForm');
    if (!form) return;

    const validation = new JustValidate('#validateBrandForm');

    validation
        .addField('#name', [
            {
                rule: 'required',
                errorMessage: 'Brand name is required',
            },
            {
                rule: 'minLength',
                value: 2,
                errorMessage: 'At least 2 characters required',
            },
        ])
        .onSuccess((event) => {
            form.submit(); // ✅ Manually submit the form
        });
}
