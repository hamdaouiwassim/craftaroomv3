# 3D Viewer Integration Documentation

## Overview
The 3D viewer has been successfully integrated into the Laravel application as a standalone Blade component, replacing the previous iframe implementation. This provides better performance, native integration with your application, and hierarchical material selection.

## Files Created & Modified

### 1. New Blade Component
**File**: `resources/views/components/3d-viewer.blade.php`
- Self-contained Alpine.js powered 3D viewer
- Includes Three.js integration
- Supports hierarchical material selection (main metals → sub-metals)
- Loading states, error handling, and retry functionality
- Interactive controls (reset view, fullscreen, screenshot)
- Double-click to select model parts for material application

### 2. API Controller
**File**: `app/Http/Controllers/Api/ThreeDViewerController.php`
- Provides 3D viewer configuration via REST API
- Methods:
  - `getProductConfig(Product $product)` - Returns product's 3D model configuration
  - `getConceptConfig(Concept $concept)` - Returns concept's 3D model configuration
  - `getProductMaterials(Product $product)` - Returns hierarchical materials structure
  - `getConceptMaterials(Concept $concept)` - Returns hierarchical materials structure
  - `getAvailableFloors()` - Returns floor models

### 3. API Routes
**File**: `routes/api.php`
- `GET /api/3d-viewer/product/{product}` - Get product configuration
- `GET /api/3d-viewer/concept/{concept}` - Get concept configuration

### 4. Updated Views
**Files**:
- `resources/views/product-details.blade.php` - Replaced iframe with `<x-3d-viewer>` component
- `resources/views/concept-details.blade.php` - Replaced iframe with `<x-3d-viewer>` component

### 5. Helper Module (Optional)
**File**: `public/3d-viewer/js/model-loader.js`
- Advanced model loading utilities
- Can be used for future enhancements
- Not currently required by the main component

## Usage

### In Blade Templates

```blade
<x-3d-viewer 
    model-type="product"          {{-- or "concept" --}}
    :model-id="$product->id"
    height="600px"
    :enable-customization="true"
    :show-controls="true"
/>
```

### Component Props
- **model-type** (string, required): Either 'product' or 'concept'
- **model-id** (integer, required): ID of the product or concept
- **height** (string, optional): CSS height value (default: '600px')
- **show-controls** (boolean, optional): Show viewer controls (default: true)
- **enable-customization** (boolean, optional): Enable material selection (default: true)

## Features

### 1. Hierarchical Material Selection
Materials are displayed in two levels:
- **Level 1**: Main metal categories (e.g., "Gold", "Silver", "Bronze")
- **Level 2**: Sub-metal options within each category (e.g., "Rose Gold", "White Gold", "Yellow Gold")

**User Flow**:
1. Click the "Customize Materials" button at the bottom
2. Select a main metal category
3. View all sub-metals within that category
4. Double-click a part of the 3D model to select it
5. Click a sub-metal to apply it to the selected part

### 2. Interactive Controls
- **Reset View**: Return camera to default position
- **Fullscreen**: Toggle fullscreen mode
- **Screenshot**: Capture current view as PNG image

### 3. Loading States
- Progress bar during model loading
- Custom loading messages
- Error handling with retry option

### 4. Responsive Design
- Mobile-friendly interface
- Touch controls support via OrbitControls
- Adaptive layout for different screen sizes

## API Response Structure

### Product/Concept Configuration
```json
{
  "mainModel": {
    "type": "extracted",
    "path": "/storage/products/models/abc123/",
    "name": "model.obj",
    "size": 1.0
  },
  "components": {
    "gold": {
      "type": "metal",
      "mainMetal": {
        "id": 1,
        "name": "Gold",
        "icon": "/storage/metals/gold-icon.jpg",
        "ref": "GOLD-001"
      },
      "subMetals": [
        {
          "id": 10,
          "name": "Rose Gold",
          "url": "/storage/metal-options/rose-gold.jpg",
          "ref": "RG-001"
        },
        {
          "id": 11,
          "name": "White Gold",
          "url": "/storage/metal-options/white-gold.jpg",
          "ref": "WG-001"
        }
      ]
    }
  },
  "floors": {
    "floor-wooden": [
      {
        "url": "/storage/floors/wooden-floor-preview.jpg",
        "folderPath": "/storage/floors/models/xyz789/",
        "fileName": "",
        "baseSize": 2.0
      }
    ]
  },
  "metadata": {
    "productId": 1,
    "productName": "Artisan Chair",
    "category": "Furniture"
  }
}
```

## Database Requirements

The integration expects the following relationships to exist:
- **Product** has one `threedmodels` (3D model media)
- **Product** has many `metals` (main metal categories)
- **Metal** has many `metalOptions` (sub-metal variations)
- **Concept** has one `threedmodels` (3D model media)
- **Concept** has many `metals` (main metal categories)
- **Floor** has many `floorModels` (floor 3D models)

## 3D Model Support

### Supported Formats
- **OBJ** (.obj) - Primary format
- **MTL** (.mtl) - Material library files
- **Extracted ZIPs** - Automatically handled

### File Structure for Extracted ZIPs
```
storage/products/models/unique-id/
├── model.obj
├── model.mtl
└── textures/
    ├── texture1.jpg
    ├── texture2.jpg
    └── ...
```

## Three.js Integration

### Libraries Loaded (CDN)
- Three.js r132
- OBJLoader
- MTLLoader
- OrbitControls

### Scene Setup
- **Renderer**: WebGL with antialiasing, shadows enabled
- **Camera**: Perspective (60° FOV)
- **Lighting**: Ambient + 2 Directional lights
- **Controls**: OrbitControls with damping

## Troubleshooting

### Model Not Loading
1. Check if the product/concept has a `threedmodels` relationship
2. Verify the file exists in storage
3. Check browser console for errors
4. Ensure storage is publicly accessible

### Materials Not Showing
1. Verify the product/concept has associated `metals`
2. Check that metals have `metalOptions`
3. Ensure `image_url` fields are populated

### Selection Not Working
1. Ensure you're **double-clicking** on the model
2. Check that the model has named materials in the OBJ file
3. Verify materials are not merged during export

## Future Enhancements

Potential improvements:
1. Support for GLTF/GLB format (modern, efficient)
2. Drag-and-drop texture upload
3. Real-time texture preview before applying
4. Save customizations to database
5. Share customized models via URL
6. AR preview on mobile devices
7. Multiple model views (front, side, top)
8. Lighting presets (studio, outdoor, dramatic)

## Performance Considerations

- Models are cached by the browser after first load
- Textures are loaded on-demand when applied
- OrbitControls uses damping for smooth interaction
- Progressive enhancement for low-end devices

## Browser Support

- **Chrome/Edge**: Full support
- **Firefox**: Full support
- **Safari**: Full support (iOS 12+)
- **Mobile**: Touch controls supported

## Security

- All API endpoints are publicly accessible (adjust if needed)
- File paths are validated before serving
- Storage symlink must be created: `php artisan storage:link`

## Related Files

- Models: `app/Models/Product.php`, `app/Models/Concept.php`
- Migrations: Already implemented (ZIP extraction support)
- Controllers: `app/Http/Controllers/ProductController.php`, `app/Http/Controllers/ConceptController.php`

---

**Last Updated**: 2026-01-29
**Version**: 1.0
