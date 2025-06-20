<div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="max-w-xl">
    <form method="POST" action="{{ route('profile.update-picture') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label for="profile_photo" class="block text-sm font-medium text-gray-700">Upload new profile photo</label>
        <input type="file" name="profile_photo" id="profile_photo" class="mt-2 border rounded p-2 w-full">
    </div>

    <button type="submit" class="px-4 py-2 bg-pink-900 text-white rounded hover:bg-pink-700">
        Save
    </button>
</form>

    </div>
</div>

<script>
    document.getElementById('profile_picture').addEventListener('change', function(event) {
        let reader = new FileReader();
        reader.onload = function() {
            let output = document.getElementById('profilePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    });
</script>
