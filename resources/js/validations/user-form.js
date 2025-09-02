import JustValidate from 'just-validate';

export function userFormValidation() {
    const form = document.querySelector('#validateUserForm');
    if (!form) return;

    const validation = new JustValidate('#validateUserForm');

    validation
        .addField('[name="name"]', [
            {
                rule: 'required',
                errorMessage: 'Full name is required',
            },
        ])
        .addField('[name="email"]', [
            {
                rule: 'required',
                errorMessage: 'Email is required',
            },
            {
                rule: 'email',
                errorMessage: 'Please enter a valid email',
            },
        ])
        .addField('[name="password"]', [
            {
                rule: 'required',
                errorMessage: 'Password is required',
            },
            {
                rule: 'minLength',
                value: 8,
                errorMessage: 'Password must be at least 8 characters',
            },
        ])
        .addField('[name="password_confirmation"]', [
            {
                validator: (value, fields) => {
                    return value === fields['[name="password"]']?.elem?.value;
                },
                errorMessage: 'Passwords do not match',
            },
        ])
        .addField('[name="roles[]"]', [
            {
                validator: () => {                    
                    return document.querySelectorAll('[name^="roles["]').length > 1;
                },
                errorMessage: 'Please select at least one role',
            },
        ])
        .addField('[name="username"]', [
            {
                rule: 'required',
                errorMessage: 'Username is required',
            },
        ])
        .onSuccess((event) => {
            form.submit(); // ✅ Manually submit the form
        });
}
