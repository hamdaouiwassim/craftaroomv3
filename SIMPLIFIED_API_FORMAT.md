# Simplified API Format - Matches Original 3D Engine

The API now returns data in the **exact same format** as the original 3D engine configuration.

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
    "wood": [
      {
        "url": "http://example.com/storage/metals/oak.jpg",
        "name": "Oak",
        "_categoryName": "Wood",
        "_categoryIcon": "http://example.com/storage/metals/wood-icon.png",
        "_categoryRef": "W001"
      },
      {
        "url": "http://example.com/storage/metals/walnut.jpg",
        "name": "Walnut",
        "_categoryName": "Wood",
        "_categoryIcon": "http://example.com/storage/metals/wood-icon.png",
        "_categoryRef": "W001"
      }
    ],
    "metal": [
      {
        "url": "http://example.com/storage/metals/steel.jpg",
        "name": "Steel",
        "_categoryName": "Metal",
        "_categoryIcon": "http://example.com/storage/metals/metal-icon.png",
        "_categoryRef": "M001"
      }
    ]
  },
  
  "floors": {
    "floor-simple": [
      {
        "url": "http://example.com/storage/floors/wood1-preview.jpg",
        "folderPath": "/storage/uploads/floors/wood1/",
        "fileName": "wood1.obj",
        "baseSize": 2.0,
        "_categoryName": "Simple",
        "_categoryIcon": "http://example.com/storage/floors/simple-icon.png"
      },
      {
        "url": "http://example.com/storage/floors/wood2-preview.jpg",
        "folderPath": "/storage/uploads/floors/wood2/",
        "fileName": "wood2.obj",
        "baseSize": 2.5,
        "_categoryName": "Simple",
        "_categoryIcon": "http://example.com/storage/floors/simple-icon.png"
      }
    ],
    "floor-carpet": [
      {
        "url": "http://example.com/storage/floors/carpet1-preview.jpg",
        "folderPath": "/storage/uploads/floors/carpet1/",
        "fileName": "carpet1.obj",
        "baseSize": 2.0,
        "_categoryName": "Carpet",
        "_categoryIcon": "http://example.com/storage/floors/carpet-icon.png"
      }
    ]
  },
  
  "metadata": {
    "productId": 123,
    "productName": "Modern Chair",
    "category": "Furniture"
  }
}
```

## Format Details

### Components Structure

**Original 3D Engine Format:**
```javascript
setComponentsConfig({
  wood: [
    { url: 'textures/oak.jpg', name: 'Oak' },
    { url: 'textures/walnut.jpg', name: 'Walnut' }
  ],
  metal: [
    { url: 'textures/steel.jpg', name: 'Steel' }
  ]
});
```

**Our API Returns (SAME FORMAT + Metadata):**
```json
{
  "wood": [
    {
      "url": "http://example.com/storage/metals/oak.jpg",
      "name": "Oak",
      "_categoryName": "Wood",
      "_categoryIcon": "http://example.com/storage/metals/wood-icon.png"
    }
  ],
  "metal": [...]
}
```

**Key Points:**
- ✅ Category name is the object key (`"wood"`, `"metal"`, `"fabric"`)
- ✅ Value is an array of texture objects
- ✅ Each texture has `url` and `name` (required by 3D engine)
- ✅ Metadata fields (`_categoryName`, `_categoryIcon`) are optional extras for UI enhancement
- ✅ Category key comes from `metals.name` (converted to lowercase with underscores)

### Floors Structure

**Original 3D Engine Format:**
```javascript
setFloorsConfig({
  "floor-wood": [
    { url: "preview.jpg", folderPath: "objects/woodfloor/", fileName: "woodfloor.obj", baseSize: 2.0 }
  ],
  "floor-carpet": [
    { url: "preview2.jpg", folderPath: "objects/carpet/", fileName: "carpet.obj", baseSize: 2.0 }
  ]
});
```

**Our API Returns (SAME FORMAT + Metadata):**
```json
{
  "floor-simple": [
    {
      "url": "http://example.com/storage/floors/wood1-preview.jpg",
      "folderPath": "/storage/uploads/floors/wood1/",
      "fileName": "wood1.obj",
      "baseSize": 2.0,
      "_categoryName": "Simple",
      "_categoryIcon": "http://example.com/storage/floors/simple-icon.png"
    }
  ],
  "floor-carpet": [...]
}
```

**Key Points:**
- ✅ Category name is the object key with `floor-` prefix (`"floor-simple"`, `"floor-carpet"`)
- ✅ Value is an array of floor model objects
- ✅ Each model has `url`, `folderPath`, `fileName`, `baseSize` (required by 3D engine)
- ✅ Metadata fields (`_categoryName`, `_categoryIcon`) are optional extras for UI enhancement
- ✅ Category key comes from `floors.name` (converted to lowercase with dashes, prefixed with `floor-`)

## Database Mapping

### Components (from `metals` and `metal_options`)

```
metals table:
├─ id: 1
├─ name: "Wood"                    → Category key: "wood"
├─ image_url: "metals/wood.png"    → _categoryIcon
└─ metal_options:
   ├─ name: "Oak"                   → name
   ├─ image_url: "metals/oak.jpg"  → url
   ├─ name: "Walnut"                → name
   └─ image_url: "metals/walnut.jpg" → url
```

**Resulting JSON:**
```json
{
  "wood": [
    {"url": "http://.../oak.jpg", "name": "Oak", "_categoryName": "Wood", "_categoryIcon": "http://.../wood.png"},
    {"url": "http://.../walnut.jpg", "name": "Walnut", "_categoryName": "Wood", "_categoryIcon": "http://.../wood.png"}
  ]
}
```

### Floors (from `floors` and `floor_models`)

```
floors table:
├─ id: 1
├─ name: "Simple"                  → Category key: "floor-simple"
├─ icon: "floors/simple.png"       → _categoryIcon
└─ floor_models:
   ├─ name: "Wood Floor 1"         
   ├─ path: "uploads/floors/wood1" → folderPath (+ auto-detect fileName)
   ├─ image: "floors/wood1.jpg"    → url
   ├─ size: 2.0                     → baseSize
   ├─ name: "Wood Floor 2"
   └─ ...
```

**Resulting JSON:**
```json
{
  "floor-simple": [
    {
      "url": "http://.../wood1.jpg",
      "folderPath": "/storage/uploads/floors/wood1/",
      "fileName": "wood1.obj",
      "baseSize": 2.0,
      "_categoryName": "Simple",
      "_categoryIcon": "http://.../simple.png"
    }
  ]
}
```

## Metadata Fields (Optional)

Fields starting with underscore (`_`) are metadata for UI enhancement:

### For Components:
- `_categoryName`: Display name for the category (e.g., "Wood" instead of "wood")
- `_categoryIcon`: URL to category icon image
- `_categoryRef`: Reference code from database

### For Floors:
- `_categoryName`: Display name for the category (e.g., "Simple" instead of "floor-simple")
- `_categoryIcon`: URL to category icon image

These metadata fields:
- ✅ Are extracted by `laravel-viewer.html` to pass separately to the 3D engine
- ✅ Are removed from the actual data before passing to `setComponentsConfig()` / `setFloorsConfig()`
- ✅ Are used to display database icons instead of hardcoded icons
- ✅ Don't break the original 3D engine (they're ignored if not processed)

## How It Works

### 1. Laravel API (Controller)
```php
// Returns original format with metadata
return [
  'components' => [
    'wood' => [
      ['url' => '...', 'name' => 'Oak', '_categoryIcon' => '...'],
      ['url' => '...', 'name' => 'Walnut', '_categoryIcon' => '...']
    ]
  ]
];
```

### 2. Integration Layer (`laravel-viewer.html`)
```javascript
// Extracts metadata and cleans data
const categoriesData = {}; // Extract _categoryName, _categoryIcon
const componentsData = {}; // Clean data (remove _ fields)

// Pass to 3D engine
setComponentsConfig(componentsData, categoriesData);
```

### 3. 3D Engine (`componentlist.js` / `floorlist.js`)
```javascript
// Uses database icons if available
const iconUrl = apiCategories?.[categoryKey]?.icon || defaultIcons[categoryKey];

// Uses database names if available
const displayName = apiCategories?.[categoryKey]?.name || formatKey(categoryKey);
```

## Benefits

✅ **Same Format as Original** - No breaking changes to 3D engine code
✅ **Database-Driven** - All data comes from Laravel database
✅ **Enhanced UI** - Category icons and names from database
✅ **Backward Compatible** - Works with or without metadata
✅ **Simple Structure** - Easy to understand and maintain
✅ **Console Debugging** - Comprehensive logging at every step

## Testing

```javascript
// Check API response
fetch('/api/3d-viewer/product/1')
  .then(r => r.json())
  .then(data => {
    console.log('Components:', Object.keys(data.components));
    console.log('Floors:', Object.keys(data.floors));
  });

// Check what's passed to 3D engine
// Look in console for:
// - "🎨 COMPONENTS DATA RECEIVED FROM LARAVEL API"
// - "🏢 FLOORS DATA RECEIVED FROM LARAVEL API"
```

## Summary

The implementation now uses the **exact same data structure** as the original 3D engine, with optional metadata for UI enhancements. The 3D engine code remains unchanged, and all dynamic data comes from your Laravel database!
