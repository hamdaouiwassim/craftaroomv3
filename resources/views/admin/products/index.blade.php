<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @include('admin.inc.messages')


                <div class="bg-gray-50 p-6">
                    <div class="max-w-6xl mx-auto">

                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h1 class="text-2xl font-semibold text-gray-800">Liste des catégories</h1>

                            <div class="flex items-center gap-3">
                                <form method="GET" action="{{ route('admin.products.index') }}">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Rechercher une catégorie..."
                                        class="placeholder:italic placeholder:text-slate-400 block bg-white w-72 border border-slate-200 rounded-md py-2 pl-10 pr-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                                </form>

                                <a href="{{ route('admin.products.create') }}"
                                    class="inline-flex items-center gap-2 bg-sky-600 text-white px-4 py-2 rounded-md shadow hover:bg-sky-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Ajouter
                                </a>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <table class="min-w-full table-auto">
                                <thead class="bg-gray-100">
                                    <tr class="text-left text-sm text-gray-600">
                                        <th class="px-4 py-3 w-12">#</th>
                                        <th class="px-4 py-3">Nom</th>
                                        <th class="px-4 py-3">Description</th>
                                        <th class="px-4 py-3">Statut</th>
                                        <th class="px-4 py-3">Créé le</th>
                                        <th class="px-4 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                                    @forelse($products as $product)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                                            <td class="px-4 py-3">
                                                {{ $product->description }}
                                            </td>
                                            <td class="px-4 py-3">
                                                @if ($product->status === 'active')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Actif</span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactif</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">{{ $product->created_at->format('Y-m-d') }}</td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="inline-flex items-center gap-2">
                                                    <a href="{{ route('admin.products.show', $product) }}"
                                                        class="px-3 py-1.5 border border-slate-200 rounded shadow-sm hover:bg-slate-50"
                                                        title="Modifier">
                                                        👁️
                                                    </a>
                                                    <a href="{{ route('admin.products.edit', $product) }}"
                                                        class="px-3 py-1.5 border border-slate-200 rounded shadow-sm hover:bg-slate-50"
                                                        title="Modifier">
                                                        ✏️
                                                    </a>
                                                    <form method="POST"
                                                        action="{{ route('admin.products.destroy', $product) }}"
                                                        onsubmit="return confirm('Supprimer cette catégorie ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="px-3 py-1.5 border border-red-200 text-red-600 rounded shadow-sm hover:bg-red-50"
                                                            title="Supprimer">
                                                            🗑️
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucune
                                                produit trouvée.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-end mt-4">
                            {{ $products->links() }}
                        </div>

                    </div>
                </div>

            </div>













        </div>
    </div>


</x-admin-layout>
