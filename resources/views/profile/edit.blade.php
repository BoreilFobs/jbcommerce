@extends('layouts.app')
@section('title', 'Profile')

@section('content')
<div class="main-content p-6 w-full h-full">
    <div class="card bg-white rounded-lg shadow-xl p-8">
        <div class="flex items-center justify-between mb-8 border-b border-gray-200 pb-4">
            <h4 class="text-2xl font-bold text-gray-800">Profile</h4>
        </div>
        
        <div class="space-y-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection