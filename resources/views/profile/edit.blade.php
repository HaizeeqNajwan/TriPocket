@extends('layouts.auth')

@section('content')
<div class="relative min-h-screen flex items-center justify-center px-4 pt-20 z-10">
    <!-- Background Image -->
    <div class="fixed inset-0 bg-cover bg-center opacity-30 -z-10" style="background-image: url('{{ asset('images/Background.png') }}'); background-attachment: fixed;"></div>

    <!-- Card -->
    <div class="w-full max-w-lg bg-white dark:bg-gray-900 shadow-xl rounded-xl p-6 space-y-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Profile</h2>

        @if(session('success'))
            <div class="text-green-700 bg-green-100 px-4 py-2 rounded">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="text-red-700 bg-red-100 px-4 py-2 rounded">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Profile Picture -->
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile Photo" 
                         class="w-16 h-16 rounded-full object-cover shadow"
                         id="profile-picture-preview">
                    @if(Auth::user()->profile_picture)
                        <button type="button" onclick="confirmDeleteProfilePicture()"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                            ×
                        </button>
                    @endif
                </div>
                <div>
                    <input type="file" name="profile_picture" id="profile_picture" 
                           accept="image/*" class="hidden"
                           onchange="previewProfilePicture(this)">
                    <label for="profile_picture" class="cursor-pointer text-sm text-pink-700 hover:text-pink-900 font-medium underline">
                        Change Photo
                    </label>
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG up to 2MB</p>
                    <input type="hidden" name="remove_profile_picture" id="remove_profile_picture" value="0">
                </div>
            </div>

            <!-- Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Name</label>
                <input name="name" value="{{ old('name', $user->name) }}" 
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100 dark:bg-gray-800 focus:outline-pink-600">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Email</label>
                <input name="email" type="email" value="{{ old('email', $user->email) }}" 
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100 dark:bg-gray-800">
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Phone</label>
                <input name="phone" value="{{ old('phone', $user->phone) }}" 
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100 dark:bg-gray-800">
            </div>

            <!-- Change Password -->
            <div class="pt-4 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Change Password</h3>
                <div class="space-y-2">
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300">New Password</label>
                        <input type="password" name="password" 
                               class="w-full px-4 py-2 border rounded-lg bg-gray-100 dark:bg-gray-800">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300">Confirm New Password</label>
                        <input type="password" name="password_confirmation" 
                               class="w-full px-4 py-2 border rounded-lg bg-gray-100 dark:bg-gray-800">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-3 rounded-lg bg-pink-800 text-white hover:bg-pink-700 transition">
                Save Changes
            </button>
        </form>

        <!-- Preferences Button -->
        <div class="text-center pt-4">
            <a href="{{ route('preferences.select') }}" class="inline-block text-pink-700 hover:text-pink-900 font-medium underline">
                Edit Preferences
            </a>
        </div>
    </div>
</div>

<script>
    function previewProfilePicture(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-picture-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function confirmDeleteProfilePicture() {
        if (confirm('Are you sure you want to remove your profile picture?')) {
            document.getElementById('remove_profile_picture').value = '1';
            document.getElementById('profile-picture-preview').src = '{{ asset("images/default-profile.jpg") }}';
            document.getElementById('profile_picture').value = '';
        }
    }
</script>
@endsection