@extends('frontend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('main-content')
<div class="min-h-screen bg-green-50 py-10 px-4">
  <div class="max-w-7xl mx-auto bg-white shadow-xl rounded-2xl overflow-hidden flex">
    
    <!-- Sidebar -->
    @include('frontend.layouts.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 p-8">
      <h1 class="text-2xl font-bold text-green-700 mb-6">My Profile</h1>

      <div class="grid md:grid-cols-2 gap-10">
        <!-- Farmer Details -->
        <div>
          <h3 class="text-lg font-semibold text-green-700 mb-4 border-b pb-2">Personal Details</h3>
          <form class="space-y-5">
            <div>
              <label class="block text-sm font-medium text-gray-700">Full Name</label>
              <input type="text" value="{{ $farmerDetail->name }}"  readonly
                     class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Email Address</label>
              <input type="email" value="{{ $farmerDetail->email }}"  readonly
                     class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Phone Number</label>
              <input type="text" value="{{ $farmerDetail->phone_number }}" readonly
                     class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Profile Photo</label>
              <input type="file" 
                     class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
            </div>
            <button type="submit" 
                    class="w-full bg-green-500 text-white py-2 px-4 rounded-lg shadow hover:bg-green-700 transition">
              Save Changes
            </button>
          </form>
        </div>

        <!-- Change Password -->
        <div>
          <h3 class="text-lg font-semibold text-green-700 mb-4 border-b pb-2">Change Password</h3>
          <form class="space-y-5">
            <div>
              <label class="block text-sm font-medium text-gray-700">Current Password</label>
              <input type="password" 
                     class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">New Password</label>
              <input type="password" 
                     class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
              <input type="password" 
                     class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
            </div>
            <button type="submit" 
                    class="w-full bg-yellow-500 text-white py-2 px-4 rounded-lg shadow hover:bg-yellow-600 transition">
              Update Password
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>



@endsection

@push('scripts')

@endpush