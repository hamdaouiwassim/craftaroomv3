<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Concept;
use App\Models\Floor;
use Illuminate\Http\Request;

class ThreeDViewerController extends Controller
{
    /**
     * Get 3D viewer configuration for a product
     */
    public function getProductConfig(Product $product)
    {
        // Get the 3D model
        $model3D = $product->threedmodels;
        
        if (!$model3D) {
            return response()->json([
                'error' => 'No 3D model available for this product'
            ], 404);
        }

        // All models are extracted from ZIP files
        // Remove .zip extension from URL to get the extracted directory path
        $storagePath = str_replace('/storage/', '', $model3D->url);
        $extractedPath = storage_path('app/public/' . preg_replace('/\.zip$/i', '', $storagePath));
        $extractedUrl = preg_replace('/\.zip$/i', '', $model3D->url);
        
        // Find the OBJ file inside the extracted directory
        $objFilePath = null;
        $objFileName = null;
        
        if (is_dir($extractedPath)) {
            // Recursively search for .obj file in the extracted directory
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($extractedPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile() && strtolower($file->getExtension()) === 'obj') {
                    $relativePath = str_replace($extractedPath . '/', '', $file->getPathname());
                    $objFilePath = $extractedUrl . '/' . $relativePath;
                    $objFileName = $file->getFilename();
                    break;
                }
            }
        }
        
        if (!$objFilePath) {
            return response()->json([
                'error' => 'No OBJ file found in the extracted model directory'
            ], 404);
        }

        $config = [
            'mainModel' => [
                'type' => 'extracted',
                'path' => $objFilePath,
                'name' => $objFileName,
                'directory' => $extractedUrl,
                'size' => 1.0,
            ],
            
            // Materials attached to this product
            'components' => $this->getMaterials($product),
            
            // Include available floors
            'floors' => $this->getAvailableFloors(),
            
            // Product metadata
            'metadata' => [
                'productId' => $product->id,
                'productName' => $product->name,
                'category' => $product->category?->name,
            ]
        ];

        return response()->json($config);
    }

    /**
     * Get 3D viewer configuration for a concept
     */
    public function getConceptConfig(Concept $concept)
    {
        $model3D = $concept->threedmodels;
        
        if (!$model3D) {
            return response()->json([
                'error' => 'No 3D model available for this concept'
            ], 404);
        }

        // All models are extracted from ZIP files
        // Remove .zip extension from URL to get the extracted directory path
        $storagePath = str_replace('/storage/', '', $model3D->url);
        $extractedPath = storage_path('app/public/' . preg_replace('/\.zip$/i', '', $storagePath));
        $extractedUrl = preg_replace('/\.zip$/i', '', $model3D->url);
        
        // Find the OBJ file inside the extracted directory
        $objFilePath = null;
        $objFileName = null;
        
        if (is_dir($extractedPath)) {
            // Recursively search for .obj file in the extracted directory
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($extractedPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile() && strtolower($file->getExtension()) === 'obj') {
                    $relativePath = str_replace($extractedPath . '/', '', $file->getPathname());
                    $objFilePath = $extractedUrl . '/' . $relativePath;
                    $objFileName = $file->getFilename();
                    break;
                }
            }
        }
        
        if (!$objFilePath) {
            return response()->json([
                'error' => 'No OBJ file found in the extracted model directory'
            ], 404);
        }

        $config = [
            'mainModel' => [
                'type' => 'extracted',
                'path' => $objFilePath,
                'name' => $objFileName,
                'directory' => $extractedUrl,
                'size' => 1.0,
            ],
            
            'components' => $this->getMaterials($concept),
            'floors' => $this->getAvailableFloors(),
            
            'metadata' => [
                'conceptId' => $concept->id,
                'conceptName' => $concept->name,
                'category' => $concept->category?->name,
            ]
        ];

        return response()->json($config);
    }

    /**
     * Get materials/textures for a product or concept
     * Only loads metals attached to the given model, with their options
     * Format: { "wood": [{url, name}, ...], "fabric": [...] }
     */
    private function getMaterials($model)
    {
        $materials = [];
        
        \Log::info('🎨 Loading components (metals) for: ' . class_basename($model) . ' #' . $model->id);
        
        foreach ($model->metals()->with('metalOptions')->get() as $metal) {
            // Skip metals with no options
            if ($metal->metalOptions->isEmpty()) {
                continue;
            }
            
            $metalKey = strtolower(str_replace(' ', '_', $metal->name));
            
            \Log::info("Processing metal category: {$metal->name} ({$metalKey})", [
                'options_count' => $metal->metalOptions->count(),
                'icon' => $metal->image_url
            ]);
            
            $materials[$metalKey] = $metal->metalOptions->map(function ($option) use ($metal) {
                return [
                    'url' => $option->image_url ?: 'https://via.placeholder.com/200?text=' . urlencode($option->name),
                    'name' => $option->name,
                    '_categoryName' => $metal->name,
                    '_categoryIcon' => $metal->image_url ?: null,
                    '_categoryRef' => $metal->ref,
                ];
            })->toArray();
        }

        \Log::info('✅ Components loaded successfully', [
            'categories_count' => count($materials),
            'total_textures' => collect($materials)->flatten(1)->count()
        ]);

        return $materials;
    }

    /**
     * Get available floor models in original 3D engine format
     * Format: { "floor-category-name": [{url, folderPath, fileName, baseSize}, ...] }
     */
    private function getAvailableFloors()
    {
        $floors = [];
        
        \Log::info('🏢 Loading floors from database...');
        
        Floor::with('floorModels')->get()->each(function ($floor) use (&$floors) {
            // Use floor name as key: "floor-wood", "floor-carpet", etc.
            $floorCategory = 'floor-' . strtolower(str_replace(' ', '-', $floor->name));
            
            \Log::info("Processing floor category: {$floor->name} ({$floorCategory})", [
                'models_count' => $floor->floorModels->count(),
                'icon' => $floor->icon
            ]);
            
            // Map floor models to original format
            $floors[$floorCategory] = $floor->floorModels->map(function ($model) use ($floor) {
                $modelPath = $model->path;
                $storagePath = str_replace('/storage/', '', $modelPath);
                $fullPath = storage_path('app/public/' . $storagePath);
                
                $fileName = '';
                $folderPath = '';
                
                if (is_dir($fullPath)) {
                    // For extracted models, find the OBJ file and derive folder from its actual location
                    $objFile = $this->findObjFileInDirectory($fullPath);
                    if ($objFile) {
                        $fileName = basename($objFile);
                        // Get the directory containing the OBJ file, relative to storage
                        $objDir = dirname($objFile);
                        $relativeDirFromStorage = str_replace(storage_path('app/public/'), '', $objDir);
                        $folderPath = '/storage/' . $relativeDirFromStorage . '/';
                    }
                } else {
                    // For single file models
                    $fileName = basename($modelPath);
                    $folderPath = '/storage/' . dirname($storagePath) . '/';
                }
                
                \Log::info("Floor model path resolved", [
                    'original_path' => $modelPath,
                    'folderPath' => $folderPath,
                    'fileName' => $fileName,
                ]);
                
                return [
                    'url' => $model->image ? asset('storage/' . $model->image) : null,
                    'folderPath' => $folderPath,
                    'fileName' => $fileName,
                    'baseSize' => is_numeric($model->size) ? (float)$model->size : 2.0,
                    '_categoryName' => $floor->name,
                    '_categoryIcon' => $floor->icon ? asset('storage/' . $floor->icon) : null,
                ];
            })->toArray();
        });

        \Log::info('✅ Floors loaded successfully', [
            'categories_count' => count($floors),
            'total_models' => collect($floors)->flatten(1)->count(),
            'categories' => array_keys($floors)
        ]);

        return $floors;
    }
    
    /**
     * Find OBJ file in directory
     */
    private function findObjFileInDirectory($dirPath)
    {
        if (!is_dir($dirPath)) {
            return null;
        }
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dirPath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && strtolower($file->getExtension()) === 'obj') {
                return $file->getPathname();
            }
        }
        
        return null;
    }
}
