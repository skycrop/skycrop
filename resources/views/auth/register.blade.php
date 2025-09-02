@extends('frontend.layouts.app')
@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('main-content')

<div class="max-w-2xl mx-auto px-6 py-10 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-500 to-green-800 text-white px-6 py-5">
            <h1 class="text-2xl font-bold tracking-wide">Create Your Account</h1>
            <p class="text-sm opacity-90">Create your account and explore seeds for every season</p>
        </div>

        <div class="p-6 space-y-6">
            <!-- Step Indicator -->
            <div class="flex items-center justify-center gap-4 mb-6">
                <div class="flex items-center">
                    <div id="indicator-step-1" class="w-10 h-10 flex items-center justify-center rounded-full bg-green-500 text-white font-bold shadow-md">1</div>
                    <span class="ml-2 text-sm font-medium">Verify Phone</span>
                </div>
                <div class="flex-1 h-1 bg-gray-300 mx-2"></div>
                <div class="flex items-center">
                    <div id="indicator-step-2" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-300 text-white font-bold shadow-md">2</div>
                    <span class="ml-2 text-sm font-medium">Details</span>
                </div>
            </div>

            <!-- Step 1: Phone Verification -->
            <div id="step-1" class="animate-fadeIn">
                <h2 class="text-lg font-semibold mb-3">Enter Phone Number</h2>
                <input type="text" id="phone" placeholder="Enter phone number" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500">
                <p class="text-sm text-red-500 phone-error mb-3"></p>
                <button id="send-otp" 
                    class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg font-medium shadow-md transition">
                    Send OTP
                </button>

                <div id="otp-section" class="hidden mt-4">
                    <input type="text" id="otp" placeholder="Enter OTP" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <p class="text-sm text-red-500 otp-error mb-3"></p>
                    <button id="verify-otp" 
                        class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white py-2 rounded-lg font-medium shadow-md transition">
                        Verify OTP
                    </button>
                </div>
            </div>

            <!-- Step 2: Personal Details -->
            <div id="step-2" class="hidden animate-fadeIn">
                <h2 class="text-lg font-semibold mb-3">Your Details</h2>
                <input type="text" id="full_name" placeholder="Full Name" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                <p class="text-sm text-red-500 name-error mb-3"></p>

                <input type="email" id="email" placeholder="Email Address" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                <p class="text-sm text-red-500 email-error mb-3"></p>

                <input type="password" id="password" placeholder="Password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                <p class="text-sm text-red-500 password-error mb-3"></p>

                <input type="text"  id="city" placeholder="City"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                <p class="text-sm text-red-500 city-error mb-3"></p>

                <input type="text"  id="state" placeholder="State"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                <p class="text-sm text-red-500 state-error mb-3"></p>

                <input type="text"  id="country" placeholder="Country"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                <p class="text-sm text-red-500 country-error mb-3"></p>

                <input type="text"  id="address" placeholder="Complete Address"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                <p class="text-sm text-red-500 address-error mb-3"></p>

                
                <label class="block mb-3">
                    <span class="text-sm font-medium">Profile Photo</span>
                    <input type="file" id="photo"
                        class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg mt-1 cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-400" />
                    <p class="text-sm text-red-500 photo-error mb-3"></p>

                </label>

                <input type="text" placeholder="Referral Code (optional)" id="referral_code"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                <p class="text-sm text-red-500 otp-error mb-3"></p>


                <button id="register-btn" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 disabled:opacity-50 disabled:cursor-not-allowed text-white py-2 rounded-lg font-medium shadow-md transition">
                    Register
                </button>
            </div>
        </div>
    </div>   
</div>
@endsection

@push('scripts')
<script>
window.verifiedPhoneFromServer = @json($verifiedPhone);

let verifiedPhone = "";
// Helper to set verified phone after OTP step
function setVerifiedPhone(phone) {
    verifiedPhone = phone;
    console.log("Verified phone stored:", verifiedPhone);
}

document.addEventListener("DOMContentLoaded", () => {
    const sendOtpBtn = document.getElementById('send-otp');
    const verifyOtpBtn = document.getElementById('verify-otp');
    const otpSection = document.getElementById('otp-section');

    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');

    const indicator1 = document.getElementById('indicator-step-1');
    const indicator2 = document.getElementById('indicator-step-2');

    let phoneNumber = "";

    // Step 1 → Send OTP
    sendOtpBtn.addEventListener('click', () => {
        phoneNumber = document.getElementById('phone').value.trim();
        const errorDiv = document.querySelector('.phone-error');
        errorDiv.textContent = '';

        if (!phoneNumber) {
            errorDiv.textContent = "Please enter a valid phone number.";
            return;
        }
        const onlyDigits = phoneNumber.replace(/\D/g, '');
        if (onlyDigits.length < 10) {
            errorDiv.textContent = "Phone number must be at least 10 digits.";
            return;
        }

        fetch("/temp-user/send-otp", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ mobile_number: phoneNumber })
        })
        .then(res => res.json())
        .then(data => {
            if (data.errors) {
                notyf.error(Object.values(data.errors).join("\n"));
            } else {
                notyf.success('OTP sent successfully');
                otpSection.classList.remove('hidden');
                sendOtpBtn.disabled = true;
                sendOtpBtn.textContent = 'OTP Sent ✔';
            }
        })
        .catch(err => {
            console.error("Error sending OTP:", err);
            notyf.error("Something went wrong while sending OTP.");
        });
    });

    // Step 1 → Verify OTP
    verifyOtpBtn.addEventListener('click', () => {
        const otp = document.getElementById('otp').value.trim();
        const errorDiv = document.querySelector('.otp-error');
        errorDiv.textContent = '';

        if (!otp) {
            errorDiv.textContent = "Please enter the OTP.";
            return;
        }

        fetch("/temp-user/verify-otp", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ mobile_number: phoneNumber, otp: otp })
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                notyf.error(data.error);
            } else {
                notyf.success("OTP verified successfully!");
                setVerifiedPhone(phoneNumber)
                // Switch to Step 2
                step1.classList.add('hidden');
                step2.classList.remove('hidden');

                // Update indicator colors
                indicator1.classList.replace("bg-green-500", "bg-gray-300");
                indicator2.classList.replace("bg-gray-300", "bg-green-500");
            }
        })
        .catch(err => {
            console.error("Error verifying OTP:", err);
            notyf.error("Something went wrong while verifying OTP.");
        });
    });
    
    if (window.verifiedPhoneFromServer) {
        verifiedPhone = window.verifiedPhoneFromServer;
        step1.classList.add('hidden');
        step2.classList.remove('hidden');
        indicator1.classList.replace("bg-green-500", "bg-gray-300");
        indicator2.classList.replace("bg-gray-300", "bg-green-500");
    }

    // Register 
    const registerBtn = document.getElementById('register-btn');

    registerBtn.addEventListener('click', () => {        
        const full_name = document.getElementById('full_name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const city = document.getElementById('city').value.trim();
        const state = document.getElementById('state').value.trim();
        const country = document.getElementById('country').value.trim();
        const address = document.getElementById('address').value.trim();
        const photoInput = document.getElementById('photo');

        const fieldMapping = [
            { value: full_name, errorSelector: '.name-error', message: 'Full name is required.' },
            { value: email, errorSelector: '.email-error', message: 'Email is required.' },
            { value: password, errorSelector: '.password-error', message: 'Password is required.' },
            { value: city, errorSelector: '.city-error', message: 'City is required.' },
            { value: state, errorSelector: '.state-error', message: 'State is required.' },
            { value: country, errorSelector: '.country-error', message: 'Country is required.' },
            { value: address, errorSelector: '.address-error', message: 'Address is required.' }
        ];

        // Clear previous errors
        fieldMapping.forEach(({ errorSelector }) => {
            const errorDiv = document.querySelector(errorSelector);
            if (errorDiv) errorDiv.textContent = '';
        });

        // Validate required fields
        let hasError = false;
        fieldMapping.forEach(({ value, errorSelector, message }) => {
            if (!value) {
                const errorDiv = document.querySelector(errorSelector);
                if (errorDiv) errorDiv.textContent = message;
                hasError = true;
            }
        });
        if (hasError) return;

        // Email pattern check
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            const errorDiv = document.querySelector('.email-error');
            if (errorDiv) errorDiv.textContent = "Please enter a valid email address.";
            return;
        }

        if(photoInput.files.length == 0){
            const errorDiv = document.querySelector('.photo-error');
            if (errorDiv) errorDiv.textContent = "Please upload the profile image.";
            return;
        }
        registerBtn.disabled=true;
        
        const formData = new FormData();
        formData.append('phone_number', verifiedPhone);        
        formData.append('full_name', full_name);
        formData.append('email', email);
        formData.append('password', password);
        formData.append('city', city);
        formData.append('state', state);
        formData.append('country', country);
        formData.append('address', address);
        formData.append('referral_code', document.getElementById('referral_code').value);

        
        if (photoInput.files.length > 0) {
            formData.append('photo', photoInput.files[0]);
        }

        fetch("/register", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                notyf.error(data.error);
                registerBtn.disabled=false;
            } else if (data.success) {
                notyf.success(data.message);
                setTimeout(() => {
                    window.location.href = "/login"; // redirect after register
                }, 1500);
            } else if (data.errors) {
                // Validation errors
                registerBtn.disabled=true;
                notyf.error(Object.values(data.errors).join('<br>'));
            }
        })
        .catch(() => {
            registerBtn.disabled=true;
            notyf.error('Something went wrong while registering');
        });
    });

    // Store phoneNumber when OTP verified
    window.setVerifiedPhone = function (phone) {
        phoneNumber = phone; // called in OTP step after success
        verifiedPhone = phone; // called in OTP step after success
    };


});
</script>


<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.4s ease-in-out;
    }
</style>
@endpush
