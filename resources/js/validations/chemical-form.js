import JustValidate from 'just-validate';

export function chemicalFormValidation() {
    const form = document.querySelector('#validateChemicalForm');
    if (!form) return;

    const validation = new JustValidate('#validateChemicalForm');

    validation
        .addField('#name', [
            {
                rule: 'required',
                errorMessage: 'Chemical name is required',
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
