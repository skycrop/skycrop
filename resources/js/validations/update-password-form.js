import JustValidate from 'just-validate';

export function updatePasswordFormValidation() {
    const form = document.querySelector('#update-password-form');
    if (!form) return;

    const validation = new JustValidate('#update-password-form');

    validation
      .addField('[name="current_password"]', [
        {
          rule: 'required',
          errorMessage: 'Current password is required',
        },
      ])
      .addField('[name="new_password"]', [
        {
          rule: 'required',
          errorMessage: 'New password is required',
        },
        {
          rule: 'minLength',
          value: 8,
          errorMessage: 'New password must be at least 8 characters',
        },
        { 
            rule: 'customRegexp',
            value: /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])/,
            errorMessage: 'Must contain 1 uppercase letter, 1 number & 1 special character',
        },
      ])
      .addField('[name="new_password_confirmation"]', [
        {
          rule: 'required',
          errorMessage: 'Confirm new password is required',
        },
        {
          validator: (value, fields) => {            
            return value === fields['[name="new_password"]'].elem.value;
          },
          errorMessage: 'Passwords do not match',
        },
      ])
      .onSuccess((event) => {
        form.submit();
      });
}
