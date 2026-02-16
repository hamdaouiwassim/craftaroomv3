/**
 * 3D Viewer Configuration
 * 
 * Choose one of two modes:
 * 1. API Mode: Load data dynamically from Laravel backend
 * 2. Local Mode: Use hardcoded configuration below
 */

window.VIEWER_CONFIG = {
  
  // ========== MODE SELECTION ==========
  
  /**
   * Set to true to load data from Laravel API
   * Set to false to use local configuration below
   */
  useAPI: false,
  
  /**
   * Laravel API base URL (only used if useAPI = true)
   */
  apiBaseUrl: 'http://localhost:8000',
  
  /**
   * Model type: 'product' or 'concept' (only used if useAPI = true)
   */
  modelType: 'product',
  
  /**
   * Model ID from database (only used if useAPI = true)
   * Example: Set to 1 to load product/concept with ID 1
   */
  modelId: null,
  
  
  // ========== LOCAL CONFIGURATION (used if useAPI = false) ==========
  
  /**
   * Main 3D model configuration
   * Can be a single object or an array of objects
   * Models will be rendered one by one, starting with index 0
   * Use switchModel(index) to change the displayed model
   */
  mainModel: [
    {
      folderPath: 'objects/miniVase3/',
      fileName: 'miniVase3.obj',
      desiredSize: 1.0
    }
    // Add more models here (will be selectable by index):
    // { folderPath: 'objects/chair/', fileName: 'chair.obj', desiredSize: 1.5 }
  ],
  
  /**
   * Component/Texture configuration
   * Format: { categoryName: [{url, name}, ...] }
   */
  components: {
    wood: [
      { url: 'component/assets/wood/wood1.jpg', name: 'Oak' },
      { url: 'component/assets/wood/wood2.jpeg', name: 'Walnut' },
      { url: 'component/assets/wood/wood3.jpg', name: 'Pine' },
      { url: 'component/assets/wood/wood4.jpg', name: 'Mahogany' }
    ],
    metal: [
      { url: 'component/assets/metal/metal1.jpg', name: 'Steel' },
      { url: 'component/assets/metal/metal2.jpg', name: 'Brass' },
      { url: 'component/assets/metal/metal3.jpg', name: 'Copper' }
    ],
    fabric: [
      { url: 'component/assets/fabric/fabric1.jpg', name: 'Linen' },
      { url: 'component/assets/fabric/fabric2.jpg', name: 'Cotton' },
      { url: 'component/assets/fabric/fabric3.jpg', name: 'Leather' },
      { url: 'component/assets/fabric/fabric4.jpg', name: 'Velvet' }
    ]
  },
  
  /**
   * Floor configuration
   * Format: { "floor-categoryName": [{url, folderPath, fileName, baseSize}, ...] }
   */
  floors: {
    "floor-test": [
      { url: "https://media.homecentre.com/i/homecentre/165555909-165555909-HC18052023_02-2100.jpg?fmt=auto&$quality-standard$&sm=c&$prodimg-m-sqr-pdp-2x$", folderPath: "objects/woodfloor3/", fileName: "woodfloor3.obj", baseSize: 2.0 },
      { url: "https://media.homeboxstores.com/i/homebox/165707654-165707654-HMBX24092023N_01-2100.jpg?fmt=auto&$quality-standard$&sm=c&$prodimg-m-sqr-pdp-2x$", folderPath: "objects/woodfloor4/", fileName: "woodfloor4.obj", baseSize: 2.0 }
    ],
    "floor-carpet": [
      { url: "https://www.angelfurniture.in/image/cache/catalog/Products/AF-250/AF250%20(1)-550x550w.jpg", folderPath: "objects/carpet2/", fileName: "carpet2.obj", baseSize: 2.0 },
      { url: "https://media.homecentre.com/i/homecentre/165555909-165555909-HC18052023_02-2100.jpg?fmt=auto&$quality-standard$&sm=c&$prodimg-m-sqr-pdp-2x$", folderPath: "objects/carpet3/", fileName: "carpet3.obj", baseSize: 2.0 }
    ],
    "floor-table": [
      { url: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSe6a0XOO_kvDu3EXEFGZmOS0vq2Adw9wFarA&s", folderPath: "objects/DiningTable1/", fileName: "DiningTable1.obj", baseSize: 2.0 },
      { url: "https://www.angelfurniture.in/image/cache/catalog/Products/AF-250/AF250%20(1)-550x550w.jpg", folderPath: "objects/roundTable1/", fileName: "roundTable1.obj", baseSize: 2.0 },
      { url: "https://media.homecentre.com/i/homecentre/165555909-165555909-HC18052023_02-2100.jpg?fmt=auto&$quality-standard$&sm=c&$prodimg-m-sqr-pdp-2x$", folderPath: "objects/tableOffice2/", fileName: "tableOffice2.obj", baseSize: 2.0 },
      { url: "https://media.homeboxstores.com/i/homebox/165707654-165707654-HMBX24092023N_01-2100.jpg?fmt=auto&$quality-standard$&sm=c&$prodimg-m-sqr-pdp-2x$", folderPath: "objects/smDrawer1/", fileName: "smDrawer1.obj", baseSize: 2.0 }
    ]
  }
};

// ========== QUICK EXAMPLES ==========

/**
 * Example 1: Load from Laravel API
 * 
 * window.VIEWER_CONFIG = {
 *   useAPI: true,
 *   apiBaseUrl: 'http://localhost:8000',
 *   modelType: 'product',
 *   modelId: 123
 * };
 */

/**
 * Example 2: Use local hardcoded data (single model)
 * 
 * window.VIEWER_CONFIG = {
 *   useAPI: false,
 *   mainModel: {
 *     folderPath: 'objects/chair/',
 *     fileName: 'chair.obj',
 *     desiredSize: 1.0
 *   },
 *   components: {
 *     wood: [
 *       { url: 'textures/oak.jpg', name: 'Oak' }
 *     ]
 *   },
 *   floors: {
 *     "floor-simple": [
 *       { url: 'preview.jpg', folderPath: 'objects/floor1/', fileName: 'floor1.obj', baseSize: 2.0 }
 *     ]
 *   }
 * };
 */

/**
 * Example 3: Multiple models (rendered one by one)
 * 
 * window.VIEWER_CONFIG = {
 *   useAPI: false,
 *   mainModel: [
 *     { folderPath: 'objects/chair/', fileName: 'chair.obj', desiredSize: 1.0 },
 *     { folderPath: 'objects/table/', fileName: 'table.obj', desiredSize: 1.5 },
 *     { folderPath: 'objects/lamp/', fileName: 'lamp.obj', desiredSize: 0.8 }
 *   ],
 *   components: {
 *     wood: [{ url: 'textures/oak.jpg', name: 'Oak' }]
 *   },
 *   floors: {
 *     "floor-simple": [{ url: 'preview.jpg', folderPath: 'objects/floor1/', fileName: 'floor1.obj', baseSize: 2.0 }]
 *   }
 * };
 * 
 * // Then use: switchModel(0), switchModel(1), switchModel(2) to switch between models
 */

/**
 * Example 4: Mix local models with API data
 * 
 * You can manually set the configuration in index.html after this script loads
 * or modify this file directly for your use case.
 */
