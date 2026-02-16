import { loadModel } from './utils/loader.js';
import { getMainModel, setMainModel, getCurrentModel, getCurrentModelIndex, setCurrentModelIndex, getTotalModels } from './api.js';

let scene, camera, renderer, controls;
let textureLoader;

let mainModelGroup = null;
let mainModelParent = null;
let floorModelGroup = null;

let raycaster = new THREE.Raycaster();
let mouse = new THREE.Vector2();

let previousMousePos = new THREE.Vector2();
let initialMaterialsSnapshot = new Map();

// Selection state
window.selectedMaterialName = null;
let originalMaterialsMap = new Map();

// Interaction flags
let isDraggingMainModel = false;
let isLocked = false;

// Touch-specific flags
let touchStartTime = 0;
let touchStartPos = { x: 0, y: 0 };
let lastTapTime = 0;
let touchOnModel = false;
let touchMoved = false; 
initScene();

// Color picker toggle functionality
document.getElementById('color-picker-toggle').addEventListener('click', () => {
  const colorPicker = document.getElementById('bg-color-picker');
  const icon = document.getElementById('color-picker-icon');
  
  const isHidden = colorPicker.classList.contains('hidden');
  
  if (isHidden) {
    colorPicker.classList.remove('hidden');
    icon.style.transform = 'rotate(130deg)';
  } else {
    colorPicker.classList.add('hidden');
    icon.style.transform = 'rotate(0deg)';
  }
});

// Color picker buttons
document.querySelectorAll('.color-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const color = btn.dataset.color;
    scene.background = new THREE.Color(color);
  });
});

// ========== MAIN MODEL LOADING (API-based) ==========

/**
 * Load the main model using current index
 * Falls back to default if no configuration is set
 * Loads only the current model (by index), not all at once
 */
async function loadMainModel() {
  // Get configuration from API or use default
  let config = getCurrentModel();
  
  // Default configuration if none is set
  if (!config) {
    console.warn('⚠️ No main model configured via API. Using default configuration.');
    config = {
      folderPath: 'objects/tableOffice2/',
      fileName: 'tableOffice2.obj',
      desiredSize: 1.0
    };
    // Set it so it's available for future reference
    setMainModel(config);
  }
  
  const totalModels = getTotalModels();
  const currentIndex = getCurrentModelIndex();
  
  console.log(`📦 Loading model ${currentIndex + 1}/${totalModels}:`, config);
  
  try {
    // Create parent group if it doesn't exist
    if (!mainModelParent) {
      mainModelParent = new THREE.Group();
      scene.add(mainModelParent);
      window.mainModelParent = mainModelParent;
    }
    
    // Load the current model
    const group = await loadModel(scene, camera, controls, config);
    
    // Store initial materials for each mesh
    group.traverse((child) => {
      if (child.isMesh) {
        initialMaterialsSnapshot.set(child.uuid, child.material.clone());
      }
    });
    
    // Add to parent
    mainModelParent.add(group);
    
    // Store as main model
    mainModelGroup = group;
    window.mainModelGroup = mainModelGroup;
    
    updateControlsTarget();

    console.log(`✅ Successfully loaded model at index ${currentIndex}!`);
    if (totalModels > 1) {
      console.log(`   💡 Tip: Use switchModel(index) to load other models. Total available: ${totalModels}`);
    }
    
    return mainModelGroup;
  } catch (err) {
    console.error('❌ Failed to load main model:', err);
    throw err;
  }
}

/**
 * Switch to a different model by index
 * @param {number} index - Model index to load (0-based)
 */
async function switchModel(index) {
  const totalModels = getTotalModels();
  
  if (index < 0 || index >= totalModels) {
    console.error(`❌ Invalid model index: ${index}. Available: 0-${totalModels - 1}`);
    return false;
  }
  
  console.log(`🔄 Switching to model ${index + 1}/${totalModels}...`);
  
  // Set the new index
  setCurrentModelIndex(index);
  
  // Remove current model
  if (mainModelGroup && mainModelParent) {
    console.log('🧹 Removing current model...');
    
    // Dispose geometry and materials
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
    
    mainModelParent.remove(mainModelGroup);
  }
  
  // Clear materials snapshot
  initialMaterialsSnapshot.clear();
  
  // Load new model
  await loadMainModel();
  
  console.log(`✅ Switched to model ${index + 1}/${totalModels}`);
  return true;
}

// Expose globally
window.loadMainModel = loadMainModel;
window.switchModel = switchModel;
window.getTotalModels = getTotalModels;
window.getCurrentModelIndex = getCurrentModelIndex;

// Export so it can be called externally if needed
export { loadMainModel };

// Auto-load the main model when scene is ready
window.addEventListener('DOMContentLoaded', () => {
  // Small delay to ensure scene is initialized
  setTimeout(() => {
    loadMainModel().catch(err => {
      console.error('Failed to auto-load main model:', err);
    });
  }, 100);
});

function wait(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

// Load floor model
export async function loadFloorModel({ folderPath, fileName, desiredSize = 1.0 }) {
  if (floorModelGroup) {
    floorModelGroup.traverse(child => {
      if (child.isMesh) {
        if (child.geometry) child.geometry.dispose();
        if (child.material) {
          if (Array.isArray(child.material)) {
            child.material.forEach(mat => {
              if (mat.map && typeof mat.map.dispose === 'function') {
                mat.map.dispose();
              }
              mat.dispose();
            });
          } else {
            if (child.material.map && typeof child.material.map.dispose === 'function') {
              child.material.map.dispose();
            }
            child.material.dispose();
          }
        }
      }
    });
    scene.remove(floorModelGroup);
    floorModelGroup = null;
  }

  floorModelGroup = await loadModel(scene, camera, controls, { folderPath,fileName, desiredSize });
 updateGlobalAccessors(); 
  if (floorModelGroup.parent !== scene) {
    scene.add(floorModelGroup);
  }

  floorModelGroup.rotation.set(0, 0, 0);
  floorModelGroup.updateMatrixWorld(true);

  if (mainModelParent && mainModelGroup && floorModelGroup) {
    mainModelParent.position.set(0, 0, 0);
    await wait(50);
    
    const mainBox = new THREE.Box3().setFromObject(mainModelGroup);
    const floorBox = new THREE.Box3().setFromObject(floorModelGroup);

    console.log('Main Model Box:', mainBox.min, mainBox.max);
    console.log('Floor Model Box:', floorBox.min, floorBox.max);

    const floorTopY = floorBox.max.y;
    const mainMinY = mainBox.min.y;
    const desiredParentY = floorTopY - mainMinY;
    
    console.log('Setting mainModelParent Y to:', desiredParentY);
    mainModelParent.position.set(0, desiredParentY, 0);
    updateControlsTarget();
  }

  console.log('Floor loaded:', folderPath + fileName);
}

export function showFloorAndAdjustMain(showFloor) {
  if (!floorModelGroup || !mainModelParent || !mainModelGroup) return;

  floorModelGroup.visible = showFloor;

  if (showFloor) {
    const floorBox = new THREE.Box3().setFromObject(floorModelGroup);
    const floorTopY = floorBox.max.y;
    const mainBox = new THREE.Box3().setFromObject(mainModelGroup);
    const mainMinY = mainBox.min.y;

    mainModelParent.position.y = floorTopY - mainMinY;
    updateControlsTarget();
  } else {
    mainModelParent.position.y = 0;
    controls.target.set(0, 0, 0);
    controls.update();
  }
}

// NUCLEAR OPTION: Recreate OrbitControls
function recreateOrbitControls() {
  console.log('🔥 RECREATING OrbitControls');
  
  // Store camera position
  const camPos = camera.position.clone();
  const camTarget = controls.target.clone();
  
  // Dispose old controls
  controls.dispose();
  
  // Create new controls
  controls = new THREE.OrbitControls(camera, renderer.domElement);
  controls.enableDamping = true;
  controls.dampingFactor = 0.05;
  controls.minDistance = 1.6;
  controls.maxDistance = 7;
  controls.target.copy(camTarget);
  controls.enabled = true;
  
  // Restore camera
  camera.position.copy(camPos);
  controls.update();
  
  console.log('✅ OrbitControls recreated and enabled');
}


function updateGlobalAccessors() {
  window.mainModelParent = mainModelParent;
  window.mainModelGroup = mainModelGroup;
  window.floorModelGroup = floorModelGroup;
  window.scene = scene;
  window.loadFloorModel = loadFloorModel;
  window.loadMainModel = loadMainModel; // Make it globally accessible
}
// Scene setup
function initScene() {
  scene = new THREE.Scene();
   scene.background = new THREE.Color(0xeeeeee);
   textureLoader = new THREE.TextureLoader();
  mainModelParent = new THREE.Group();
  scene.add(mainModelParent);

  // Set window variables AFTER everything is initialized
 updateGlobalAccessors();

  camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 1, 100000);
  camera.position.set(5, 5, 5);

  renderer = new THREE.WebGLRenderer({ 
    antialias: true,
    alpha: true,
    powerPreference: "high-performance"
  });
  
  renderer.setSize(window.innerWidth, window.innerHeight);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.outputEncoding = THREE.sRGBEncoding;
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.0;
  renderer.shadowMap.enabled = true;
  renderer.shadowMap.type = THREE.PCFSoftShadowMap;
  renderer.physicallyCorrectLights = true;
  
  document.body.appendChild(renderer.domElement);

  controls = new THREE.OrbitControls(camera, renderer.domElement);
  controls.enableDamping = true;
  controls.dampingFactor = 0.05;
  controls.minDistance = 1.6;
  controls.maxDistance = 7;

  // Lighting
  const ambientLight = new THREE.AmbientLight(0xffffff, 0.4);
  scene.add(ambientLight);

  const mainLight = new THREE.DirectionalLight(0xffffff, 1.2);
  mainLight.position.set(5, 8, 7);
  mainLight.castShadow = true;
  mainLight.shadow.mapSize.width = 2048;
  mainLight.shadow.mapSize.height = 2048;
  mainLight.shadow.camera.near = 0.5;
  mainLight.shadow.camera.far = 50;
  mainLight.shadow.camera.left = -10;
  mainLight.shadow.camera.right = 10;
  mainLight.shadow.camera.top = 10;
  mainLight.shadow.camera.bottom = -10;
  mainLight.shadow.bias = -0.0001;
  scene.add(mainLight);

  const fillLight = new THREE.DirectionalLight(0xffffff, 0.5);
  fillLight.position.set(-5, 3, -5);
  scene.add(fillLight);

  const hemiLight = new THREE.HemisphereLight(0xffffff, 0x444444, 0.6);
  hemiLight.position.set(0, 20, 0);
  scene.add(hemiLight);

  const rimLight = new THREE.DirectionalLight(0xffffff, 0.3);
  rimLight.position.set(0, 2, -8);
  scene.add(rimLight);

  window.addEventListener('resize', onWindowResize);
  addInteractionListeners();
  animate();

  // Double click for selection
  renderer.domElement.addEventListener('dblclick', (event) => {
    const ndc = getMouseNDC(event);
    handleSelectionLogic(ndc);
  });
}

function onWindowResize() {
  camera.aspect = window.innerWidth / window.innerHeight;
  camera.updateProjectionMatrix();
  renderer.setSize(window.innerWidth, window.innerHeight);
}

function animate() {
  requestAnimationFrame(animate);
  controls.update();
  renderer.render(scene, camera);
}

window.rotateMainModel = (angleRad) => {
  if (!mainModelParent) return;
  mainModelParent.rotation.y += angleRad;
};

export function fitObjectToScene(object, camera, controls, desiredSize = 1.0) {
  const box = new THREE.Box3().setFromObject(object);
  const size = box.getSize(new THREE.Vector3());
  const center = box.getCenter(new THREE.Vector3());

  const maxDim = Math.max(size.x, size.y, size.z);
  const scale = desiredSize / maxDim;
  object.scale.setScalar(scale);

  box.setFromObject(object);
  box.getCenter(center);
  object.position.sub(center);

  const fitOffset = 1.2;
  const sphere = box.getBoundingSphere(new THREE.Sphere());
  const distance = fitOffset * sphere.radius / Math.tan(THREE.MathUtils.degToRad(camera.fov * 0.5));

  camera.position.set(distance, distance, distance);
  camera.lookAt(0, 0, 0);
  controls.target.set(0, 0, 0);
  controls.update();
}

function updateControlsTarget() {
  if (!mainModelGroup || !floorModelGroup) return;

  const box = new THREE.Box3();
  box.expandByObject(mainModelGroup);
  box.expandByObject(floorModelGroup);

  const center = box.getCenter(new THREE.Vector3());
  controls.target.copy(center);
  controls.update();
}

function alignMainModelToFloor() {
  if (!mainModelGroup || !floorModelGroup) return;

  const mainBox = new THREE.Box3().setFromObject(mainModelGroup);
  const floorBox = new THREE.Box3().setFromObject(floorModelGroup);

  const floorTopY = floorBox.max.y;
  const mainMinY = mainBox.min.y;

  mainModelParent.position.y += floorTopY - mainMinY;
}

// ========== MOUSE HANDLERS ==========
function onMouseDown(event) {
  const ndc = getMouseNDC(event);
  mouse.set(ndc.x, ndc.y);
  raycaster.setFromCamera(mouse, camera);

  const intersects = raycaster.intersectObject(mainModelGroup, true);

  if (intersects.length > 0 && !isLocked) {
    isDraggingMainModel = true;
    previousMousePos.set(event.clientX, event.clientY);
    controls.enabled = false;
  } else {
    isDraggingMainModel = false;
    controls.enabled = true;
  }
}

function onMouseUp() {
  if (isDraggingMainModel) {
    alignMainModelToFloorRaycast();
  }
  isDraggingMainModel = false;
  controls.enabled = true;
}

function onMouseMove(event) {
  if (!isDraggingMainModel) return;

  const deltaX = event.clientX - previousMousePos.x;
  const deltaY = event.clientY - previousMousePos.y;
  const rotationSpeed = 0.01;

  mainModelParent.rotation.y += deltaX * rotationSpeed;
  mainModelParent.rotation.x += deltaY * rotationSpeed;
  mainModelParent.rotation.x = THREE.MathUtils.clamp(
    mainModelParent.rotation.x, 
    -Math.PI / 2, 
    Math.PI / 2
  );

  alignMainModelToFloor();
  previousMousePos.set(event.clientX, event.clientY);
}

// ========== TOUCH HANDLERS WITH EXTENSIVE LOGGING ==========

function onTouchStart(event) {
  const touchCount = event.touches.length;
  console.log(`
📱 TOUCH START
   Touches: ${touchCount}
   Controls enabled: ${controls.enabled}
   isDraggingMainModel: ${isDraggingMainModel}
  `);

  // Multi-touch
  if (touchCount >= 2) {
    console.log('   → Multi-touch detected - resetting state');
    isDraggingMainModel = false;
    touchOnModel = false;
    touchMoved = false;
    controls.enabled = true;
    return; // Let OrbitControls handle
  }

  const touch = event.touches[0];
  const currentTime = Date.now();
  const tapGap = currentTime - lastTapTime;

  // Double tap
  if (tapGap < 300 && tapGap > 0) {
    console.log('   → Double tap detected');
    event.preventDefault();
    lastTapTime = 0;
    const ndc = getMouseNDC(touch);
    handleSelectionLogic(ndc);
    return;
  }

  lastTapTime = currentTime;
  touchStartTime = currentTime;
  touchStartPos = { x: touch.clientX, y: touch.clientY };
  touchMoved = false;
  
  // Raycast to check what was touched
  const ndc = getMouseNDC(touch);
  mouse.set(ndc.x, ndc.y);
  raycaster.setFromCamera(mouse, camera);
  const intersects = raycaster.intersectObject(mainModelGroup, true);

  if (intersects.length > 0 && !isLocked) {
    console.log('   → Touched MAIN MODEL');
    touchOnModel = true;
    previousMousePos.set(touch.clientX, touch.clientY);
  } else {
    console.log('   → Touched FLOOR/BACKGROUND');
    touchOnModel = false;
    isDraggingMainModel = false;
    controls.enabled = true;
  }
  
  console.log(`   Final state: touchOnModel=${touchOnModel}, controls.enabled=${controls.enabled}`);
}

function onTouchMove(event) {
  const touchCount = event.touches.length;

  if (touchCount >= 2) {
    console.log('📱 TOUCH MOVE - Multi-touch, resetting');
    isDraggingMainModel = false;
    touchOnModel = false;
    touchMoved = false;
    controls.enabled = true;
    return;
  }

  const touch = event.touches[0];

  // If didn't touch model, let OrbitControls work
  if (!touchOnModel) {
    return;
  }

  const dx = touch.clientX - touchStartPos.x;
  const dy = touch.clientY - touchStartPos.y;
  const moveDistance = Math.sqrt(dx * dx + dy * dy);

  if (moveDistance > 20 && !touchMoved) {
    console.log(`
📱 TOUCH MOVE - Starting drag
   Distance: ${moveDistance.toFixed(1)}px
   Disabling controls
    `);
    isDraggingMainModel = true;
    touchMoved = true;
    controls.enabled = false;
  }

  if (isDraggingMainModel) {
    event.preventDefault();
    event.stopPropagation();

    const deltaX = touch.clientX - previousMousePos.x;
    const deltaY = touch.clientY - previousMousePos.y;
    const rotationSpeed = 0.015;

    mainModelParent.rotation.y += deltaX * rotationSpeed;
    mainModelParent.rotation.x += deltaY * rotationSpeed;
    mainModelParent.rotation.x = THREE.MathUtils.clamp(
      mainModelParent.rotation.x,
      -Math.PI / 2,
      Math.PI / 2
    );

    alignMainModelToFloor();
    previousMousePos.set(touch.clientX, touch.clientY);
  }
}

function onTouchEnd(event) {
  console.log(`
📱 TOUCH END
   Was dragging: ${isDraggingMainModel}
   Touch moved: ${touchMoved}
   Controls enabled (before): ${controls.enabled}
  `);
  
  if (isDraggingMainModel && touchMoved) {
    alignMainModelToFloorRaycast();
    console.log('   → Aligned model to floor');
    
    // NUCLEAR OPTION: Recreate OrbitControls
    recreateOrbitControls();
  }

  // Reset everything
  isDraggingMainModel = false;
  touchOnModel = false;
  touchMoved = false;
  controls.enabled = true;
  
  console.log(`   Controls enabled (after): ${controls.enabled}
   All flags reset
  `);
}

function onTouchCancel(event) {
  console.log('📱 TOUCH CANCELLED');
  isDraggingMainModel = false;
  touchOnModel = false;
  touchMoved = false;
  controls.enabled = true;
  recreateOrbitControls();
}

// ========== EVENT LISTENERS ==========
function addInteractionListeners() {
  const dom = renderer.domElement;

  // Mouse events
  dom.addEventListener('mousedown', onMouseDown, false);
  dom.addEventListener('mousemove', onMouseMove, false);
  dom.addEventListener('mouseup', onMouseUp, false);
  dom.addEventListener('mouseleave', onMouseUp, false);

  // Touch events - NO capture, normal flow
  dom.addEventListener('touchstart', onTouchStart, { passive: false });
  dom.addEventListener('touchmove', onTouchMove, { passive: false });
  dom.addEventListener('touchend', onTouchEnd, { passive: false });
  dom.addEventListener('touchcancel', onTouchCancel, { passive: false });
}

function getMouseNDC(event) {
  let clientX, clientY;
  
  if (event.clientX !== undefined) {
    clientX = event.clientX;
    clientY = event.clientY;
  } else if (event.touches && event.touches[0]) {
    clientX = event.touches[0].clientX;
    clientY = event.touches[0].clientY;
  } else if (event.changedTouches && event.changedTouches[0]) {
    clientX = event.changedTouches[0].clientX;
    clientY = event.changedTouches[0].clientY;
  } else {
    return { x: 0, y: 0 };
  }
  
  const rect = renderer.domElement.getBoundingClientRect();
  return {
    x: ((clientX - rect.left) / rect.width) * 2 - 1,
    y: -((clientY - rect.top) / rect.height) * 2 + 1
  };
}

function alignMainModelToFloorRaycast() {
  if (!mainModelGroup || !floorModelGroup) return;

  const box = new THREE.Box3().setFromObject(mainModelGroup);
  const center = box.getCenter(new THREE.Vector3());

  const ray = new THREE.Raycaster(
    new THREE.Vector3(center.x, box.max.y + 10, center.z),
    new THREE.Vector3(0, -1, 0)
  );

  const intersects = ray.intersectObject(floorModelGroup, true);

  if (intersects.length > 0) {
    const floorY = intersects[0].point.y;
    let minY = Infinity;
    
    mainModelGroup.traverse(child => {
      if (child.isMesh) {
        const posAttr = child.geometry.attributes.position;
        const vertex = new THREE.Vector3();
        for (let i = 0; i < posAttr.count; i++) {
          vertex.fromBufferAttribute(posAttr, i).applyMatrix4(child.matrixWorld);
          if (vertex.y < minY) minY = vertex.y;
        }
      }
    });

    mainModelParent.position.y += floorY - minY;
  }
}

function highlightComponent(clickedMesh) {
  restorePreviousSelection();

  window.selectedMaterialName = clickedMesh.material.name;
  console.log("Selecting all parts with material:", window.selectedMaterialName);

  const highlightMat = clickedMesh.material.clone();
  highlightMat.name = window.selectedMaterialName;
  highlightMat.transparent = true;
  highlightMat.opacity = 0.6;
  highlightMat.color.set(0x00aaff);
  highlightMat.emissive.set(0x0044ff);

  mainModelGroup.traverse((child) => {
    if (child.isMesh && child.material.name === window.selectedMaterialName) {
      originalMaterialsMap.set(child.uuid, child.material);
      child.material = highlightMat;
    }
  });
}

function restorePreviousSelection() {
  if (window.selectedMaterialName === null) return;

  mainModelGroup.traverse((child) => {
    if (child.isMesh && originalMaterialsMap.has(child.uuid)) {
      child.material = originalMaterialsMap.get(child.uuid);
    }
  });

  originalMaterialsMap.clear();
  window.selectedMaterialName = null;
}

window.applyTextureToSelected = function(textureUrl) {
  if (!window.selectedMaterialName) {
    alert("Please double-click a part of the object first!");
    return;
  }

  const loader = new THREE.TextureLoader();
  loader.load(textureUrl, (texture) => {
    texture.encoding = THREE.sRGBEncoding;
    texture.wrapS = THREE.RepeatWrapping;
    texture.wrapT = THREE.RepeatWrapping;
    texture.flipY = false;

    originalMaterialsMap.forEach((originalMat) => {
      originalMat.map = texture;
      originalMat.color.set(0xffffff);
      originalMat.needsUpdate = true;
    });

    mainModelGroup.traverse((child) => {
      if (child.isMesh && child.material.name === window.selectedMaterialName) {
        child.material.map = texture;
        child.material.needsUpdate = true;
      }
    });

    const resetBtn = document.getElementById('reset-textures-btn');
    if (resetBtn) resetBtn.style.display = 'block';
    
    isLocked = false;
    console.log(`Applied texture ${textureUrl} to ${window.selectedMaterialName}`);
  });
  return true;
};

window.resetModelTextures = function() {
  if (!mainModelGroup) return;

  window.selectedMaterialName = null;
  originalMaterialsMap.clear();

  mainModelGroup.traverse((child) => {
    if (child.isMesh && initialMaterialsSnapshot.has(child.uuid)) {
      child.material = initialMaterialsSnapshot.get(child.uuid).clone();
      child.material.needsUpdate = true;
    }
  });

  const btn = document.getElementById('reset-textures-btn');
  if (btn) btn.style.display = 'none';
  
  restorePreviousSelection();
  console.log("Model textures reset to original state.");
};

export function removeFloorModel() {
  if (floorModelGroup) {
    floorModelGroup.traverse(child => {
      if (child.isMesh) {
        if (child.geometry) child.geometry.dispose();
        if (child.material) {
          if (Array.isArray(child.material)) {
            child.material.forEach(m => m.dispose());
          } else {
            child.material.dispose();
          }
        }
      }
    });
    scene.remove(floorModelGroup);
    floorModelGroup = null;
    
    window.floorModelGroup = null;
  }

  if (mainModelParent) {
    mainModelParent.position.set(0, 0, 0);
  }
  if (controls) {
    controls.target.set(0, 0, 0);
    controls.update();
  }

  window.lastSelectedFloor = { folderPath: null, fileName: null, baseSize: 1 };
  window.hideFloorScale();

  console.log("Floor removed and model reset to ground.");
}

export function resetEverything() {
  removeFloorModel();

  if (mainModelGroup && initialMaterialsSnapshot.size > 0) {
    mainModelGroup.traverse((child) => {
      if (child.isMesh) {
        const originalMat = initialMaterialsSnapshot.get(child.uuid);
        if (originalMat) {
          if (child.material) child.material.dispose();
          child.material = originalMat.clone();
        }
      }
    });
  }

  window.selectedMaterialName = null;
  originalMaterialsMap.clear();
  isLocked = false;

  const globalResetBtn = document.getElementById('global-reset-btn');
  if (globalResetBtn) globalResetBtn.style.display = 'none';
  
  const textureResetBtn = document.getElementById('reset-textures-btn');
  if (textureResetBtn) textureResetBtn.style.display = 'none';

  window.hideAllLists();
  
  console.log("Global reset complete: Floor removed and textures restored.");
}

export function updateSelectedTint(colorValue, alpha) {
  if (!window.selectedMaterialName) return;

  // Map 0-100 slider value to colors:
  // 0 = black, then through rainbow spectrum, 100 = white
  let tintColor;
  
  if (colorValue <= 14) {
    // Black to Red (0-14)
    const t = colorValue / 14;
    tintColor = new THREE.Color().lerpColors(
      new THREE.Color(0x000000), 
      new THREE.Color(0xff0000), 
      t
    );
  } else if (colorValue <= 28) {
    // Red to Magenta (14-28)
    const t = (colorValue - 14) / 14;
    tintColor = new THREE.Color().lerpColors(
      new THREE.Color(0xff0000), 
      new THREE.Color(0xff00ff), 
      t
    );
  } else if (colorValue <= 42) {
    // Magenta to Blue (28-42)
    const t = (colorValue - 28) / 14;
    tintColor = new THREE.Color().lerpColors(
      new THREE.Color(0xff00ff), 
      new THREE.Color(0x0000ff), 
      t
    );
  } else if (colorValue <= 56) {
    // Blue to Cyan (42-56)
    const t = (colorValue - 42) / 14;
    tintColor = new THREE.Color().lerpColors(
      new THREE.Color(0x0000ff), 
      new THREE.Color(0x00ffff), 
      t
    );
  } else if (colorValue <= 70) {
    // Cyan to Green (56-70)
    const t = (colorValue - 56) / 14;
    tintColor = new THREE.Color().lerpColors(
      new THREE.Color(0x00ffff), 
      new THREE.Color(0x00ff00), 
      t
    );
  } else if (colorValue <= 84) {
    // Green to Yellow (70-84)
    const t = (colorValue - 70) / 14;
    tintColor = new THREE.Color().lerpColors(
      new THREE.Color(0x00ff00), 
      new THREE.Color(0xffff00), 
      t
    );
  } else {
    // Yellow to White (84-100)
    const t = (colorValue - 84) / 16;
    tintColor = new THREE.Color().lerpColors(
      new THREE.Color(0xffff00), 
      new THREE.Color(0xffffff), 
      t
    );
  }

  // NEW APPROACH: Blend the color overlay with the texture
  // Instead of making the material transparent, we blend the tint color
  originalMaterialsMap.forEach((mat) => {
    // Keep the texture always visible at full opacity
    mat.transparent = false;
    mat.opacity = 1.0;
    
    // Blend between the original texture color and the tint color based on alpha
    // alpha = 0 means 100% texture (white color multiplier)
    // alpha = 1 means 100% tint color
    const blendedColor = new THREE.Color(0xffffff).lerp(tintColor, alpha);
    mat.color.copy(blendedColor);
    
    mat.needsUpdate = true;
  });
  
  isLocked = false;
}

export function removeHighlightOnly() {
  if (window.selectedMaterialName === null) return;

  mainModelGroup.traverse((child) => {
    if (child.isMesh && child.material.name === window.selectedMaterialName) {
      if (originalMaterialsMap.has(child.uuid)) {
        child.material = originalMaterialsMap.get(child.uuid);
      }
    }
  });
}

function handleSelectionLogic(ndc) {
  mouse.set(ndc.x, ndc.y);
  raycaster.setFromCamera(mouse, camera);

  const intersects = raycaster.intersectObject(mainModelGroup, true);

  if (intersects.length > 0) {
    isLocked = true;
    const clickedMesh = intersects[0].object;
    const clickedMaterialName = clickedMesh.material.name;

    if (window.selectedMaterialName !== null && clickedMaterialName === window.selectedMaterialName) {
      console.log("Deselecting and hiding UI...");
      restorePreviousSelection();
      
      if (window.hideAllLists) window.hideAllLists();
    } else {
      if (window.hideAllLists) window.hideAllLists();
      console.log("Selecting and showing UI...");
      highlightComponent(clickedMesh);
      
      const cl = document.getElementById('components-list');
      if (cl) cl.style.display = 'flex';
      
      if (typeof window.selectSidebarItem === 'function') {
        window.selectSidebarItem('components-tool');
      }
    }
  } else {
    restorePreviousSelection();
    if (window.hideAllLists) window.hideAllLists();
    isLocked = false;
  }
}

// Custom Alert Function
window.customAlert = function(message) {
  const overlay = document.getElementById('custom-alert-overlay');
  const messageEl = document.getElementById('custom-alert-message');
  const okBtn = document.getElementById('custom-alert-ok-btn');

  // Set the message
  messageEl.textContent = message;

  // Show the overlay
  overlay.classList.remove('hidden');

  // Handle OK button click
  const closeAlert = () => {
    overlay.classList.add('hidden');
    okBtn.removeEventListener('click', closeAlert);
    overlay.removeEventListener('click', handleOverlayClick);
  };

  // Close when clicking outside the box
  const handleOverlayClick = (e) => {
    if (e.target === overlay) {
      closeAlert();
    }
  };

  okBtn.addEventListener('click', closeAlert);
  overlay.addEventListener('click', handleOverlayClick);
};