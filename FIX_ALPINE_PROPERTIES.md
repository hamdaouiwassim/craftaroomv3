# Fix: Alpine.js Component Properties Not Defined

## Issue
After the initial fix, you encountered new errors:
```
Uncaught ReferenceError: showControls is not defined
Uncaught ReferenceError: enableCustomization is not defined
```

## Root Cause
The component was receiving a `config` object with properties like:
```javascript
{
  modelType: 'product',
  modelId: 1,
  showControls: true,
  enableCustomization: true
}
```

But these properties from the config were **not being extracted** and added to the component's data, so when the template tried to use them in `x-show` directives, they were undefined.

## What Was Fixed

### Before (Broken)
```javascript
Alpine.data('threeDViewer', (config) => ({
    loading: true,
    loadingProgress: 0,
    // ... other properties
    
    // ❌ showControls and enableCustomization NOT defined
    // ❌ Template uses showControls → Error!
}))
```

### After (Fixed)
```javascript
Alpine.data('threeDViewer', (config) => ({
    // ✅ Extract config properties
    modelType: config.modelType || 'product',
    modelId: config.modelId || null,
    showControls: config.showControls !== false,
    enableCustomization: config.enableCustomization !== false,
    
    // State properties
    loading: true,
    loadingProgress: 0,
    // ... rest of properties
}))
```

## Changes Made

### 1. Added Config Properties to Component Data
**File**: `resources/views/components/3d-viewer.blade.php`

Added these properties at the top of the component's return object:
```javascript
// Config properties
modelType: config.modelType || 'product',
modelId: config.modelId || null,
showControls: config.showControls !== false,  // Default true
enableCustomization: config.enableCustomization !== false,  // Default true
```

**Why `!== false`?**
- If the property is not provided, it defaults to `true`
- If explicitly set to `false`, it will be `false`
- This handles both `undefined` and explicit `false` values

### 2. Updated API Call to Use Component Properties
Changed from accessing `config` directly:
```javascript
// Before
const response = await fetch(`/api/3d-viewer/${config.modelType}/${config.modelId}`);

// After
const response = await fetch(`/api/3d-viewer/${this.modelType}/${this.modelId}`);
```

### 3. Added Initialization Logging
Added console logging to help debug:
```javascript
console.log('3D Viewer initializing...', {
    modelType: this.modelType,
    modelId: this.modelId,
    showControls: this.showControls,
    enableCustomization: this.enableCustomization
});
```

## How It Works Now

### Component Usage in Blade
```blade
<x-3d-viewer 
    model-type="product" 
    :model-id="$product->id"
    height="600px"
    :enable-customization="true"
    :show-controls="true"
/>
```

### Alpine.js x-data Attribute
```html
<div x-data="threeDViewer({
    modelType: 'product',
    modelId: 1,
    showControls: true,
    enableCustomization: true
})">
```

### Component Initialization Flow
```
1. Blade renders: x-data="threeDViewer({...})"
2. Alpine calls: threeDViewer(config)
3. Component extracts config properties:
   - this.modelType = config.modelType
   - this.modelId = config.modelId
   - this.showControls = config.showControls !== false
   - this.enableCustomization = config.enableCustomization !== false
4. Template can now use these properties:
   - x-show="showControls && !loading"
   - x-show="enableCustomization && hasMaterials"
```

## Verification

### 1. Clear Caches
```bash
php artisan view:clear
```

### 2. Hard Refresh Browser
- Chrome/Edge: `Ctrl+Shift+R` (Windows) or `Cmd+Shift+R` (Mac)
- Firefox: `Ctrl+F5` (Windows) or `Cmd+Shift+R` (Mac)

### 3. Check Console
You should see:
```javascript
3D Viewer initializing... {
    modelType: "product",
    modelId: 1,
    showControls: true,
    enableCustomization: true
}
```

### 4. Verify No Errors
These errors should be **GONE**:
- ✅ No "showControls is not defined"
- ✅ No "enableCustomization is not defined"
- ✅ No "modelType is not defined"
- ✅ No "modelId is not defined"

### 5. Test Conditional Rendering
- **Controls should appear** (if showControls=true)
  - Reset view button ✅
  - Fullscreen button ✅
  - Screenshot button ✅

- **Material customization should work** (if enableCustomization=true)
  - "Customize Materials" button appears ✅
  - Can browse metal categories ✅
  - Can select and apply textures ✅

## Component Properties Reference

### Required Properties (from config)
```javascript
modelType: string       // 'product' or 'concept'
modelId: number         // ID of the product/concept
```

### Optional Properties (from config)
```javascript
showControls: boolean           // Default: true
enableCustomization: boolean    // Default: true
```

### State Properties (internal)
```javascript
loading: boolean               // Loading state
loadingProgress: number        // 0-100
error: boolean                 // Error state
errorMessage: string           // Error description
loadingMessage: string         // Current loading step

// 3D Engine
scene: THREE.Scene             // Three.js scene
camera: THREE.Camera           // Three.js camera
renderer: THREE.Renderer       // Three.js renderer
controls: THREE.OrbitControls  // Camera controls
mainModel: THREE.Group         // Loaded 3D model

// Materials
materials: object              // Material hierarchy
selectedMainMetal: string      // Currently selected category
selectedPart: string           // Currently selected model part
appliedTexture: string         // URL of applied texture
showMaterialSelector: boolean  // Panel visibility
hasMaterials: boolean          // Has materials available
```

## Testing Different Configurations

### Full Features
```blade
<x-3d-viewer 
    model-type="product" 
    :model-id="1"
    :enable-customization="true"
    :show-controls="true"
/>
```

### View Only (No Controls)
```blade
<x-3d-viewer 
    model-type="product" 
    :model-id="1"
    :enable-customization="false"
    :show-controls="false"
/>
```

### Custom Height
```blade
<x-3d-viewer 
    model-type="concept" 
    :model-id="5"
    height="800px"
/>
```

## Success Indicators ✅

After this fix, you should see:
- ✅ No console errors about undefined properties
- ✅ Component initializes successfully
- ✅ Loading progress bar displays
- ✅ 3D model loads and renders
- ✅ Controls appear (if enabled)
- ✅ Material customization works (if enabled)
- ✅ All interactive features functional

## If You Still Have Issues

### Check Component Initialization Log
In browser console, you should see:
```
3D Viewer initializing... { modelType: "product", modelId: 1, ... }
```

If you don't see this, the component isn't initializing.

### Check Alpine.js is Working
```javascript
console.log(Alpine);  // Should show Alpine object
```

### Check Config is Passed Correctly
In `product-details.blade.php`, verify:
```blade
<x-3d-viewer 
    model-type="product"          <!-- Lowercase with hyphen -->
    :model-id="$product->id"      <!-- Colon prefix for PHP expression -->
    :show-controls="true"          <!-- Colon prefix for boolean -->
    :enable-customization="true"   <!-- Colon prefix for boolean -->
/>
```

**Important**: Use `:model-id` (with colon) for PHP expressions, not `model-id` without colon.

---

**Status**: ✅ Fixed  
**Date**: 2026-01-29  
**File Modified**: `resources/views/components/3d-viewer.blade.php`
