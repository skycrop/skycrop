import JustValidate from 'just-validate';

export function addressFormValidation() {
    const form = document.querySelector('#validateAddressForm');
    if (!form) return;

    const validation = new JustValidate('#validateAddressForm', {
        errorFieldCssClass: 'border-red-500',
        errorLabelCssClass: 'text-red-500 text-xs mt-1',
    });

    validation
        .addField('#full_name', [
            {
                rule: 'required',
                errorMessage: 'Full Name is required',
            },
            {
                rule: 'minLength',
                value: 3,
                errorMessage: 'Name must be at least 3 characters',
            },
        ])
        .addField('#address_line_1', [
            {
                rule: 'required',
                errorMessage: 'Address Line 1 is required',
            },
            {
                rule: 'minLength',
                value: 5,
                errorMessage: 'Please enter a valid address',
            },
        ])
        .addField('#city', [
            {
                rule: 'required',
                errorMessage: 'City is required',
            },
        ])
        .addField('#state', [
            {
                rule: 'required',
                errorMessage: 'State is required',
            },
        ])
        .addField('#zipcode', [
            {
                rule: 'required',
                errorMessage: 'Zip Code is required',
            },
            {
                rule: 'number',
                errorMessage: 'Zip Code must be numeric',
            },
            {
                rule: 'minLength',
                value: 5,
                errorMessage: 'Zip Code must be at least 5 digits',
            },
        ])
        .addField('#phone', [
            {
                rule: 'required',
                errorMessage: 'Phone Number is required',
            },
            {
                rule: 'customRegexp',
                value: /^[0-9]{10}$/,
                errorMessage: 'Enter a valid 10-digit phone number',
            },
        ])
        .onSuccess((event) => {
            form.submit();
        });
}
