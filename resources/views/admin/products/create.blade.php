<x-admin-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Créer un nouvel produit :
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="bg-gray-50 p-6">
                <div class="max-w-6xl mx-auto">
                    @include('admin.inc.messages')
                    <form action="{{ route('admin.products.store') }}" method="POST" class="mt-4"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nom de la produit
                                :</label>
                            <input type="text" name="name" id="name"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Categorie de la produit
                                :</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                @foreach ($categories as $category)
                                    <optgroup label="{{ $category->name }}">
                                        @foreach ($category->sub_categories as $cat)
                                            <option value="{{ $cat->id }}"> {{ $cat->name }} </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach



                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="currency_id" class="block text-sm font-medium text-gray-700">Currency de la produit
                                :</label>
                            <select name="currency_id" id="currency_id"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">

                                        @foreach ($currencies as $currency)
                                            <option value="{{ $currency->id }}"> {{ $currency->name }} ( {{ $currency->symbol }} ) </option>
                                        @endforeach




                            </select>
                            @error('currency_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Etat de la produit
                                :</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                <option value="active">Actif</option>
                                <option value="inactive">Non actif</option>

                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description de la
                                produit :</label>
                            <textarea name="description" id="description" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"></textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                          <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Prix de la produit
                                :</label>
                            <input type="number" name="price" id="price"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="photos" class="block text-sm font-medium text-gray-700">photos de la produit
                                :</label>
                            <input type="file" maxlength="3" accept="image/*" multiple name="photos" id="photos"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                            @error('photos')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md">Ajouter</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>


</x-admin-layout>
