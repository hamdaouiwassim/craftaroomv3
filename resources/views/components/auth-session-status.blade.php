@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'p-4 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl shadow-lg flex items-center gap-3 animate-slide-in']) }}>
        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span class="font-semibold">{{ $status }}</span>
    </div>
@endif

<style>
    @keyframes slide-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }
</style>
