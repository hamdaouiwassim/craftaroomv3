# Complete 3D Engine Integration with Laravel

This document explains the full integration of the 3D engine with dynamic Laravel data.

## Overview

The 3D viewer uses the **EXACT SAME UI and features** as the original 3D engine (`3dengine/3dEngine/`), but all data is now loaded dynamically from the Laravel database:

- ✅ **Main Model** (Product/Concept 3D models)
- ✅ **Components** (Material textures from metals/metal_options)
- ✅ **Floors** (Floor models from floors/floor_models)

## Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Laravel Backend                           │
├─────────────────────────────────────────────────────────────┤
│  API Controller (ThreeDViewerController.php)                 │
│    ├─ getProductConfig($product)                            │
│    ├─ getConceptConfig($concept)                            │
│    └─ Returns:                                              │
│        ├─ mainModel  {path, name, size}                     │
│        ├─ components {categories, textures}                  │
│        └─ floors     {categories, models}                    │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│               Iframe Integration Layer                       │
├─────────────────────────────────────────────────────────────┤
│  laravel-viewer.html                                         │
│    1. Fetches config from Laravel API                       │
│    2. Calls setMainModel()                                   │
│    3. Calls setComponentsConfig(textures, categories)        │
│    4. Calls setFloorsConfig(models, categories)              │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│                 Original 3D Engine (Unmodified)              │
├─────────────────────────────────────────────────────────────┤
│  api.js        - Configuration storage                       │
│  main.js       - 3D rendering (Three.js)                     │
│  floorlist.js  - Floor UI and logic                          │
│  componentlist.js - Component/texture UI and logic           │
└─────────────────────────────────────────────────────────────┘
```

## Database Structure

### Products/Concepts
- `products` / `concepts` - Main objects
- `threemodels` - 3D model files (OBJ + MTL)

### Components (Materials/Textures)
- `metals` - Component categories (e.g., "Wood", "Metal", "Fabric")
  - `id`, `name`, `image_url` (category icon), `ref`
- `metal_options` - Individual textures
  - `id`, `metal_id`, `name`, `image_url` (texture image), `ref`

### Floors
- `floors` - Floor categories (e.g., "Simple", "Carpet", "Tile")
  - `id`, `name`, `icon` (category icon)
- `floor_models` - Individual floor models
  - `id`, `floor_id`, `name`, `path` (to OBJ file), `image` (preview), `size`

## API Response Format

### GET `/api/3d-viewer/product/{id}` or `/api/3d-viewer/concept/{id}`

```json
{
  "mainModel": {
    "type": "extracted",
    "path": "/storage/uploads/models/chair/chair.obj",
    "name": "chair.obj",
    "directory": "/storage/uploads/models/chair",
    "size": 1.0
  },
  
  "components": {
    "categories": {
      "wood": {
        "name": "Wood",
        "icon": "http://example.com/storage/metals/wood-icon.png",
        "ref": "W001"
      },
      "metal": {
        "name": "Metal",
        "icon": "http://example.com/storage/metals/metal-icon.png",
        "ref": "M001"
      }
    },
    "textures": {
      "wood": [
        {
          "id": 1,
          "name": "Oak",
          "url": "http://example.com/storage/metals/oak.jpg",
          "ref": "W001-OAK"
        },
        {
          "id": 2,
          "name": "Walnut",
          "url": "http://example.com/storage/metals/walnut.jpg",
          "ref": "W001-WAL"
        }
      ],
      "metal": [...]
    }
  },
  
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
      ],
      "floor-carpet": [...]
    }
  },
  
  "metadata": {
    "productId": 123,
    "productName": "Modern Chair",
    "category": "Furniture"
  }
}
```

## User Flow

### Main Model Loading
1. Page loads → Blade component renders iframe
2. Iframe loads `laravel-viewer.html?type=product&id=123`
3. JavaScript fetches `/api/3d-viewer/product/123`
4. Calls `setMainModel({folderPath, fileName, desiredSize})`
5. `main.js` loads the 3D model using Three.js

### Component (Texture) Selection
1. User clicks "Components" button in sidebar
2. Component categories appear (icons from database)
3. User clicks a category (e.g., "Wood")
4. Texture images appear (from metal_options)
5. User clicks a texture image
6. `main.js` applies the texture to the 3D model

### Floor Selection
1. User clicks "Floor" button in sidebar
2. Floor categories appear (icons from database)
3. User clicks a category (e.g., "Simple")
4. Floor model preview images appear
5. User clicks a preview image
6. `main.js` loads and displays the floor model

## File Structure

```
public/viewer3d/                          (Original 3D engine files)
├── api.js                                 ✅ Updated to support categories
├── main.js                                ✅ Original (unchanged)
├── styles.css                             ✅ Original (unchanged)
├── sidebar.css                            ✅ Original (unchanged)
│
├── floor/
│   ├── floorlist.js                       ✅ Updated to use DB icons/names
│   ├── floor-list.css                     ✅ Original (unchanged)
│   └── floor-list.html                    ✅ Original (unchanged)
│
├── component/
│   ├── componentlist.js                   ✅ Updated to use DB icons/names
│   ├── components-list.css                ✅ Original (unchanged)
│   └── components-list.html               ✅ Original (unchanged)
│
└── laravel-viewer.html                    ✅ New integration file

resources/views/components/
└── 3d-viewer-original.blade.php           ✅ Blade component (iframe wrapper)

app/Http/Controllers/Api/
└── ThreeDViewerController.php             ✅ API controller

routes/
└── api.php                                 ✅ API routes
```

## Key Features

### ✅ Same UI as Original
- All CSS styles unchanged
- All HTML structure unchanged
- All UI interactions unchanged

### ✅ Dynamic Data from Database
- **Main models** from `threemodels` table
- **Component categories** from `metals` table (with icons)
- **Component textures** from `metal_options` table
- **Floor categories** from `floors` table (with icons)
- **Floor models** from `floor_models` table

### ✅ Comprehensive Console Logging
Every step is logged:
- API data reception
- Data processing
- Category creation
- Button creation
- User interactions
- Model loading

## Testing

### 1. Check Database
```bash
php artisan tinker

# Check metals (components)
>>> Metal::with('metalOptions')->get()

# Check floors
>>> Floor::with('floorModels')->get()
```

### 2. Check API Response
```bash
curl http://localhost/api/3d-viewer/product/1 | jq
```

### 3. Check Browser Console
Open product/concept detail page:
1. Look for: `🏢 FLOORS DATA RECEIVED FROM LARAVEL API`
2. Look for: `🎨 COMPONENTS DATA RECEIVED FROM LARAVEL API`
3. Look for: `🔨 CREATING FLOOR CATEGORY BUTTONS...`
4. Look for: `🔨 CREATING COMPONENT CATEGORY BUTTONS...`

### 4. Test User Flow
1. **Main model** should load automatically
2. Click **"Toggle Tools"** → Sidebar appears
3. Click **"Components"** → Categories appear with database icons
4. Click a category → Textures appear
5. Click a texture → Applied to model
6. Click **"Floor"** → Categories appear with database icons
7. Click a category → Floor models appear
8. Click a model → Loads as floor

## Console Logs Reference

See `FLOOR_CONSOLE_LOGS.md` for detailed explanation of all console logs.

## Troubleshooting

### No Data Showing?
- Check: `⚠️ No floors/components data received from API`
- **Solution**: Database is empty, run seeders

### Wrong Icons?
- Check: `Icon: Using default`
- **Solution**: Add `image_url` (for metals) or `icon` (for floors) in database

### Model Won't Load?
- Check browser console for errors
- Check file exists: `storage/app/public/uploads/...`
- Check permissions: `chmod 755 storage/`

### API Errors?
- Check Laravel logs: `tail -f storage/logs/laravel.log`
- Check API response: Browser Network tab

## Summary

The integration keeps the **original 3D engine EXACTLY as is** and simply feeds it dynamic data from Laravel:

1. **Same UI** ✅
2. **Same features** ✅
3. **Same code** (main.js, CSS, HTML) ✅
4. **Dynamic data** (from Laravel database) ✅

Everything works exactly like the original, but the data comes from your Laravel application instead of being hardcoded!
