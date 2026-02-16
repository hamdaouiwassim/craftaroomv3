# Dynamic 3D Engine - Configuration Guide

The 3D engine now supports **dynamic data loading** from your Laravel backend or local configuration!

## 🎯 Features

- **Dual Mode**: Choose between API mode (database) or local hardcoded configuration
- **Model Switching**: Load multiple models and switch between them dynamically
- **Dynamic Floors**: Load floor models from database with custom categories
- **Dynamic Components**: Load material/texture options from database
- **Dynamic Objects**: Load main 3D models from database
- **Custom Icons**: Override category icons through database
- **Custom Names**: Override category display names through database
- **One at a Time**: Render models one by one (by index) for better performance

---

## 🚀 Quick Start

### Option 1: Load from Laravel API (Dynamic Database)

Edit `config.js`:

```javascript
window.VIEWER_CONFIG = {
  useAPI: true,
  apiBaseUrl: 'http://localhost:8000',
  modelType: 'product',  // or 'concept'
  modelId: 123           // Your product/concept ID
};
```

Then open `index.html` in your browser. The engine will fetch all data from:
- `GET /api/3d-viewer/product/{id}` or
- `GET /api/3d-viewer/concept/{id}`

### Option 2: Use Local Configuration

Edit `config.js`:

```javascript
window.VIEWER_CONFIG = {
  useAPI: false,
  
  mainModel: {
    folderPath: 'objects/chair/',
    fileName: 'chair.obj',
    desiredSize: 1.0
  },
  
  components: {
    wood: [
      { url: 'textures/oak.jpg', name: 'Oak' },
      { url: 'textures/walnut.jpg', name: 'Walnut' }
    ]
  },
  
  floors: {
    "floor-wood": [
      { 
        url: 'preview.jpg', 
        folderPath: 'objects/floor1/', 
        fileName: 'floor1.obj', 
        baseSize: 2.0 
      }
    ]
  }
};
```

---

## 📦 API Response Format

Your Laravel API should return JSON in this format:

### Single Model
```json
{
  "mainModel": {
    "path": "/storage/uploads/models/chair/chair.obj",
    "name": "chair.obj",
    "size": 1.0
  },
  
  "components": {
    "wood": [
      {
        "url": "http://localhost/storage/textures/oak.jpg",
        "name": "Oak",
        "_categoryName": "Wood Materials",
        "_categoryIcon": "https://example.com/wood-icon.png"
      }
    ],
    "metal": [
      {
        "url": "http://localhost/storage/textures/steel.jpg",
        "name": "Steel",
        "_categoryName": "Metal Finishes",
        "_categoryIcon": "https://example.com/metal-icon.png"
      }
    ]
  },
  
  "floors": {
    "floor-wood": [
      {
        "url": "http://localhost/storage/floors/preview.jpg",
        "folderPath": "/storage/uploads/floors/wood1/",
        "fileName": "wood1.obj",
        "baseSize": 2.0,
        "_categoryName": "Wooden Floors",
        "_categoryIcon": "https://example.com/floor-icon.png"
      }
    ]
  }
}
```

### Multiple Models (Switchable)
```json
{
  "mainModel": [
    {
      "path": "/storage/uploads/models/chair/chair.obj",
      "name": "chair.obj",
      "size": 1.0
    },
    {
      "path": "/storage/uploads/models/table/table.obj",
      "name": "table.obj",
      "size": 1.5
    }
  ],
  "components": { ... },
  "floors": { ... }
}
```

**Note:** First model loads automatically. Use `switchModel(index)` to change between models.

### Metadata Fields (Optional)

These special fields allow you to customize category appearance:

- `_categoryName`: Display name for the category button
- `_categoryIcon`: URL to icon image for the category button

If not provided, default icons and formatted names will be used.

---

## 🔧 Configuration Properties

### Main Model (Single)
```javascript
{
  folderPath: 'objects/model/',   // Path to model folder
  fileName: 'model.obj',          // OBJ filename
  desiredSize: 1.0                // Scale factor
}
```

### Main Models (Multiple - Rendered One by One)
```javascript
[
  {
    folderPath: 'objects/chair/',
    fileName: 'chair.obj',
    desiredSize: 1.0
  },
  {
    folderPath: 'objects/table/',
    fileName: 'table.obj',
    desiredSize: 1.5
  }
]
// First model (index 0) loads automatically
// Use switchModel(1) to load second model
```

### Components (Materials/Textures)
```javascript
{
  categoryKey: [
    {
      url: 'path/to/texture.jpg',  // Texture image URL
      name: 'Display Name'          // Name shown to user
    }
  ]
}
```

### Floors
```javascript
{
  "floor-categoryKey": [
    {
      url: 'preview.jpg',           // Preview image URL
      folderPath: 'objects/floor/', // Path to floor model folder
      fileName: 'floor.obj',        // OBJ filename
      baseSize: 2.0                 // Scale factor
    }
  ]
}
```

---

## 🎨 Custom Categories

You can define any category names you want. The engine will automatically create buttons for each category.

**Examples:**

**Components:**
- `wood`, `metal`, `fabric`, `paint`, `glass`, `leather`, etc.

**Floors:**
- `floor-wood`, `floor-carpet`, `floor-tile`, `floor-marble`, etc.

**Note:** Floor categories should be prefixed with `floor-` for consistency.

---

## 🗄️ Database Integration

Your Laravel API endpoint (`ThreeDViewerController`) should:

1. Fetch the product/concept from database
2. Get main model path from `model_path` column
3. Get components/materials from related database tables
4. Get available floors from floors database table
5. Format the response as shown above

Example controller:

```php
public function getProductConfig(Product $product)
{
    return response()->json([
        'mainModel' => [
            'path' => Storage::url($product->model_path),
            'name' => basename($product->model_path),
            'size' => $product->model_size ?? 1.0
        ],
        'components' => $this->getProductMaterials($product),
        'floors' => $this->getAvailableFloors()
    ]);
}
```

---

## 📝 Examples

### Example 1: Product from Database

```javascript
// config.js
window.VIEWER_CONFIG = {
  useAPI: true,
  apiBaseUrl: 'http://localhost:8000',
  modelType: 'product',
  modelId: 42
};
```

### Example 2: Concept from Database

```javascript
// config.js
window.VIEWER_CONFIG = {
  useAPI: true,
  apiBaseUrl: 'http://localhost:8000',
  modelType: 'concept',
  modelId: 99
};
```

### Example 3: Local Development (Single Model)

```javascript
// config.js
window.VIEWER_CONFIG = {
  useAPI: false,
  mainModel: {
    folderPath: 'objects/miniVase3/',
    fileName: 'miniVase3.obj',
    desiredSize: 1.0
  },
  components: {
    wood: [
      { url: 'component/assets/wood/wood1.jpg', name: 'Oak' }
    ]
  },
  floors: {
    "floor-simple": [
      { 
        url: 'preview.jpg', 
        folderPath: 'objects/woodfloor/', 
        fileName: 'woodfloor.obj', 
        baseSize: 5.0 
      }
    ]
  }
};
```

### Example 4: Multiple Switchable Models

```javascript
// config.js
window.VIEWER_CONFIG = {
  useAPI: false,
  mainModel: [
    {
      folderPath: 'objects/chair/',
      fileName: 'chair.obj',
      desiredSize: 1.0
    },
    {
      folderPath: 'objects/table/',
      fileName: 'table.obj',
      desiredSize: 1.5
    },
    {
      folderPath: 'objects/lamp/',
      fileName: 'lamp.obj',
      desiredSize: 0.8
    }
  ],
  components: {
    wood: [{ url: 'textures/oak.jpg', name: 'Oak' }]
  },
  floors: {
    "floor-wood": [{ url: 'preview.jpg', folderPath: 'objects/floor/', fileName: 'floor.obj', baseSize: 2.0 }]
  }
};

// Then switch between models:
// switchModel(0) - Chair
// switchModel(1) - Table
// switchModel(2) - Lamp
```

---

## 🐛 Troubleshooting

### Issue: "No configuration loaded"
- **Solution**: Check `config.js` exists and is loaded before `index.html` main script
- **Solution**: Open browser console to see detailed error messages

### Issue: "Failed to load from API"
- **Solution**: Check `apiBaseUrl` is correct
- **Solution**: Check Laravel API routes are registered
- **Solution**: Check CORS settings if API is on different domain
- **Solution**: Check network tab in browser DevTools for actual API response

### Issue: Categories not showing
- **Solution**: Check category keys match expected format (e.g., `floor-` prefix for floors)
- **Solution**: Check each category array has at least one item
- **Solution**: Open console to see initialization logs

### Issue: Icons not loading
- **Solution**: Check icon URLs are accessible
- **Solution**: Check CORS settings for external icon URLs
- **Solution**: Provide fallback icons in `floorlist.js` / `componentlist.js`

---

## 📚 File Structure

```
3dengine/3dEngine/
├── index.html          # Main HTML file (updated with dynamic loading)
├── config.js           # Configuration file (edit this!)
├── api.js              # API module (stores configuration)
├── floor/
│   └── floorlist.js    # Floor UI logic (uses database icons/names)
├── component/
│   └── componentlist.js # Component UI logic (uses database icons/names)
└── README_DYNAMIC.md   # This file
```

---

## ✅ Benefits

- **No Hardcoding**: All data comes from database
- **Easy Updates**: Change data in admin panel, no code changes needed
- **Flexible**: Switch between API and local mode easily
- **Scalable**: Add unlimited categories, models, materials
- **Model Switching**: Easily switch between multiple models using `switchModel(index)`
- **Performance**: Only one model loaded at a time (better memory usage)
- **Customizable**: Override icons and names per category
- **Backward Compatible**: Single model format still works

## 🔄 Model Switching

When you have multiple models configured:

```javascript
// Check total models
getTotalModels(); // Returns: 3

// Check current model
getCurrentModelIndex(); // Returns: 0, 1, 2, etc.

// Switch models
switchModel(0); // Load first model
switchModel(1); // Load second model
switchModel(2); // Load third model
```

See [MODEL_SWITCHING_GUIDE.md](MODEL_SWITCHING_GUIDE.md) for complete switching documentation.

---

## 🔄 Migration from Old Version

If you have the old hardcoded version:

1. Create `config.js` with your settings
2. Update `index.html` (already done)
3. Update `api.js` (already done)
4. Update `floorlist.js` (already done)
5. Update `componentlist.js` (already done)
6. Test with `useAPI: false` first (local mode)
7. Once working, switch to `useAPI: true` (database mode)

---

## 🎉 That's It!

You now have a fully dynamic 3D engine that can load data from your Laravel database!

For questions or issues, check the browser console for detailed logs.
