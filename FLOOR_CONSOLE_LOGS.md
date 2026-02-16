# Floor System - Console Logging Guide

This document explains all the console logs you'll see when the floor system loads.

## Console Log Flow

### 1. Laravel API Response (in `laravel-viewer.html`)

When the API responds with floor data, you'll see:

```
================================================================================
🏢 FLOORS DATA RECEIVED FROM LARAVEL API
================================================================================
Raw floors data: {
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

📂 Processing floor categories...
Categories found: ["floor-simple", "floor-carpet"]

📁 Processing category: floor-simple (2 models)
   Model 1: {
     name: "Wood Floor 1",
     url: "http://...",
     path: "/storage/uploads/floors/wood1/wood1.obj",
     size: 2.0
   }
   Model 2: {...}

✅ Processed floors summary:
   Categories: 2
   Total models: 5
   Category details: {...}
================================================================================
```

### 2. API Configuration Set (in `api.js`)

```
✅ Floors configuration set: ["floor-simple", "floor-carpet", "floor-tile"]
✅ Floor categories set: ["floor-simple", "floor-carpet", "floor-tile"]
```

### 3. Floor List Initialization (in `floor/floorlist.js`)

When the floor list component initializes:

```
================================================================================
🏢 FLOOR LIST DATA - BEFORE DISPLAY
================================================================================
Raw floorImagesData from API: {
  "floor-simple": [{...}, {...}],
  "floor-carpet": [{...}]
}
Floor categories from API: {
  "floor-simple": {name: "Simple", icon: "..."},
  "floor-carpet": {name: "Carpet", icon: "..."}
}

📊 FLOOR STRUCTURE:

📁 Category: floor-simple
   Name: Simple
   Icon: http://example.com/storage/floors/simple-icon.png
   Models count: 2
   1. Wood Floor 1
      Preview: http://example.com/storage/floors/wood1-preview.jpg
      Path: /storage/uploads/floors/wood1/wood1.obj
      Base Size: 2.0
   2. Wood Floor 2
      Preview: http://example.com/storage/floors/wood2-preview.jpg
      Path: /storage/uploads/floors/wood2/wood2.obj
      Base Size: 2.5

📁 Category: floor-carpet
   Name: Carpet
   Icon: http://example.com/storage/floors/carpet-icon.png
   Models count: 1
   1. Carpet 1
      Preview: http://example.com/storage/floors/carpet1-preview.jpg
      Path: /storage/uploads/floors/carpet1/carpet1.obj
      Base Size: 2.0

================================================================================
```

### 4. Creating Floor Buttons

```
🚀 INITIALIZING FLOOR LIST...

🔨 CREATING FLOOR CATEGORY BUTTONS...
Categories to create: ["floor-simple", "floor-carpet"]

➕ Creating button 1: floor-simple
   Category info: {name: "Simple", icon: "..."}
   ✅ Button created: {
     category: "floor-simple",
     displayName: "Simple",
     icon: "http://example.com/storage/floors/simple-icon.png",
     modelsCount: 2
   }

➕ Creating button 2: floor-carpet
   Category info: {name: "Carpet", icon: "..."}
   ✅ Button created: {
     category: "floor-carpet",
     displayName: "Carpet",
     icon: "http://example.com/storage/floors/carpet-icon.png",
     modelsCount: 1
   }

✅ SUMMARY: Created 2 floor category buttons
Total floor models across all categories: 3
================================================================================

✅ Floor list initialized with dynamic categories
```

### 5. When User Clicks a Category

```
🖼️ Showing 2 floor model images for: floor-simple
```

### 6. When User Clicks a Floor Model Image

```
🏢 Loading floor model: {
  folderPath: "/storage/uploads/floors/wood1/",
  fileName: "wood1.obj",
  baseSize: 2.0
}
✅ Floor model loaded successfully!
```

## Laravel Logs

You can also check Laravel logs at `storage/logs/laravel.log`:

```
[2024-01-29 10:00:00] local.INFO: 🏢 Loading floors from database...
[2024-01-29 10:00:00] local.INFO: Processing floor category: Simple (floor-simple) {"models_count":2,"icon":"floors/icons/simple-icon.png"}
[2024-01-29 10:00:00] local.INFO: Processing floor category: Carpet (floor-carpet) {"models_count":1,"icon":"floors/icons/carpet-icon.png"}
[2024-01-29 10:00:00] local.INFO: ✅ Floors loaded successfully {"categories_count":2,"total_models":3,"categories":["floor-simple","floor-carpet"]}
```

## Debugging Tips

### If You Don't See Floor Data:

1. Check for: `⚠️ No floors data received from API`
   - Your database might be empty
   - Run the seeder or add floors manually

2. Check for: `⚠️ Invalid floors structure from API`
   - API format might be incorrect
   - Check the Laravel logs

3. Check for: `⚠️ No floors configured via API. Using default configuration.`
   - The floor data didn't reach the floorlist.js
   - Check the API response in Network tab

### If Floor Images Don't Show:

Look for: `⚠️ No floor models found for category: floor-xyz`
- The category exists but has no models
- Check the `floor_models` table

### If Floor Model Doesn't Load:

Look for: `❌ Error loading floor model:`
- The OBJ file path might be incorrect
- Check that the file exists in storage
- Check file permissions

## Quick Check Commands

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log | grep 🏢

# Check if floors exist in database
php artisan tinker
>>> Floor::with('floorModels')->get()

# Check storage symlink
ls -la public/storage
```
