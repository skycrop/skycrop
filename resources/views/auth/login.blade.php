@extends('frontend.layouts.app')
@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('main-content')

<div class="max-w-2xl mx-auto px-6 py-10 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
    
        <div class="bg-gradient-to-r from-green-500 to-green-800 text-white px-6 py-5">
                <h1 class="text-2xl font-bold tracking-wide">Login to Your Account</h1>
                <p class="text-sm opacity-90">Sign in to grow your garden and nurture your dreams</p>
        </div>

        <div class="p-6 space-y-6">
            
            {{-- Laravel default login form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email / Mobile --}}
                <div>
                    <label for="login" class="block mb-1 text-sm font-medium text-gray-700">
                        {{ __('Email or Mobile') }}
                    </label>
                    <input id="login" name="login" type="text" value="{{ old('login') }}"
                        autofocus
                        placeholder="Enter your email or mobile"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 px-4 py-2">
                    @error('login')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block mb-1 text-sm font-medium text-gray-700">
                        {{ __('Password') }}
                    </label>
                    <div class="relative">
                        <input id="password" name="password" type="password" required
                            placeholder="Enter your password"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 px-4 py-2 pr-10">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">                        
                            <img id="eye-icon" src="{{ asset('images/icons/view.png') }}" alt="Toggle Password Visibility" class="h-5 w-5" />
                        </button>
                    </div>
                    @error('password')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Forgot Password --}}
                <div class="text-right">
                    <a href="{{ route('password.request') }}" class="text-sm text-orange-600 hover:underline">
                        {{ __('Forgot your password?') }}
                    </a>
                </div>

                {{-- Login Button --}}
                <div>
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold py-2 rounded-lg shadow-md transition duration-200">
                        {{ __('Login') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
function togglePassword() {
    const pwd = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');
    const isPassword = pwd.type === 'password';

    pwd.type = isPassword ? 'text' : 'password';

    if (isPassword) {
        // When password is visible, show "eye-close" icon
        eyeIcon.src = "{{ asset('images/icons/hide.png') }}";
        eyeIcon.alt = "Hide password";
    } else {
        // When password is hidden, show "eye-open" icon
        eyeIcon.src = "{{ asset('images/icons/view.png') }}";
        eyeIcon.alt = "Show password";
    }
}
</script>
    
@endpush