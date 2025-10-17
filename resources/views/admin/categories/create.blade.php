<x-admin-layout>

<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Créer une nouvelle catégorie :
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
  <div class="bg-gray-50 p-6">
            <div class="max-w-6xl mx-auto">
    @include("admin.inc.messages")
        <form action="{{ route('admin.categories.store') }}" method="POST" class="mt-4" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nom de la catégorie :</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
             @error("name")
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>

            @enderror
            </div>
 <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Etat de la catégorie :</label>
                <select  name="status" id="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    <option value="active">Actif</option>
                    <option value="inactive">Non actif</option>

                </select>
                @error("status")
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>

            @enderror
            </div>
             <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700">Type de la catégorie :</label>
                <select onchange="toggleParentCategory()" name="type" id="type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    <option value="main">Principale</option>
                    <option value="sub">Secondaire</option>

                </select>
                @error("type")
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>

            @enderror
            </div>

            <div class="mb-4" id="parent-category-group">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Catégorie parente :</label>
                <select name="category_id" id="category_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach

                </select>
                @error("category_id")
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>

            @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description de la catégorie :</label>
                <textarea name="description" id="description" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" ></textarea>
              @error("description")
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>

            @enderror
            </div>
            <div class="mb-4">
                <label for="icon" class="block text-sm font-medium text-gray-700">Icon de la catégorie :</label>
                <input type="file" name="icon" id="icon" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" >
            @error("icon")
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>

            @enderror
            </div><div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md">Ajouter</button>

                </div> </form>
        </div>
        </div>
</div>

    </div>

    <script>
    // Get the elements we'll be working with
    const categoryTypeSelect = document.getElementById('type');
    const parentCategoryGroup = document.getElementById('parent-category-group');

    // Function to show/hide the parent category field
    function toggleParentCategory() {
        // Check the selected value of the type dropdown
        if (categoryTypeSelect.value === 'main') {
            // 'physical' is 'Principale', so hide the parent category field
            parentCategoryGroup.style.display = 'none';
            // You might want to optionally reset the value or disable the select here
            // document.getElementById('category_id').value = '';
        } else {
            // 'digital' is 'Secondaire', so show the parent category field
            parentCategoryGroup.style.display = 'block';
        }
    }

    // 1. Call the function once when the page loads to set the initial state
    toggleParentCategory();

    // 2. The 'onchange' handler in the HTML will call this function every time the type is changed.
</script>
</x-admin-layout>
