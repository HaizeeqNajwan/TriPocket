@extends('layouts.auth')

@section('content')
<div class="relative min-h-screen flex items-center justify-center px-4 pt-28 z-10">

    <!-- Background -->
    <div class="fixed inset-0 bg-cover bg-center opacity-20 -z-10" style="background-image: url('{{ asset('images/Background.png') }}'); background-attachment: fixed;"></div>

    <!-- Profile Card -->
    <div class="w-full max-w-2xl bg-white/70 dark:bg-gray-900/80 backdrop-blur-md shadow-2xl rounded-3xl px-10 py-12 space-y-8 transition duration-300 ease-in-out">

        <!-- Header -->
        <h2 class="text-3xl font-extrabold text-center text-gray-900 dark:text-white">Edit Profile</h2>

        <!-- Session Alert -->
        @if(session('success'))
            <div class="bg-green-100 text-green-800 border border-green-300 px-4 py-2 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="bg-red-100 text-red-800 border border-red-300 px-4 py-2 rounded-md text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Profile Photo -->
            <div class="flex items-center space-x-6">
                <div class="relative group">
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile Photo"
                         class="w-20 h-20 rounded-full object-cover border-2 border-pink-600 shadow-lg transition duration-200"
                         id="profile-picture-preview">
                    @if(Auth::user()->profile_picture)
                        <button type="button" onclick="confirmDeleteProfilePicture()" 
                                class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6 text-xs flex items-center justify-center shadow hover:bg-red-700">
                            ×
                        </button>
                    @endif
                    <div class="absolute bottom-0 left-0 w-full text-center text-xs text-gray-500 mt-1 group-hover:opacity-100 opacity-0 transition">Current Photo</div>
                </div>
                <div>
                    <input type="file" name="profile_picture" id="profile_picture" class="hidden" accept="image/*" onchange="previewProfilePicture(this)">
                    <label for="profile_picture" class="cursor-pointer text-sm text-pink-700 hover:text-pink-900 underline font-medium">
                        Change Photo
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Accepted: JPG/PNG • Max: 2MB</p>
                    <input type="hidden" name="remove_profile_picture" id="remove_profile_picture" value="0">
                </div>
            </div>

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                <input name="name" value="{{ old('name', $user->name) }}"
                       class="w-full mt-1 px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 transition">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full mt-1 px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 transition">
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Phone</label>
                <input name="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full mt-1 px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 transition">
            </div>

            <!-- Password -->
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Change Password</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300">New Password</label>
                        <input type="password" name="password"
                               class="w-full mt-1 px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-pink-500 focus:outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300">Confirm New Password</label>
                        <input type="password" name="password_confirmation"
                               class="w-full mt-1 px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-pink-500 focus:outline-none transition">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit"
                        class="w-full py-3 rounded-xl bg-pink-700 text-white font-semibold hover:bg-pink-800 active:bg-pink-900 transition-all duration-300 shadow-md">
                    Save Changes
                </button>
            </div>
        </form>

        <!-- Preferences -->
        <div class="pt-4 text-center">
            <a href="{{ route('preferences.select') }}"
               class="text-sm text-pink-700 hover:text-pink-900 font-medium underline transition">
                Edit Preferences
            </a>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    function previewProfilePicture(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('profile-picture-preview').src = e.target.result;
            };
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
