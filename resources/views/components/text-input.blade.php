@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-brand focus:ring-brand rounded-lg shadow-sm']) }}>
