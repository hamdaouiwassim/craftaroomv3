{{-- ✅ Success Message --}}
  @if (session('success'))
    <div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-200">
      {{ session('success') }}
    </div>
  @endif

  {{-- ⚠️ Validation Errors --}}
  @if ($errors->any())
    <div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-200">
      <strong>Veuillez corriger les erreurs suivantes :</strong>
      <ul class="mt-2 list-disc list-inside text-sm">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
