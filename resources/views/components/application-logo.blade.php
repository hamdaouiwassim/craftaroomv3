@props([
    'variant' => 'dark',
    'alt' => config('app.name', 'CraftARoom'),
])

<img
    src="{{ asset($variant === 'light' ? 'images/branding/logo-light.png' : 'images/branding/logo-dark.png') }}"
    alt="{{ $alt }}"
    {{ $attributes }}
/>
