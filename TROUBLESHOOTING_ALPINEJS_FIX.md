# Alpine.js Error Fix

## Problem
You were encountering these errors:
```
Uncaught ReferenceError: threeDViewer is not defined
Uncaught ReferenceError: init is not defined
Uncaught ReferenceError: loading is not defined
Uncaught ReferenceError: loadingMessage is not defined
Uncaught ReferenceError: loadingProgress is not defined
```

## Root Cause
The Alpine.js component (`threeDViewer`) was being defined in a script that was pushed to a `@stack('scripts')`, but the main layout didn't have `@stack('scripts')` to render those scripts. This meant:

1. The Alpine.js component definition was never loaded
2. Alpine.js tried to use `x-data="threeDViewer(...)"` before the component was defined
3. All component properties were undefined

## What Was Fixed

### 1. Added Scripts Stack to Main Layout
**File**: `resources/views/components/main-layout.blade.php`

Added before `</body>`:
```blade
<!-- Scripts Stack -->
@stack('scripts')
```

This ensures any scripts pushed via `@push('scripts')` are actually rendered.

### 2. Improved Component Script Loading
**File**: `resources/views/components/3d-viewer.blade.php`

- Moved Alpine component definition outside of the `@push` to load immediately
- Added `waitForThreeJS()` method to ensure Three.js libraries are loaded before initialization
- Improved error handling for library loading failures

### 3. Cleared Caches
```bash
php artisan view:clear
php artisan cache:clear
```

## Verification Steps

### 1. Check the Browser Console
After refreshing the page, you should **NOT** see these errors anymore:
- ✅ No "threeDViewer is not defined"
- ✅ No "init is not defined"
- ✅ No "loading is not defined"

### 2. Check 3D Viewer Loads
You should see:
- ✅ Loading progress bar appears
- ✅ "Loading 3D libraries..." message
- ✅ "Loading configuration..." message
- ✅ "Initializing 3D scene..." message
- ✅ 3D model appears and is interactive

### 3. Check Alpine.js Component is Registered
In browser console, run:
```javascript
console.log(typeof Alpine);  // Should be 'object'
console.log(typeof THREE);   // Should be 'object'
```

## How the Fix Works

### Before (Broken)
```
1. Browser loads page with Alpine.js
2. Alpine.js sees x-data="threeDViewer(...)"
3. Alpine.js tries to call threeDViewer() → ERROR: not defined
4. Scripts in @push('scripts') never rendered (no @stack)
5. Component definition never executed
```

### After (Fixed)
```
1. Browser loads page with Alpine.js
2. @stack('scripts') renders Three.js libraries
3. Alpine component definition script executes
4. threeDViewer() is registered with Alpine
5. Alpine.js sees x-data="threeDViewer(...)"
6. Alpine.js successfully initializes component
7. Component waits for Three.js to fully load
8. 3D scene initializes successfully
```

## Additional Improvements Made

### 1. Library Loading Check
The component now waits for Three.js libraries to load:
```javascript
async waitForThreeJS() {
    // Waits up to 5 seconds for THREE to be available
    // Then waits for OBJLoader, MTLLoader, OrbitControls
    // Throws error if libraries don't load
}
```

### 2. Better Loading Progress
```javascript
'Loading 3D libraries...'    // 5%
'Loading configuration...'    // 15%
'Initializing 3D scene...'   // 35%
'Loading 3D model...'        // 55%
'Complete'                   // 100%
```

### 3. Error Handling
If Three.js fails to load after 5 seconds:
```
Error: "Three.js library failed to load"
```
With retry button for user to try again.

## If You Still Have Issues

### Clear Everything
```bash
# Clear all caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear

# Clear browser cache
# Chrome: Ctrl+Shift+Delete (or Cmd+Shift+Delete on Mac)
# Select "Cached images and files"
```

### Check Scripts Stack
Verify `resources/views/components/main-layout.blade.php` has:
```blade
@stack('scripts')
```
Before the closing `</body>` tag.

### Check Alpine.js is Loaded
In your layout, verify Alpine.js is loaded via Vite:
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

And in `resources/js/app.js`:
```javascript
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
```

### Check Network Tab
Open browser DevTools → Network tab:
- ✅ `three.min.js` should load (200 status)
- ✅ `OBJLoader.js` should load (200 status)
- ✅ `MTLLoader.js` should load (200 status)
- ✅ `OrbitControls.js` should load (200 status)

If any fail to load (404 or CORS error), the CDN might be blocked.

### Alternative: Self-Host Three.js
If CDN is blocked, download and host Three.js locally:
```bash
npm install three@0.132.2
```

Then update the component to use local files instead of CDN.

## Testing the Fix

1. **Visit a product page**: `http://your-domain/products/1`
2. **Open browser console**: F12 or Right-click → Inspect → Console
3. **Check for errors**: Should be none related to Alpine.js or threeDViewer
4. **Watch the loading**: Should see progress bar and messages
5. **Interact with model**: Should be able to rotate, zoom, pan

## Success Indicators ✅

- ✅ No console errors about undefined variables
- ✅ Loading progress bar displays
- ✅ 3D model loads and renders
- ✅ Camera controls work (rotate, zoom, pan)
- ✅ "Customize Materials" button appears (if product has materials)
- ✅ Can select parts by double-clicking
- ✅ Can apply materials to selected parts

## Common Issues After Fix

### Issue: "Three.js library failed to load"
**Cause**: CDN is slow or blocked  
**Solution**: Wait and retry, or self-host Three.js

### Issue: Model appears but is black/no texture
**Cause**: Lighting or materials not loaded  
**Solution**: Check OBJ/MTL files are valid, check model path in API response

### Issue: Can't select parts
**Cause**: OBJ file has no named materials  
**Solution**: Export OBJ with material names from 3D software

---

**Status**: ✅ Fixed  
**Date**: 2026-01-29  
**Files Modified**: 2 (`main-layout.blade.php`, `3d-viewer.blade.php`)
