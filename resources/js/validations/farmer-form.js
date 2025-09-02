import JustValidate from 'just-validate';

export function farmerFormValidation() {
    const form = document.querySelector('#validateFarmerForm');
    if (!form) return;

    const validation = new JustValidate('#validateFarmerForm');

    validation        
        .addField('[name="name"]', [
            { rule: 'required', errorMessage: 'Name is required' },
        ])
        .addField('[name="phone_number"]', [
            { rule: 'required', errorMessage: 'Phone number is required' },
            { rule: 'number', errorMessage: 'Enter a valid phone number' },
            { rule: 'minLength', value: 10, errorMessage: 'Must be at least 10 digits' },
        ])
        .addField('[name="email"]', [
            { rule: 'required', errorMessage: 'Email is required' },
            { rule: 'email', errorMessage: 'Enter a valid email' },
        ])
        .addField('[name="password"]', [
            { rule: 'required', errorMessage: 'Password is required' },
            { rule: 'minLength', value: 8, errorMessage: 'Minimum 8 characters' },
            { 
                rule: 'customRegexp',
                value: /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])/,
                errorMessage: 'Must contain 1 uppercase letter, 1 number & 1 special character',
            },
        ])
        .addField('[name="referral_code"]', [
            { rule: 'minLength', value: 4, errorMessage: 'Referral code too short' },
        ])
        .addField('[name="city"]', [
            { rule: 'required', errorMessage: 'City name is required' },
        ])
        .addField('[name="state"]', [
            { rule: 'required', errorMessage: 'State name is required' },
        ])
        .addField('[name="country"]', [
            { rule: 'required', errorMessage: 'Country name is required' },
        ])
        .addField('[name="address"]', [
            { rule: 'required', errorMessage: 'Complete address is required' },
        ])
        .addField('[name="photo"]', [
            { 
                validator: (value, fields) => {
                    const fileInput = fields['[name="photo"]']?.elem;
                    if (!fileInput || fileInput.files.length === 0) return false;

                    const file = fileInput.files[0];
                    const allowedTypes = ['image/jpeg', 'image/png'];

                    return allowedTypes.includes(file.type);
                },
                errorMessage: 'Please upload a valid image file (JPG, PNG)',
            },
        ])
        .onSuccess((event) => {
            form.submit(); // ✅ Manually submit the form
        });
}
