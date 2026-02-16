# Quick Start Guide - 3D Viewer Integration

## 🚀 Integration Complete!

The 3D viewer has been successfully integrated into your Laravel application with hierarchical material selection.

## ✅ What's Been Done

1. **Created 3D Viewer Component** - Reusable Blade component with Alpine.js
2. **Built API Controller** - RESTful endpoints for 3D viewer configuration
3. **Updated Product Details** - Replaced iframe with interactive viewer
4. **Updated Concept Details** - Replaced iframe with interactive viewer
5. **Implemented Hierarchical Materials** - Two-level material selection (Main → Sub)
6. **Added Interactive Controls** - Reset, fullscreen, screenshot, and part selection

## 🎯 How to Test

### Step 1: Visit a Product or Concept Page
Navigate to any product or concept details page:
```
http://your-domain/products/1
http://your-domain/concepts/1
```

### Step 2: Interact with the 3D Viewer
- **Rotate**: Click and drag
- **Zoom**: Scroll or pinch (mobile)
- **Pan**: Right-click and drag (or two-finger drag on mobile)

### Step 3: Customize Materials
1. Click the **"Customize Materials"** button at the bottom
2. Browse **main metal categories** (e.g., Gold, Silver, Bronze)
3. Click a category to see **sub-metal options** (e.g., Rose Gold, White Gold)
4. **Double-click** a part on the 3D model to select it
5. Click a **sub-metal texture** to apply it

### Step 4: Use Controls
- **Reset View**: Return camera to starting position
- **Fullscreen**: Toggle fullscreen mode
- **Screenshot**: Download the current view as PNG

## 📋 Verification Checklist

- [ ] 3D model loads and displays correctly
- [ ] Camera controls work (rotate, zoom, pan)
- [ ] "Customize Materials" button appears (if product has metals)
- [ ] Main metal categories are visible
- [ ] Clicking a category shows sub-metals
- [ ] Double-clicking the model selects a part
- [ ] Clicking a sub-metal applies the texture
- [ ] Reset/Fullscreen/Screenshot buttons work
- [ ] Loading progress bar appears during model load
- [ ] Error message shows if model fails to load

## 🔍 API Testing

Test the API endpoints directly:

```bash
# Get product configuration
curl http://your-domain/api/3d-viewer/product/1

# Get concept configuration
curl http://your-domain/api/3d-viewer/concept/1
```

Expected response structure:
```json
{
  "mainModel": {
    "type": "extracted",
    "path": "/storage/products/models/...",
    "name": "model.obj",
    "size": 1.0
  },
  "components": {
    "gold": {
      "type": "metal",
      "mainMetal": {
        "id": 1,
        "name": "Gold",
        "icon": "/storage/metals/gold.jpg",
        "ref": "GOLD-001"
      },
      "subMetals": [
        {
          "id": 10,
          "name": "Rose Gold",
          "url": "/storage/metal-options/rose-gold.jpg",
          "ref": "RG-001"
        }
      ]
    }
  },
  "floors": { ... },
  "metadata": { ... }
}
```

## 🐛 Troubleshooting

### Model Not Loading
**Problem**: 3D model doesn't appear, error message shows  
**Solution**:
1. Check if the product/concept has a `threedmodels` relationship:
   ```php
   $product->threedmodels // Should not be null
   ```
2. Verify the file exists in storage:
   ```bash
   ls -la storage/app/public/products/models/
   ```
3. Check browser console for specific errors

### Materials Not Showing
**Problem**: "Customize Materials" button doesn't appear  
**Solution**:
1. Check if the product/concept has metals:
   ```php
   $product->metals()->count() // Should be > 0
   ```
2. Verify metals have metalOptions:
   ```php
   $product->metals()->first()->metalOptions()->count() // Should be > 0
   ```
3. Check API response includes "components"

### Selection Not Working
**Problem**: Can't select parts of the model  
**Solution**:
1. Make sure you're **double-clicking**, not single-clicking
2. Check that the OBJ file has named materials
3. Try clicking different parts of the model
4. Check console for any JavaScript errors

### Textures Not Applying
**Problem**: Clicking sub-metal doesn't change the model  
**Solution**:
1. Select a part first by double-clicking
2. Ensure texture URLs in database are valid
3. Check browser console for texture loading errors
4. Verify texture files exist in storage

## 📁 Key Files

### Component
- `resources/views/components/3d-viewer.blade.php` - Main component

### API
- `app/Http/Controllers/Api/ThreeDViewerController.php` - API controller
- `routes/api.php` - API routes

### Views
- `resources/views/product-details.blade.php` - Product page
- `resources/views/concept-details.blade.php` - Concept page

### Documentation
- `docs/3D_VIEWER_INTEGRATION.md` - Full technical documentation
- `INTEGRATION_SUMMARY.md` - Implementation summary
- `QUICK_START.md` - This file

## 🎨 Customization

### Change Viewer Height
```blade
<x-3d-viewer 
    model-type="product" 
    :model-id="$product->id"
    height="800px"  <!-- Custom height -->
/>
```

### Disable Controls
```blade
<x-3d-viewer 
    model-type="product" 
    :model-id="$product->id"
    :show-controls="false"
/>
```

### Disable Material Customization
```blade
<x-3d-viewer 
    model-type="product" 
    :model-id="$product->id"
    :enable-customization="false"
/>
```

## 🔄 Next Steps

1. **Test with Real Data**: Try with products/concepts that have:
   - 3D models (OBJ files)
   - Associated metals
   - Metal options with textures

2. **Add Materials**: Ensure your products have:
   ```sql
   -- Check products with models and metals
   SELECT p.id, p.name, 
          COUNT(DISTINCT m.id) as metal_count,
          COUNT(DISTINCT mo.id) as option_count
   FROM products p
   LEFT JOIN product_metal pm ON p.id = pm.product_id
   LEFT JOIN metals m ON pm.metal_id = m.id
   LEFT JOIN metal_options mo ON m.id = mo.metal_id
   WHERE p.threedmodel_id IS NOT NULL
   GROUP BY p.id, p.name;
   ```

3. **Optimize Models**: If models are large:
   - Reduce polygon count in 3D modeling software
   - Compress texture images
   - Consider using GLTF format in future

4. **User Testing**: Get feedback on:
   - Material selection UX
   - Loading times
   - Mobile experience
   - Visual quality

5. **Future Enhancements**:
   - Save customizations to database
   - Share customized models via URL
   - Add AR preview on mobile
   - Implement material presets

## 📞 Support

If you encounter issues:
1. Check browser console for errors
2. Verify API responses in Network tab
3. Review the full documentation in `docs/3D_VIEWER_INTEGRATION.md`
4. Check database relationships are correct

## 🎉 Success!

You now have a fully integrated, interactive 3D viewer with hierarchical material selection. Users can:
- View 3D models in real-time
- Select model parts by double-clicking
- Browse materials in a two-level hierarchy
- Apply textures to customize products
- Take screenshots of their customizations

**Enjoy your new 3D viewer! 🚀**

---

**Questions?** Refer to the full documentation in `docs/3D_VIEWER_INTEGRATION.md`
