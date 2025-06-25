@props(['name', 'image', 'label', 'checked' => false])

<label class="relative overflow-hidden rounded-lg border border-gray-200 hover:border-blue-400 transition-all cursor-pointer">
    <img src="{{ asset('images/' . $image) }}" alt="{{ $label }}" class="w-full h-24 object-cover">
    <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
        <input type="checkbox" name="preferences[]" value="{{ $name }}"
               class="absolute top-2 left-2 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
               {{ $checked ? 'checked' : '' }}>
        <span class="text-white font-medium drop-shadow-md">{{ $label }}</span>
    </div>
</label>
