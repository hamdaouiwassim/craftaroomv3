# Floor System Integration

The 3D viewer now loads floor models dynamically from the Laravel database.

## Database Structure

### Tables

1. **`floors`** - Floor categories
   - `id` - Primary key
   - `name` - Category name (e.g., "Simple", "Carpet", "Tile")
   - `icon` - Icon image path (stored in `storage/`)
   - `created_at`, `updated_at`

2. **`floor_models`** - Individual floor models
   - `id` - Primary key
   - `floor_id` - Foreign key to `floors.id`
   - `name` - Model name (e.g., "Wood Floor 1")
   - `path` - Path to the 3D model (directory or OBJ file)
   - `image` - Preview image path (stored in `storage/`)
   - `size` - Base size for the model (float, default: 2.0)
   - `created_at`, `updated_at`

## How It Works

### 1. User Flow
```
Click "Floor" button 
  → Shows floor categories (with icons from database)
    → Click a category
      → Shows all floor models for that category (preview images)
        → Click a model image
          → Loads the 3D floor model in the viewer
```

### 2. API Flow

**Endpoint:** `/api/3d-viewer/product/{product}` or `/api/3d-viewer/concept/{concept}`

**Response includes floors:**
```json
{
  "mainModel": {...},
  "components": {...},
  "floors": {
    "categories": {
      "floor-simple": {
        "name": "Simple",
        "icon": "http://example.com/storage/floors/simple-icon.png"
      },
      "floor-carpet": {
        "name": "Carpet",
        "icon": "http://example.com/storage/floors/carpet-icon.png"
      }
    },
    "models": {
      "floor-simple": [
        {
          "name": "Wood Floor 1",
          "url": "http://example.com/storage/floors/wood1-preview.jpg",
          "folderPath": "/storage/uploads/floors/wood1/",
          "fileName": "wood1.obj",
          "baseSize": 2.0
        }
      ]
    }
  }
}
```

### 3. File Structure

Floor 3D models should be stored in `storage/app/public/uploads/floors/` (or any path):

```
storage/app/public/uploads/floors/
├── wood1/
│   ├── wood1.obj
│   ├── wood1.mtl
│   └── textures/
│       └── wood_texture.jpg
├── carpet1/
│   ├── carpet1.obj
│   └── carpet1.mtl
└── ...
```

Preview images should be stored separately:
```
storage/app/public/floors/
├── previews/
│   ├── wood1-preview.jpg
│   ├── carpet1-preview.jpg
│   └── ...
└── icons/
    ├── simple-icon.png
    ├── carpet-icon.png
    └── ...
```

## Adding Floors to Database

### Method 1: Using Tinker

```php
php artisan tinker

// Create a floor category
$floor = Floor::create([
    'name' => 'Simple',
    'icon' => 'floors/icons/simple-icon.png'
]);

// Add floor models to this category
$floor->floorModels()->create([
    'name' => 'Wood Floor 1',
    'path' => 'uploads/floors/wood1',
    'image' => 'floors/previews/wood1-preview.jpg',
    'size' => 2.0
]);

$floor->floorModels()->create([
    'name' => 'Wood Floor 2',
    'path' => 'uploads/floors/wood2',
    'image' => 'floors/previews/wood2-preview.jpg',
    'size' => 2.5
]);
```

### Method 2: Using Seeder

Create a seeder:
```bash
php artisan make:seeder FloorSeeder
```

Edit `database/seeders/FloorSeeder.php`:
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Floor;

class FloorSeeder extends Seeder
{
    public function run()
    {
        $floors = [
            [
                'name' => 'Simple',
                'icon' => 'floors/icons/simple-icon.png',
                'models' => [
                    ['name' => 'Wood Floor 1', 'path' => 'uploads/floors/wood1', 'image' => 'floors/previews/wood1.jpg', 'size' => 2.0],
                    ['name' => 'Wood Floor 2', 'path' => 'uploads/floors/wood2', 'image' => 'floors/previews/wood2.jpg', 'size' => 2.0],
                ]
            ],
            [
                'name' => 'Carpet',
                'icon' => 'floors/icons/carpet-icon.png',
                'models' => [
                    ['name' => 'Carpet 1', 'path' => 'uploads/floors/carpet1', 'image' => 'floors/previews/carpet1.jpg', 'size' => 2.0],
                ]
            ],
        ];

        foreach ($floors as $floorData) {
            $floor = Floor::create([
                'name' => $floorData['name'],
                'icon' => $floorData['icon'],
            ]);

            foreach ($floorData['models'] as $modelData) {
                $floor->floorModels()->create($modelData);
            }
        }
    }
}
```

Run the seeder:
```bash
php artisan db:seed --class=FloorSeeder
```

## Path Format

The `path` field in `floor_models` table should contain:
- **Directory path** for extracted models: `uploads/floors/wood1`
- **File path** for single OBJ files: `uploads/floors/wood1/wood1.obj`

The API will automatically:
- Detect if it's a directory or file
- Search for `.obj` files in directories
- Format the paths correctly for the 3D viewer

## Testing

1. Populate the database with floor categories and models
2. Upload 3D model files to `storage/app/public/`
3. Upload preview images and icons
4. Run `php artisan storage:link` if not already done
5. Open a product or concept detail page
6. Click the "Floor" button in the 3D viewer
7. Select a category → Select a model → See it load!

## Console Logs

When debugging, check the browser console for:
- `🏢 Floors received from API:` - Raw API response
- `🏢 Setting floors config with categories:` - Categories and models being set
- `✅ Floors data loaded:` - Floor categories loaded
- `📁 Floor categories from API:` - Categories with icons
- `✅ Created X floor category buttons` - Buttons created
- `🖼️ Showing X floor model images for:` - Images displayed
- `🏢 Loading floor model:` - Model being loaded
- `✅ Floor model loaded successfully!` - Success!

## Features

✅ Dynamic floor categories from database  
✅ Custom icons per category  
✅ Multiple floor models per category  
✅ Preview images for each model  
✅ Configurable base size per model  
✅ Automatic OBJ file detection in directories  
✅ Support for extracted ZIP models  
✅ Scale adjustment for floor models  
✅ Reset floor functionality  
