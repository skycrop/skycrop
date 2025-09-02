import JustValidate from 'just-validate';

export function rolesFormValidation() {
    const form = document.querySelector('#validateRoleForm');
    if (!form) return;

    const validation = new JustValidate('#validateRoleForm');

    validation
        .addField('[name="name"]', [
            {
                rule: 'required',
                errorMessage: 'Role name is required',
            },
            {
                rule: 'minLength',
                value: 2,
                errorMessage: 'At least 2 characters required',
            },
        ])
        .addField('[name="permissions[]"]', [
            {
            validator: () => {
                return document.querySelectorAll('[name="permissions[]"]:checked').length > 0;
            },
            errorMessage: 'Please select at least one permission',
            },
        ], {
            errorsContainer: document.getElementById('permissions-error'),
        })
        .onSuccess((event) => {
            form.submit(); // ✅ Manually submit the form
        });
}
