// api.js - Configuration API for 3D Model Viewer

/**
 * Global configuration store
 * This holds all the configuration data that can be set via API functions
 */
const CONFIG = {
  mainModel: null,
  components: null,
  componentCategories: null,
  floors: null,
  floorCategories: null,
  currentModelIndex: 0
};

/**
 * Set the main model configuration
 * @param {Object|Array} config - Main model configuration (single object or array of objects)
 * @param {string} config.folderPath - Path to the model folder (e.g., 'objects/tableOffice2/')
 * @param {string} config.fileName - Name of the .obj file (e.g., 'tableOffice2.obj')
 * @param {number} config.desiredSize - Desired size of the model (default: 1.0)
 * 
 * @example Single model:
 * setMainModel({
 *   folderPath: 'objects/myChair/',
 *   fileName: 'chair.obj',
 *   desiredSize: 1.5
 * });
 * 
 * @example Multiple models (rendered one by one):
 * setMainModel([
 *   { folderPath: 'objects/chair/', fileName: 'chair.obj', desiredSize: 1.0 },
 *   { folderPath: 'objects/table/', fileName: 'table.obj', desiredSize: 1.5 }
 * ]);
 */
export function setMainModel(config) {
  // Handle both single object and array
  const models = Array.isArray(config) ? config : [config];
  
  // Validate each model
  for (const model of models) {
    if (!model || !model.folderPath || !model.fileName) {
      throw new Error('setMainModel requires each model to have folderPath and fileName');
    }
  }
  
  // Normalize each model config
  CONFIG.mainModel = models.map(model => ({
    folderPath: model.folderPath,
    fileName: model.fileName,
    desiredSize: model.desiredSize || 1.0
  }));
  
  // Reset to first model
  CONFIG.currentModelIndex = 0;
  
  console.log('✅ Main model configuration set:', CONFIG.mainModel);
  console.log(`   Total models: ${CONFIG.mainModel.length}, starting with index: 0`);
}

/**
 * Get the main model configuration
 * @returns {Array|null} Array of main model configurations or null if not set
 */
export function getMainModel() {
  return CONFIG.mainModel;
}

/**
 * Get the current model by index
 * @returns {Object|null} Current model configuration or null
 */
export function getCurrentModel() {
  if (!CONFIG.mainModel || CONFIG.mainModel.length === 0) return null;
  return CONFIG.mainModel[CONFIG.currentModelIndex];
}

/**
 * Get the current model index
 * @returns {number} Current model index
 */
export function getCurrentModelIndex() {
  return CONFIG.currentModelIndex;
}

/**
 * Set the current model index
 * @param {number} index - Model index to load
 * @returns {boolean} Success status
 */
export function setCurrentModelIndex(index) {
  if (!CONFIG.mainModel || index < 0 || index >= CONFIG.mainModel.length) {
    console.error(`❌ Invalid model index: ${index}. Available: 0-${CONFIG.mainModel?.length - 1 || 0}`);
    return false;
  }
  CONFIG.currentModelIndex = index;
  console.log(`✅ Current model index set to: ${index}`);
  return true;
}

/**
 * Get total number of models
 * @returns {number} Total models count
 */
export function getTotalModels() {
  return CONFIG.mainModel?.length || 0;
}

/**
 * Set the components (textures) configuration
 * @param {Object} componentsData - Components data organized by category
 * @param {Object} categoriesData - Component categories with names and icons (optional)
 * 
 * @example
 * setComponentsConfig({
 *   wood: [
 *     { url: 'textures/oak.jpg', name: 'Oak' },
 *     { url: 'textures/walnut.jpg', name: 'Walnut' }
 *   ],
 *   metal: [
 *     { url: 'textures/steel.jpg', name: 'Steel' }
 *   ]
 * });
 */
export function setComponentsConfig(componentsData, categoriesData = null) {
  if (!componentsData || typeof componentsData !== 'object') {
    throw new Error('setComponentsConfig requires an object with component categories');
  }
  
  CONFIG.components = componentsData;
  if (categoriesData) {
    CONFIG.componentCategories = categoriesData;
  }
  console.log('✅ Components configuration set:', Object.keys(CONFIG.components));
  if (categoriesData) {
    console.log('✅ Component categories set:', Object.keys(CONFIG.componentCategories));
  }
}

/**
 * Get the components configuration
 * @returns {Object|null} Components configuration or null if not set
 */
export function getComponentsConfig() {
  return CONFIG.components;
}

/**
 * Get the component categories configuration
 * @returns {Object|null} Component categories configuration or null if not set
 */
export function getComponentCategories() {
  return CONFIG.componentCategories;
}

/**
 * Set the floors configuration
 * @param {Object} floorsData - Floors data organized by category
 * @param {Object} categoriesData - Floor categories with names and icons (optional)
 * 
 * @example
 * setFloorsConfig({
 *   "floor-simple": [
 *     { 
 *       url: "preview.jpg", 
 *       folderPath: "objects/woodfloor/", 
 *       fileName: "woodfloor.obj", 
 *       baseSize: 5.0 
 *     }
 *   ],
 *   "floor-carpet": [...]
 * });
 */
export function setFloorsConfig(floorsData, categoriesData = null) {
  if (!floorsData || typeof floorsData !== 'object') {
    throw new Error('setFloorsConfig requires an object with floor categories');
  }
  
  CONFIG.floors = floorsData;
  if (categoriesData) {
    CONFIG.floorCategories = categoriesData;
  }
  console.log('✅ Floors configuration set:', Object.keys(CONFIG.floors));
  if (categoriesData) {
    console.log('✅ Floor categories set:', Object.keys(CONFIG.floorCategories));
  }
}

/**
 * Get the floors configuration
 * @returns {Object|null} Floors configuration or null if not set
 */
export function getFloorsConfig() {
  return CONFIG.floors;
}

/**
 * Get the floor categories configuration
 * @returns {Object|null} Floor categories configuration or null if not set
 */
export function getFloorCategories() {
  return CONFIG.floorCategories;
}

/**
 * Get the entire configuration
 * @returns {Object} Complete configuration object
 */
export function getConfig() {
  return CONFIG;
}

/**
 * Reset all configurations
 */
export function resetConfig() {
  CONFIG.mainModel = null;
  CONFIG.components = null;
  CONFIG.componentCategories = null;
  CONFIG.floors = null;
  CONFIG.floorCategories = null;
  CONFIG.currentModelIndex = 0;
  console.log('🔄 Configuration reset');
}

// Make functions available globally for easier access
if (typeof window !== 'undefined') {
  window.ViewerAPI = {
    setMainModel,
    getMainModel,
    getCurrentModel,
    getCurrentModelIndex,
    setCurrentModelIndex,
    getTotalModels,
    setComponentsConfig,
    getComponentsConfig,
    getComponentCategories,
    setFloorsConfig,
    getFloorsConfig,
    getFloorCategories,
    getConfig,
    resetConfig
  };
}