# Model Switching Guide

The 3D engine supports loading **multiple models** and switching between them dynamically!

## 🎯 Overview

Instead of loading all models at once, models are loaded **one by one** by index:
- **Index 0**: First model (loaded by default)
- **Index 1**: Second model
- **Index 2**: Third model
- And so on...

Use `switchModel(index)` to change between models.

## 🚀 Quick Start

### Step 1: Configure Multiple Models

```javascript
// config.js
window.VIEWER_CONFIG = {
  useAPI: false,
  mainModel: [
    { folderPath: 'objects/chair/', fileName: 'chair.obj', desiredSize: 1.0 },
    { folderPath: 'objects/table/', fileName: 'table.obj', desiredSize: 1.5 },
    { folderPath: 'objects/lamp/', fileName: 'lamp.obj', desiredSize: 0.8 }
  ]
};
```

### Step 2: Open index.html

The first model (index 0 - chair) will load automatically.

### Step 3: Switch Models

Open browser console and use:

```javascript
// Switch to second model (table)
switchModel(1);

// Switch to third model (lamp)
switchModel(2);

// Switch back to first model (chair)
switchModel(0);

// Check current index
getCurrentModelIndex(); // Returns: 0, 1, 2, etc.

// Get total models available
getTotalModels(); // Returns: 3
```

## 📋 Available Functions

### `switchModel(index)`
Load and display a model by its index.

```javascript
switchModel(0); // Load first model
switchModel(1); // Load second model
switchModel(2); // Load third model
```

**Returns:** `true` if successful, `false` if invalid index

### `getCurrentModelIndex()`
Get the currently loaded model index.

```javascript
const current = getCurrentModelIndex();
console.log(`Current model: ${current}`); // e.g., "Current model: 0"
```

**Returns:** Number (0-based index)

### `getTotalModels()`
Get total number of available models.

```javascript
const total = getTotalModels();
console.log(`Total models: ${total}`); // e.g., "Total models: 3"
```

**Returns:** Number

## 🌐 API Mode (Database)

Your Laravel API can return multiple models:

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
    },
    {
      "path": "/storage/uploads/models/lamp/lamp.obj",
      "name": "lamp.obj",
      "size": 0.8
    }
  ]
}
```

The first model will load automatically. Use `switchModel()` to change.

## 🎨 UI Integration Example

### Create Model Switcher Buttons

```html
<div id="model-switcher">
  <button onclick="switchModel(0)">Chair</button>
  <button onclick="switchModel(1)">Table</button>
  <button onclick="switchModel(2)">Lamp</button>
</div>
```

### Dynamic Model Switcher

```javascript
// Create buttons dynamically based on available models
const total = getTotalModels();
const container = document.getElementById('model-switcher');

for (let i = 0; i < total; i++) {
  const btn = document.createElement('button');
  btn.textContent = `Model ${i + 1}`;
  btn.onclick = () => switchModel(i);
  container.appendChild(btn);
}
```

### Next/Previous Buttons

```javascript
function nextModel() {
  const current = getCurrentModelIndex();
  const total = getTotalModels();
  const next = (current + 1) % total; // Loop back to 0
  switchModel(next);
}

function previousModel() {
  const current = getCurrentModelIndex();
  const total = getTotalModels();
  const prev = (current - 1 + total) % total; // Loop back to last
  switchModel(prev);
}
```

```html
<button onclick="previousModel()">← Previous</button>
<button onclick="nextModel()">Next →</button>
```

## 💡 Use Cases

### 1. Product Variations
Show different versions of the same product:
- Small, Medium, Large sizes
- Different styles or colors
- Before/After versions

### 2. Product Catalog
Browse through multiple products:
- Chair collection
- Table options
- Furniture series

### 3. Product Comparison
Switch between competing products:
- Compare features
- Compare sizes
- Compare styles

### 4. Configuration Steps
Step-by-step product customization:
- Step 1: Base model
- Step 2: With accessories
- Step 3: Final configuration

## ⚙️ How It Works

1. **Configuration**: All models are stored in an array
2. **Index Tracking**: Current index is tracked in `CONFIG.currentModelIndex`
3. **Loading**: Only the current model (by index) is loaded
4. **Switching**: 
   - Current model is removed and disposed
   - New model is loaded at the new index
   - Materials and textures are reset

## 🔧 Advanced Usage

### Auto-Rotate Through Models

```javascript
let autoRotateInterval;

function startAutoRotate() {
  autoRotateInterval = setInterval(() => {
    const current = getCurrentModelIndex();
    const total = getTotalModels();
    const next = (current + 1) % total;
    switchModel(next);
  }, 5000); // Every 5 seconds
}

function stopAutoRotate() {
  clearInterval(autoRotateInterval);
}

// Start auto-rotation
startAutoRotate();

// Stop when user interacts
document.addEventListener('click', stopAutoRotate);
```

### Model Preloading (Coming Soon)

```javascript
// Future feature: preload next model for faster switching
preloadModel(getCurrentModelIndex() + 1);
```

## 📐 Model Properties

Each model in the array has:

| Property | Type | Required | Description |
|----------|------|----------|-------------|
| `folderPath` | string | Yes | Path to model folder (e.g., 'objects/chair/') |
| `fileName` | string | Yes | OBJ filename (e.g., 'chair.obj') |
| `desiredSize` | number | No | Scale factor (default: 1.0) |

**Note:** Position is not used - each model is rendered in the same location.

## 🐛 Troubleshooting

### Models Not Switching
- Check console for errors
- Verify index is valid: `0` to `getTotalModels() - 1`
- Ensure models are configured correctly

### Wrong Model Showing
- Check current index: `getCurrentModelIndex()`
- Verify configuration array order
- Check if models loaded successfully

### Performance Issues
- Switching cleans up previous model automatically
- Each model loads fresh with its own materials
- Only one model is in memory at a time

## ✅ Best Practices

1. **Start with First Model**: Always default to index 0
2. **Validate Index**: Check bounds before calling `switchModel()`
3. **Show Feedback**: Display loading state during model switch
4. **Name Clearly**: Use descriptive folder names for each model
5. **Optimize Models**: Keep models lightweight for faster switching

## 🎉 Example: Complete Product Viewer

```html
<!DOCTYPE html>
<html>
<head>
  <title>Product Viewer</title>
</head>
<body>
  <div id="viewer-container">
    <!-- 3D viewer here -->
  </div>
  
  <div id="model-selector">
    <h3>Select Model:</h3>
    <div id="model-buttons"></div>
    <div id="model-info"></div>
  </div>
  
  <script>
    // Wait for viewer to load
    window.addEventListener('load', () => {
      const total = getTotalModels();
      const buttonsContainer = document.getElementById('model-buttons');
      const infoContainer = document.getElementById('model-info');
      
      // Model names (from your config)
      const modelNames = ['Chair', 'Table', 'Lamp'];
      
      // Create buttons
      for (let i = 0; i < total; i++) {
        const btn = document.createElement('button');
        btn.textContent = modelNames[i] || `Model ${i + 1}`;
        btn.onclick = () => {
          switchModel(i);
          updateInfo();
        };
        buttonsContainer.appendChild(btn);
      }
      
      // Update info display
      function updateInfo() {
        const current = getCurrentModelIndex();
        infoContainer.textContent = `Viewing: ${modelNames[current]} (${current + 1}/${total})`;
      }
      
      updateInfo();
    });
  </script>
</body>
</html>
```

---

For more information, see [README_DYNAMIC.md](README_DYNAMIC.md)
