@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'mt-2 p-3 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 rounded-lg']) }}>
        <ul class="text-sm text-red-700 space-y-1">
        @foreach ((array) $messages as $message)
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </li>
        @endforeach
    </ul>
    </div>
@endif
