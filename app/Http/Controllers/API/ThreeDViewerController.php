<?php

namespace App\Http\Controllers\API;

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
        try {
            $model3D = $product->threedmodels;
            
            if (!$model3D) {
                return response()->json(['error' => 'No 3D model available for this product'], 404);
            }

            $mainModel = $this->resolveMainModel($model3D);
            
            if (!$mainModel) {
                return response()->json(['error' => 'No OBJ file found in the model directory'], 404);
            }

            return response()->json([
                'mainModel' => $mainModel,
                'components' => $this->getMaterials($product),
                'floors' => $this->getAvailableFloors(),
                'metadata' => [
                    'productId' => $product->id,
                    'productName' => $product->name,
                    'category' => $product->category?->name,
                ]
            ]);
        } catch (\Throwable $e) {
            \Log::error('3D Viewer API error (product)', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get 3D viewer configuration for a concept
     */
    public function getConceptConfig(Concept $concept)
    {
        try {
            $model3D = $concept->threedmodels;
            
            if (!$model3D) {
                return response()->json(['error' => 'No 3D model available for this concept'], 404);
            }

            $mainModel = $this->resolveMainModel($model3D);
            
            if (!$mainModel) {
                return response()->json(['error' => 'No OBJ file found in the model directory'], 404);
            }

            return response()->json([
                'mainModel' => $mainModel,
                'components' => $this->getMaterials($concept),
                'floors' => $this->getAvailableFloors(),
                'metadata' => [
                    'conceptId' => $concept->id,
                    'conceptName' => $concept->name,
                    'category' => $concept->category?->name,
                ]
            ]);
        } catch (\Throwable $e) {
            \Log::error('3D Viewer API error (concept)', [
                'concept_id' => $concept->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Resolve main model path from a 3D model record
     */
    private function resolveMainModel($model3D)
    {
        $storagePath = str_replace('/storage/', '', $model3D->url);
        $extractedPath = storage_path('app/public/' . preg_replace('/\.zip$/i', '', $storagePath));
        $extractedUrl = preg_replace('/\.zip$/i', '', $model3D->url);

        \Log::info('Resolving main model', [
            'url' => $model3D->url,
            'extractedPath' => $extractedPath,
            'exists' => file_exists($extractedPath),
            'is_dir' => is_dir($extractedPath),
        ]);

        if (!is_dir($extractedPath)) {
            \Log::warning('Model directory not found: ' . $extractedPath);
            return null;
        }

        $objFile = $this->findObjFileInDirectory($extractedPath);
        
        if (!$objFile) {
            \Log::warning('No OBJ file found in: ' . $extractedPath);
            return null;
        }

        $relativePath = str_replace($extractedPath . '/', '', $objFile);

        return [
            'type' => 'extracted',
            'path' => $extractedUrl . '/' . $relativePath,
            'name' => basename($objFile),
            'directory' => $extractedUrl,
            'size' => 1.0,
        ];
    }

    /**
     * Get materials/textures for a product or concept
     * Only loads metals attached to the given model, with their options
     * Format: { "wood": [{url, name}, ...], "fabric": [...] }
     */
    private function getMaterials($model)
    {
        try {
            $materials = [];
            
            foreach ($model->metals()->with('metalOptions')->get() as $metal) {
                if ($metal->metalOptions->isEmpty()) {
                    continue;
                }
                
                $metalKey = strtolower(str_replace(' ', '_', $metal->name));
                
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

            return $materials;
        } catch (\Throwable $e) {
            \Log::error('Error loading materials: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get available floor models in original 3D engine format
     * Format: { "floor-category-name": [{url, folderPath, fileName, baseSize}, ...] }
     */
    private function getAvailableFloors()
    {
        try {
            $floors = [];
            
            Floor::with('floorModels')->get()->each(function ($floor) use (&$floors) {
                $floorCategory = 'floor-' . strtolower(str_replace(' ', '-', $floor->name));
                
                $floors[$floorCategory] = $floor->floorModels->map(function ($model) use ($floor) {
                    $modelPath = $model->path;
                    $storagePath = str_replace('/storage/', '', $modelPath);
                    $fullPath = storage_path('app/public/' . $storagePath);
                    
                    $fileName = '';
                    $folderPath = '';
                    
                    if (is_dir($fullPath)) {
                        $objFile = $this->findObjFileInDirectory($fullPath);
                        if ($objFile) {
                            $fileName = basename($objFile);
                            $objDir = dirname($objFile);
                            $relativeDirFromStorage = str_replace(storage_path('app/public/'), '', $objDir);
                            $folderPath = '/storage/' . $relativeDirFromStorage . '/';
                        }
                    } else {
                        $fileName = basename($modelPath);
                        $folderPath = '/storage/' . dirname($storagePath) . '/';
                    }
                    
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

            return $floors;
        } catch (\Throwable $e) {
            \Log::error('Error loading floors: ' . $e->getMessage());
            return [];
        }
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
