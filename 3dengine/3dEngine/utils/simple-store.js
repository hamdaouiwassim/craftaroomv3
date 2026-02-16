/**
 * SIMPLE Customization Save/Load System
 * WITH MODEL INFO - Now stores which model was used!
 */

import { getMainModel, getCurrentModelIndex } from '../api.js';

export class SimpleCustomizationStore {
  
  /**
   * Capture current customization state
   */
  static captureState(mainModelParent, mainModelGroup, floorModelGroup, scene) {
    // Get current model info from API
    const currentModels = getMainModel();
    const currentIndex = getCurrentModelIndex();
    
    const state = {
      // ✨ Store which model(s) are available and current index
      models: currentModels ? currentModels.map(model => ({
        folderPath: model?.folderPath || null,
        fileName: model?.fileName || null,
        desiredSize: model?.desiredSize || 1.0
      })) : [],
      currentModelIndex: currentIndex,
      
      // Model transformations (for parent group)
      model: {
        rotation: mainModelParent ? {
          x: mainModelParent.rotation.x,
          y: mainModelParent.rotation.y,
          z: mainModelParent.rotation.z
        } : { x: 0, y: 0, z: 0 },
        position: mainModelParent ? {
          x: mainModelParent.position.x,
          y: mainModelParent.position.y,
          z: mainModelParent.position.z
        } : { x: 0, y: 0, z: 0 }
      },
      
      // Floor info
      floor: {
        active: !!floorModelGroup,
        folderPath: window.lastSelectedFloor?.folderPath || null,
        fileName: window.lastSelectedFloor?.fileName || null,
        baseSize: window.lastSelectedFloor?.baseSize || 1,
        scale: window.currentScale || 1
      },
      
      // Materials/textures
      materials: [],
      
      // Scene background
      backgroundColor: scene ? '#' + scene.background.getHexString() : '#eeeeee',
      
      // Metadata
      savedAt: new Date().toISOString(),
      version: '2.0' // Version for future compatibility
    };
    
    // Capture all materials
    if (mainModelGroup) {
      const materialMap = new Map();
      
      mainModelGroup.traverse((child) => {
        if (child.isMesh && child.material) {
          const matName = child.material.name;
          
          if (!materialMap.has(matName)) {
            const materialData = {
              name: matName,
              color: '#' + child.material.color.getHexString(),
              texture: null
            };
            
            // Get texture path if exists
            if (child.material.map && child.material.map.image) {
              const src = child.material.map.image.currentSrc || child.material.map.image.src;
              if (src) {
                try {
                  const url = new URL(src);
                  materialData.texture = url.pathname;
                } catch (e) {
                  materialData.texture = src;
                }
              }
            }
            
            materialMap.set(matName, materialData);
            state.materials.push(materialData);
          }
        }
      });
    }
    
    console.log('📦 State captured:', state);
    return state;
  }
  
  /**
   * Export state as JSON string
   */
  static toJSON(state) {
    return JSON.stringify(state, null, 2);
  }
  
  /**
   * Import state from JSON string
   */
  static fromJSON(jsonString) {
    try {
      return JSON.parse(jsonString);
    } catch (e) {
      console.error("Failed to parse JSON:", e);
      return null;
    }
  }
  
  /**
   * Save to file (download)
   */
  static saveToFile(state, filename = 'customization.json') {
    const json = this.toJSON(state);
    const blob = new Blob([json], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.click();
    URL.revokeObjectURL(url);
    console.log('💾 Saved to file:', filename);
  }
  
  /**
   * Load from file
   */
  static loadFromFile(file) {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.onload = (e) => {
        const state = this.fromJSON(e.target.result);
        if (state) {
          resolve(state);
        } else {
          reject(new Error('Invalid JSON file'));
        }
      };
      reader.onerror = () => reject(new Error('Failed to read file'));
      reader.readAsText(file);
    });
  }
  
  /**
   * Check if we need to reload the model
   */
  static needsModelReload(savedModel, currentModel) {
    if (!savedModel || !savedModel.folderPath || !savedModel.fileName) {
      console.log('⚠️ No model info in saved state - will use current model');
      return false;
    }
    
    if (!currentModel) {
      console.log('🔄 No current model - needs reload');
      return true;
    }
    
    const different = 
      savedModel.folderPath !== currentModel.folderPath ||
      savedModel.fileName !== currentModel.fileName;
    
    if (different) {
      console.log('🔄 Model mismatch - needs reload');
      console.log('   Saved:', savedModel.folderPath + savedModel.fileName);
      console.log('   Current:', currentModel.folderPath + currentModel.fileName);
    } else {
      console.log('✅ Same model - no reload needed');
    }
    
    return different;
  }
  
  /**
   * Restore customization from state
   * WITH SMART MODEL RELOAD
   */
  static async restore(state, { loadFloorModel, mainModelGroup, mainModelParent, scene }) {
    try {
      console.log('🔄 Starting restore process...');
      
      // ========== STEP 1: CHECK IF WE NEED TO RELOAD MODEL ==========
      const currentModels = getMainModel();
      const currentIndex = getCurrentModelIndex();
      const savedModels = state.models || [];
      const savedIndex = state.currentModelIndex || 0;
      
      // Check if we need to reload: different models or different index
      const needsReload = !currentModels || 
                          currentModels.length !== savedModels.length ||
                          currentIndex !== savedIndex ||
                          JSON.stringify(currentModels.map(m => ({ f: m.folderPath, n: m.fileName }))) !== 
                          JSON.stringify(savedModels.map(m => ({ f: m.folderPath, n: m.fileName })));
      
      if (needsReload && savedModels.length > 0) {
        console.log(`📦 Loading model at index ${savedIndex}...`);
        
        // ========== CLEANUP OLD MODEL FIRST ==========
        console.log('🧹 Cleaning up old model(s)...');
        
        // Dispose old model geometry and materials
        if (mainModelGroup) {
          mainModelGroup.traverse((child) => {
            if (child.isMesh) {
              if (child.geometry) child.geometry.dispose();
              if (child.material) {
                if (Array.isArray(child.material)) {
                  child.material.forEach(mat => {
                    if (mat.map) mat.map.dispose();
                    mat.dispose();
                  });
                } else {
                  if (child.material.map) child.material.map.dispose();
                  child.material.dispose();
                }
              }
            }
          });
        }
        
        // Remove from scene
        if (mainModelParent && mainModelGroup) {
          mainModelParent.remove(mainModelGroup);
          console.log('✅ Old model removed from scene');
        }
        
        // Clear references
        mainModelGroup = null;
        window.mainModelGroup = null;
        

           // ========== CLEANUP OLD FLOOR ==========
        console.log('🧹 Cleaning up old floor...');
        const { removeFloorModel } = await import('../main.js');
        removeFloorModel();
        console.log('✅ Old floor removed');
        
        // Import setMainModel and setCurrentModelIndex to configure the new model
        const { setMainModel, setCurrentModelIndex } = await import('../api.js');
        
        // Set the model configuration (array or single)
        setMainModel(savedModels.length === 1 ? savedModels[0] : savedModels);
        
        // Set the saved index
        setCurrentModelIndex(savedIndex);
        
        // Reload the model at the saved index
        if (window.loadMainModel) {
          console.log(`📥 Loading model at index ${savedIndex}...`);
          await window.loadMainModel();
          console.log(`✅ Model loaded successfully at index ${savedIndex}!`);
          
          // Wait a bit for model to fully initialize
          await new Promise(resolve => setTimeout(resolve, 500));
          
          // Update references after model reload
          mainModelGroup = window.mainModelGroup;
          mainModelParent = window.mainModelParent;
        } else {
          console.error('❌ loadMainModel function not available!');
          return false;
        }
      }
      
      // ========== STEP 2: RESTORE FLOOR ==========
      if (state.floor && state.floor.active && state.floor.folderPath) {
        console.log('🏠 Restoring floor...');
        await loadFloorModel({
          folderPath: state.floor.folderPath,
          fileName: state.floor.fileName,
          desiredSize: state.floor.baseSize * state.floor.scale
        });
        window.currentScale = state.floor.scale;
        console.log('✅ Floor restored!');
      }
      
      // ========== STEP 3: RESTORE MODEL TRANSFORMATIONS ==========
      if (mainModelParent && state.model) {
        console.log('🔄 Restoring transformations...');
        mainModelParent.rotation.set(
          state.model.rotation.x,
          state.model.rotation.y,
          state.model.rotation.z
        );
        mainModelParent.position.set(
          state.model.position.x,
          state.model.position.y,
          state.model.position.z
        );
        console.log('✅ Transformations restored!');
      }
      
      // ========== STEP 4: RESTORE MATERIALS ==========
      if (mainModelGroup && state.materials) {
        console.log('🎨 Restoring materials...');
        const textureLoader = new THREE.TextureLoader();
        
        for (const matData of state.materials) {
          mainModelGroup.traverse((child) => {
            if (child.isMesh && child.material.name === matData.name) {
              // Restore color
              child.material.color.set(matData.color);
              
              // Restore texture if exists
              if (matData.texture) {
                textureLoader.load(matData.texture, (texture) => {
                  texture.encoding = THREE.sRGBEncoding;
                  texture.wrapS = THREE.RepeatWrapping;
                  texture.wrapT = THREE.RepeatWrapping;
                  child.material.map = texture;
                  child.material.needsUpdate = true;
                });
              }
              
              child.material.needsUpdate = true;
            }
          });
        }
        console.log('✅ Materials restored!');
      }
      
      // ========== STEP 5: RESTORE BACKGROUND ==========
      if (scene && state.backgroundColor) {
        console.log('🎨 Restoring background...');
        scene.background = new THREE.Color(state.backgroundColor);
        console.log('✅ Background restored!');
      }
      
      console.log("✅ Customization fully restored!");
      return true;
    } catch (e) {
      console.error("❌ Failed to restore:", e);
      return false;
    }
  }
}